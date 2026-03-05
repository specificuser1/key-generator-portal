<?php
require_once 'config.php';

// Validate admin license
function validateLicense($license) {
    $validLicense = trim(file_get_contents(ADMIN_LICENSE_FILE));
    return $license === $validLicense;
}

// Read JSON file
function readJsonFile($file) {
    if (!file_exists($file)) {
        return [];
    }
    $content = file_get_contents($file);
    return json_decode($content, true) ?: [];
}

// Write JSON file
function writeJsonFile($file, $data) {
    return file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}

// Get all fresh keys
function getFreshKeys() {
    return readJsonFile(FRESH_KEYS_FILE);
}

// Get all redeemed keys
function getRedeemedKeys() {
    return readJsonFile(REDEEMED_KEYS_FILE);
}

// Get user data
function getUsers() {
    return readJsonFile(USERS_FILE);
}

// Save fresh keys
function saveFreshKeys($keys) {
    return writeJsonFile(FRESH_KEYS_FILE, $keys);
}

// Save redeemed keys
function saveRedeemedKeys($keys) {
    return writeJsonFile(REDEEMED_KEYS_FILE, $keys);
}

// Save users
function saveUsers($users) {
    return writeJsonFile(USERS_FILE, $users);
}

// Check if user can redeem
function canUserRedeem($userId) {
    $users = getUsers();
    
    if (!isset($users[$userId])) {
        return true; // New user
    }
    
    $lastRedeem = $users[$userId]['last_redeem'];
    $timePassed = time() - $lastRedeem;
    
    return $timePassed >= REDEEM_COOLDOWN;
}

// Get next redeem time for user
function getNextRedeemTime($userId) {
    $users = getUsers();
    
    if (!isset($users[$userId])) {
        return time();
    }
    
    return $users[$userId]['last_redeem'] + REDEEM_COOLDOWN;
}

// Redeem a key for user
function redeemKey($userId) {
    $freshKeys = getFreshKeys();
    
    if (empty($freshKeys)) {
        return [
            'success' => false,
            'message' => 'No keys available at the moment. Please try again later.'
        ];
    }
    
    if (!canUserRedeem($userId)) {
        $nextTime = getNextRedeemTime($userId);
        $timeRemaining = $nextTime - time();
        $hours = floor($timeRemaining / 3600);
        $minutes = floor(($timeRemaining % 3600) / 60);
        
        return [
            'success' => false,
            'message' => "You can redeem again in {$hours}h {$minutes}m"
        ];
    }
    
    // Get first available key
    $keyData = array_shift($freshKeys);
    $key = $keyData['key'];
    
    // Move to redeemed
    $redeemedKeys = getRedeemedKeys();
    $redeemedKeys[] = [
        'key' => $key,
        'user_id' => $userId,
        'redeemed_at' => time(),
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
    ];
    
    // Update user data
    $users = getUsers();
    $users[$userId] = [
        'last_redeem' => time(),
        'total_redeems' => ($users[$userId]['total_redeems'] ?? 0) + 1,
        'last_ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
    ];
    
    // Save all changes
    saveFreshKeys($freshKeys);
    saveRedeemedKeys($redeemedKeys);
    saveUsers($users);
    
    $nextRedeem = date('M d, Y H:i:s', time() + REDEEM_COOLDOWN);
    
    return [
        'success' => true,
        'key' => $key,
        'nextRedeem' => $nextRedeem
    ];
}

// Add new keys
function addKeys($keysArray) {
    $freshKeys = getFreshKeys();
    $added = 0;
    $duplicates = 0;
    
    foreach ($keysArray as $key) {
        $key = trim($key);
        if (empty($key)) continue;
        
        // Check if key already exists
        $exists = false;
        foreach ($freshKeys as $existing) {
            if ($existing['key'] === $key) {
                $exists = true;
                break;
            }
        }
        
        // Also check in redeemed keys
        if (!$exists) {
            $redeemedKeys = getRedeemedKeys();
            foreach ($redeemedKeys as $redeemed) {
                if ($redeemed['key'] === $key) {
                    $exists = true;
                    break;
                }
            }
        }
        
        if (!$exists) {
            $freshKeys[] = [
                'key' => $key,
                'created_at' => time(),
                'created_by' => 'admin'
            ];
            $added++;
        } else {
            $duplicates++;
        }
    }
    
    saveFreshKeys($freshKeys);
    
    return [
        'success' => true,
        'added' => $added,
        'duplicates' => $duplicates
    ];
}

// Get statistics
function getStatistics() {
    $freshKeys = getFreshKeys();
    $redeemedKeys = getRedeemedKeys();
    
    return [
        'total' => count($freshKeys) + count($redeemedKeys),
        'available' => count($freshKeys),
        'redeemed' => count($redeemedKeys)
    ];
}

// Get recent redemptions
function getRecentRedemptions($limit = 10) {
    $redeemedKeys = getRedeemedKeys();
    
    // Sort by redemption time (newest first)
    usort($redeemedKeys, function($a, $b) {
        return $b['redeemed_at'] - $a['redeemed_at'];
    });
    
    return array_slice($redeemedKeys, 0, $limit);
}

// Get all keys with status
function getAllKeys() {
    $freshKeys = getFreshKeys();
    $redeemedKeys = getRedeemedKeys();
    
    $allKeys = [];
    
    foreach ($freshKeys as $keyData) {
        $allKeys[] = [
            'key' => $keyData['key'],
            'status' => 'fresh',
            'created_at' => $keyData['created_at']
        ];
    }
    
    foreach ($redeemedKeys as $keyData) {
        $allKeys[] = [
            'key' => $keyData['key'],
            'status' => 'redeemed',
            'created_at' => $keyData['redeemed_at'],
            'user_id' => $keyData['user_id']
        ];
    }
    
    // Sort by creation time
    usort($allKeys, function($a, $b) {
        return $b['created_at'] - $a['created_at'];
    });
    
    return $allKeys;
}

// Delete a specific key
function deleteKey($key) {
    $freshKeys = getFreshKeys();
    $redeemedKeys = getRedeemedKeys();
    
    // Remove from fresh keys
    $freshKeys = array_filter($freshKeys, function($item) use ($key) {
        return $item['key'] !== $key;
    });
    
    // Remove from redeemed keys
    $redeemedKeys = array_filter($redeemedKeys, function($item) use ($key) {
        return $item['key'] !== $key;
    });
    
    // Re-index arrays
    $freshKeys = array_values($freshKeys);
    $redeemedKeys = array_values($redeemedKeys);
    
    saveFreshKeys($freshKeys);
    saveRedeemedKeys($redeemedKeys);
    
    return true;
}

// Clear all keys
function clearAllKeys() {
    saveFreshKeys([]);
    saveRedeemedKeys([]);
    return true;
}

// Export keys to text
function exportKeys($keys, $filename) {
    $content = '';
    foreach ($keys as $keyData) {
        $content .= $keyData['key'] . "\n";
    }
    
    header('Content-Type: text/plain');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    echo $content;
    exit;
}

// Generate random key
function generateRandomKey() {
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $key = 'KEY-';
    
    for ($i = 0; $i < 3; $i++) {
        for ($j = 0; $j < 4; $j++) {
            $key .= $chars[rand(0, strlen($chars) - 1)];
        }
        if ($i < 2) $key .= '-';
    }
    
    return $key;
}

// Update admin license
function updateAdminLicense($newLicense) {
    return file_put_contents(ADMIN_LICENSE_FILE, trim($newLicense));
}
?>
