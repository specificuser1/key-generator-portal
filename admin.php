<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    // Show login form
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['license_key'])) {
        if (validateLicense($_POST['license_key'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['license_key'] = $_POST['license_key'];
            header('Location: admin.php');
            exit;
        } else {
            $error = "Invalid license key!";
        }
    }
    
    // Display login page
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Login - Key Generator</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Poppins', sans-serif;
                background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
                min-height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                padding: 20px;
            }

            .login-container {
                background: white;
                padding: 40px;
                border-radius: 20px;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                max-width: 400px;
                width: 100%;
            }

            .login-header {
                text-align: center;
                margin-bottom: 30px;
            }

            .login-header h1 {
                color: #1e3c72;
                font-size: 2em;
                margin-bottom: 10px;
            }

            .login-header p {
                color: #666;
            }

            .form-group {
                margin-bottom: 20px;
            }

            .form-group label {
                display: block;
                margin-bottom: 8px;
                color: #333;
                font-weight: 600;
            }

            .form-group input {
                width: 100%;
                padding: 12px;
                border: 2px solid #ddd;
                border-radius: 8px;
                font-size: 1em;
                transition: border-color 0.3s;
            }

            .form-group input:focus {
                outline: none;
                border-color: #1e3c72;
            }

            .login-btn {
                width: 100%;
                padding: 14px;
                background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
                color: white;
                border: none;
                border-radius: 8px;
                font-size: 1.1em;
                font-weight: 600;
                cursor: pointer;
                transition: transform 0.3s;
            }

            .login-btn:hover {
                transform: translateY(-2px);
            }

            .error {
                background: #e74c3c;
                color: white;
                padding: 12px;
                border-radius: 8px;
                margin-bottom: 20px;
                text-align: center;
            }

            .back-link {
                text-align: center;
                margin-top: 20px;
            }

            .back-link a {
                color: #1e3c72;
                text-decoration: none;
            }
        </style>
    </head>
    <body>
        <div class="login-container">
            <div class="login-header">
                <h1>🔐 Admin Panel</h1>
                <p>Enter your license key to continue</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>License Key</label>
                    <input type="password" name="license_key" required placeholder="Enter your license key">
                </div>
                <button type="submit" class="login-btn">Login</button>
            </form>

            <div class="back-link">
                <a href="index.php">← Back to Home</a>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Admin is logged in - show dashboard
$stats = getStatistics();
$recentRedemptions = getRecentRedemptions(10);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Key Generator</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f6fa;
            min-height: 100vh;
        }

        .navbar {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 20px 40px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h1 {
            font-size: 1.8em;
        }

        .logout-btn {
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid white;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
        }

        .logout-btn:hover {
            background: white;
            color: #1e3c72;
        }

        .container {
            max-width: 1400px;
            margin: 40px auto;
            padding: 0 40px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-card h3 {
            color: #666;
            font-size: 0.9em;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .stat-card .value {
            font-size: 2.5em;
            font-weight: 700;
            color: #1e3c72;
        }

        .section {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .section-header h2 {
            color: #1e3c72;
            font-size: 1.5em;
        }

        .btn {
            padding: 12px 24px;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1em;
            transition: transform 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-success {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        }

        .btn-danger {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        }

        textarea {
            width: 100%;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 0.95em;
            resize: vertical;
            min-height: 150px;
        }

        textarea:focus {
            outline: none;
            border-color: #1e3c72;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th {
            background: #f8f9fa;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #dee2e6;
        }

        .table td {
            padding: 12px 15px;
            border-bottom: 1px solid #dee2e6;
        }

        .table tr:hover {
            background: #f8f9fa;
        }

        .key-code {
            font-family: 'Courier New', monospace;
            background: #f0f0f0;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.9em;
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
        }

        .status-fresh {
            background: #d4edda;
            color: #155724;
        }

        .status-redeemed {
            background: #fff3cd;
            color: #856404;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 20px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .navbar-content {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="navbar-content">
            <h1>🛠️ Admin Dashboard</h1>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <div class="container">
        <?php
        // Display success messages
        if (isset($_GET['success'])) {
            switch ($_GET['success']) {
                case 'keys_added':
                    $added = $_GET['added'] ?? 0;
                    $duplicates = $_GET['duplicates'] ?? 0;
                    echo '<div class="success-message">✓ Successfully added ' . $added . ' keys';
                    if ($duplicates > 0) echo ' (' . $duplicates . ' duplicates skipped)';
                    echo '</div>';
                    break;
                case 'license_updated':
                    echo '<div class="success-message">✓ Admin license updated successfully!</div>';
                    break;
            }
        }
        
        // Display error messages
        if (isset($_GET['error'])) {
            switch ($_GET['error']) {
                case 'no_keys':
                    echo '<div class="error-message">✗ No valid keys provided</div>';
                    break;
                case 'license_empty':
                    echo '<div class="error-message">✗ License key cannot be empty</div>';
                    break;
                case 'license_mismatch':
                    echo '<div class="error-message">✗ License keys do not match</div>';
                    break;
                case 'license_short':
                    echo '<div class="error-message">✗ License key must be at least 6 characters</div>';
                    break;
            }
        }
        ?>
        
        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Keys</h3>
                <div class="value"><?php echo $stats['total']; ?></div>
            </div>
            <div class="stat-card">
                <h3>Available Keys</h3>
                <div class="value"><?php echo $stats['available']; ?></div>
            </div>
            <div class="stat-card">
                <h3>Redeemed Keys</h3>
                <div class="value"><?php echo $stats['redeemed']; ?></div>
            </div>
            <div class="stat-card">
                <h3>Redemption Rate</h3>
                <div class="value"><?php echo $stats['total'] > 0 ? round(($stats['redeemed'] / $stats['total']) * 100) : 0; ?>%</div>
            </div>
        </div>

        <!-- Add Keys Section -->
        <div class="section">
            <div class="section-header">
                <h2>📝 Add New Keys</h2>
            </div>
            
            <form method="POST" action="api.php?action=addKeys">
                <div class="form-group">
                    <label>Enter Keys (one per line or comma-separated)</label>
                    <textarea name="keys" placeholder="KEY-XXXX-XXXX-XXXX&#10;KEY-YYYY-YYYY-YYYY&#10;KEY-ZZZZ-ZZZZ-ZZZZ"></textarea>
                </div>
                <div class="action-buttons">
                    <button type="submit" class="btn btn-success">Add Keys</button>
                    <button type="button" class="btn" onclick="generateBulkKeys()">Generate 10 Random Keys</button>
                </div>
            </form>
        </div>

        <!-- Change License Section -->
        <div class="section">
            <div class="section-header">
                <h2>🔐 Change Admin License</h2>
            </div>
            
            <form method="POST" action="api.php?action=changeLicense">
                <div class="form-group">
                    <label>Current License Key</label>
                    <input type="text" value="<?php echo str_repeat('*', strlen(trim(file_get_contents(ADMIN_LICENSE_FILE)))); ?>" disabled style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 8px; background: #f5f5f5;">
                </div>
                <div class="form-group">
                    <label>New License Key</label>
                    <input type="text" name="new_license" required placeholder="Enter new license key" style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 8px; font-size: 1em;">
                </div>
                <div class="form-group">
                    <label>Confirm New License Key</label>
                    <input type="text" name="confirm_license" required placeholder="Confirm new license key" style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 8px; font-size: 1em;">
                </div>
                <button type="submit" class="btn btn-success">Update License</button>
            </form>
        </div>

        <!-- Recent Redemptions -->
        <div class="section">
            <div class="section-header">
                <h2>🔄 Recent Redemptions</h2>
                <a href="api.php?action=exportRedeemed" class="btn">Export Redeemed</a>
            </div>
            
            <table class="table">
                <thead>
                    <tr>
                        <th>Key</th>
                        <th>User ID</th>
                        <th>Redeemed At</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentRedemptions)): ?>
                        <tr>
                            <td colspan="4" style="text-align: center; color: #999;">No redemptions yet</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentRedemptions as $redemption): ?>
                            <tr>
                                <td><span class="key-code"><?php echo htmlspecialchars($redemption['key']); ?></span></td>
                                <td><?php echo htmlspecialchars(substr($redemption['user_id'], 0, 16)); ?>...</td>
                                <td><?php echo date('M d, Y H:i:s', $redemption['redeemed_at']); ?></td>
                                <td><?php echo htmlspecialchars($redemption['ip_address']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- All Keys Management -->
        <div class="section">
            <div class="section-header">
                <h2>🗂️ All Keys</h2>
                <div>
                    <a href="api.php?action=exportFresh" class="btn btn-success" style="margin-right: 10px;">Export Fresh Keys</a>
                    <button class="btn btn-danger" onclick="clearAllKeys()">Clear All Keys</button>
                </div>
            </div>
            
            <table class="table">
                <thead>
                    <tr>
                        <th>Key</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="keysTable">
                    <!-- Keys will be loaded via AJAX -->
                    <tr>
                        <td colspan="4" style="text-align: center;">Loading keys...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function generateBulkKeys() {
            let keys = [];
            for (let i = 0; i < 10; i++) {
                keys.push(generateRandomKey());
            }
            document.querySelector('textarea[name="keys"]').value = keys.join('\n');
        }

        function generateRandomKey() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let key = 'KEY-';
            for (let i = 0; i < 3; i++) {
                for (let j = 0; j < 4; j++) {
                    key += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                if (i < 2) key += '-';
            }
            return key;
        }

        async function loadAllKeys() {
            try {
                const response = await fetch('api.php?action=getAllKeys');
                const data = await response.json();
                
                const tbody = document.getElementById('keysTable');
                if (data.keys && data.keys.length > 0) {
                    tbody.innerHTML = data.keys.map(key => `
                        <tr>
                            <td><span class="key-code">${key.key}</span></td>
                            <td><span class="status-badge status-${key.status}">${key.status.toUpperCase()}</span></td>
                            <td>${new Date(key.created_at * 1000).toLocaleString()}</td>
                            <td>
                                <button class="btn btn-danger" style="padding: 6px 12px; font-size: 0.85em;" onclick="deleteKey('${key.key}')">Delete</button>
                            </td>
                        </tr>
                    `).join('');
                } else {
                    tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; color: #999;">No keys found</td></tr>';
                }
            } catch (error) {
                console.error('Error loading keys:', error);
            }
        }

        async function deleteKey(key) {
            if (!confirm('Are you sure you want to delete this key?')) return;
            
            try {
                const response = await fetch('api.php?action=deleteKey', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ key: key })
                });
                
                const data = await response.json();
                if (data.success) {
                    loadAllKeys();
                } else {
                    alert('Error deleting key');
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

        async function clearAllKeys() {
            if (!confirm('Are you sure you want to delete ALL keys? This action cannot be undone!')) return;
            
            try {
                const response = await fetch('api.php?action=clearAllKeys', {
                    method: 'POST'
                });
                
                const data = await response.json();
                if (data.success) {
                    alert('All keys cleared successfully!');
                    location.reload();
                } else {
                    alert('Error clearing keys');
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

        // Load keys on page load
        loadAllKeys();
    </script>
</body>
</html>
