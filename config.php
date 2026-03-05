<?php
// Database Configuration (Using JSON files instead of MySQL for simplicity on Railway)
define('DATA_DIR', __DIR__ . '/data');
define('FRESH_KEYS_FILE', DATA_DIR . '/freshkeys.json');
define('REDEEMED_KEYS_FILE', DATA_DIR . '/redeemedkeys.json');
define('USERS_FILE', DATA_DIR . '/users.json');
define('ADMIN_LICENSE_FILE', DATA_DIR . '/admin_license.txt');

// Create data directory if it doesn't exist
if (!file_exists(DATA_DIR)) {
    mkdir(DATA_DIR, 0755, true);
}

// Initialize files if they don't exist
if (!file_exists(FRESH_KEYS_FILE)) {
    file_put_contents(FRESH_KEYS_FILE, json_encode([]));
}

if (!file_exists(REDEEMED_KEYS_FILE)) {
    file_put_contents(REDEEMED_KEYS_FILE, json_encode([]));
}

if (!file_exists(USERS_FILE)) {
    file_put_contents(USERS_FILE, json_encode([]));
}

// Create default admin license if not exists
if (!file_exists(ADMIN_LICENSE_FILE)) {
    // Default license key: $UBHAN8962@
    file_put_contents(ADMIN_LICENSE_FILE, '$UBHAN8962@');
}

// Time Configuration
define('REDEEM_COOLDOWN', 6 * 60 * 60); // 6 hours in seconds

// Security
define('SECRET_KEY', 'your-secret-key-change-this-' . md5(__DIR__));

// Error Reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', DATA_DIR . '/error.log');
?>
