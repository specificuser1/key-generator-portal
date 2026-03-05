<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

// Public endpoints
if ($action === 'getKey') {
    $input = json_decode(file_get_contents('php://input'), true);
    $userId = $input['userId'] ?? '';
    
    if (empty($userId)) {
        echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
        exit;
    }
    
    $result = redeemKey($userId);
    echo json_encode($result);
    exit;
}

// Admin-only endpoints
if (!isset($_SESSION['admin_logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

switch ($action) {
    case 'addKeys':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }
        
        $keysText = $_POST['keys'] ?? '';
        
        // Parse keys (support both newline and comma separation)
        $keysText = str_replace(',', "\n", $keysText);
        $keysArray = explode("\n", $keysText);
        $keysArray = array_map('trim', $keysArray);
        $keysArray = array_filter($keysArray);
        
        if (empty($keysArray)) {
            header('Location: admin.php?error=no_keys');
            exit;
        }
        
        $result = addKeys($keysArray);
        header('Location: admin.php?success=keys_added&added=' . $result['added'] . '&duplicates=' . $result['duplicates']);
        exit;
    
    case 'changeLicense':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: admin.php?error=invalid_request');
            exit;
        }
        
        $newLicense = trim($_POST['new_license'] ?? '');
        $confirmLicense = trim($_POST['confirm_license'] ?? '');
        
        if (empty($newLicense) || empty($confirmLicense)) {
            header('Location: admin.php?error=license_empty');
            exit;
        }
        
        if ($newLicense !== $confirmLicense) {
            header('Location: admin.php?error=license_mismatch');
            exit;
        }
        
        if (strlen($newLicense) < 6) {
            header('Location: admin.php?error=license_short');
            exit;
        }
        
        updateAdminLicense($newLicense);
        
        // Update session
        $_SESSION['license_key'] = $newLicense;
        
        header('Location: admin.php?success=license_updated');
        exit;
        
    case 'getAllKeys':
        $keys = getAllKeys();
        echo json_encode(['success' => true, 'keys' => $keys]);
        exit;
        
    case 'deleteKey':
        $input = json_decode(file_get_contents('php://input'), true);
        $key = $input['key'] ?? '';
        
        if (empty($key)) {
            echo json_encode(['success' => false, 'message' => 'Invalid key']);
            exit;
        }
        
        deleteKey($key);
        echo json_encode(['success' => true]);
        exit;
        
    case 'clearAllKeys':
        clearAllKeys();
        echo json_encode(['success' => true]);
        exit;
        
    case 'exportFresh':
        $keys = getFreshKeys();
        exportKeys($keys, 'fresh_keys_' . date('Y-m-d') . '.txt');
        exit;
        
    case 'exportRedeemed':
        $keys = getRedeemedKeys();
        exportKeys($keys, 'redeemed_keys_' . date('Y-m-d') . '.txt');
        exit;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        exit;
}
?>
