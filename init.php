<?php
/**
 * Initialization Script
 * Run this once to set up the key generator system
 */

echo "🚀 Key Generator Portal - Initialization\n\n";

// Create data directory
$dataDir = __DIR__ . '/data';
if (!file_exists($dataDir)) {
    mkdir($dataDir, 0755, true);
    echo "✓ Created data directory\n";
} else {
    echo "✓ Data directory exists\n";
}

// Initialize fresh keys file
$freshKeysFile = $dataDir . '/freshkeys.json';
if (!file_exists($freshKeysFile)) {
    file_put_contents($freshKeysFile, json_encode([], JSON_PRETTY_PRINT));
    echo "✓ Created freshkeys.json\n";
} else {
    echo "✓ freshkeys.json exists\n";
}

// Initialize redeemed keys file
$redeemedKeysFile = $dataDir . '/redeemedkeys.json';
if (!file_exists($redeemedKeysFile)) {
    file_put_contents($redeemedKeysFile, json_encode([], JSON_PRETTY_PRINT));
    echo "✓ Created redeemedkeys.json\n";
} else {
    echo "✓ redeemedkeys.json exists\n";
}

// Initialize users file
$usersFile = $dataDir . '/users.json';
if (!file_exists($usersFile)) {
    file_put_contents($usersFile, json_encode([], JSON_PRETTY_PRINT));
    echo "✓ Created users.json\n";
} else {
    echo "✓ users.json exists\n";
}

// Create admin license file
$adminLicenseFile = $dataDir . '/admin_license.txt';
if (!file_exists($adminLicenseFile)) {
    $defaultLicense = 'ADMIN-2024-SUPER-SECRET';
    file_put_contents($adminLicenseFile, $defaultLicense);
    echo "✓ Created admin_license.txt\n";
    echo "  → Default license: $defaultLicense\n";
} else {
    $currentLicense = trim(file_get_contents($adminLicenseFile));
    echo "✓ admin_license.txt exists\n";
    echo "  → Current license: $currentLicense\n";
}

// Add sample keys
$sampleKeys = [];
for ($i = 1; $i <= 10; $i++) {
    $key = sprintf("SAMPLE-%04d-%04d-%04d", rand(1000, 9999), rand(1000, 9999), rand(1000, 9999));
    $sampleKeys[] = [
        'key' => $key,
        'created_at' => time(),
        'created_by' => 'initialization'
    ];
}

$currentKeys = json_decode(file_get_contents($freshKeysFile), true);
if (empty($currentKeys)) {
    file_put_contents($freshKeysFile, json_encode($sampleKeys, JSON_PRETTY_PRINT));
    echo "✓ Added 10 sample keys\n";
} else {
    echo "✓ Keys already exist (skipped sample keys)\n";
}

// Set permissions
chmod($dataDir, 0755);
chmod($freshKeysFile, 0644);
chmod($redeemedKeysFile, 0644);
chmod($usersFile, 0644);
chmod($adminLicenseFile, 0644);
echo "✓ Set proper file permissions\n";

echo "\n✅ Initialization complete!\n\n";
echo "📝 Next Steps:\n";
echo "1. Visit your site at: http://localhost:8000/ (or your domain)\n";
echo "2. Admin panel at: http://localhost:8000/admin.php\n";
echo "3. Login with license key: " . trim(file_get_contents($adminLicenseFile)) . "\n";
echo "4. Change license key in: data/admin_license.txt\n";
echo "\n🎉 Ready to use!\n";
?>
