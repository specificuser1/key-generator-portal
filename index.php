<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

// Get statistics
$stats = getStatistics();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Key Generator Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            width: 100%;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #667eea;
            font-size: 2.5em;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .header p {
            color: #666;
            font-size: 1.1em;
        }

        .stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            color: white;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .stat-box h3 {
            font-size: 2em;
            margin-bottom: 5px;
            font-weight: 700;
        }

        .stat-box p {
            font-size: 0.9em;
            opacity: 0.9;
        }

        .get-key-btn {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.3em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .get-key-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.6);
        }

        .get-key-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        .timer-info {
            text-align: center;
            margin-top: 15px;
            color: #666;
            font-size: 0.95em;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background: white;
            margin: 10% auto;
            padding: 40px;
            border-radius: 20px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            text-align: center;
            margin-bottom: 25px;
        }

        .modal-header h2 {
            color: #667eea;
            font-size: 1.8em;
            margin-bottom: 10px;
        }

        .key-display {
            background: #f5f5f5;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 20px;
            border: 2px dashed #667eea;
        }

        .key-value {
            font-size: 1.4em;
            font-weight: 600;
            color: #333;
            letter-spacing: 2px;
            word-break: break-all;
        }

        .copy-btn {
            width: 100%;
            padding: 12px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            cursor: pointer;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .copy-btn:hover {
            background: #5568d3;
        }

        .next-redeem {
            text-align: center;
            color: #666;
            margin-bottom: 20px;
            font-size: 0.95em;
        }

        .close-btn {
            width: 100%;
            padding: 12px;
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .close-btn:hover {
            background: #c0392b;
        }

        .success-icon {
            font-size: 4em;
            color: #27ae60;
            margin-bottom: 15px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            color: white;
            font-size: 0.9em;
        }

        @media (max-width: 600px) {
            .card {
                padding: 25px;
            }

            .header h1 {
                font-size: 2em;
            }

            .stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <h1>🔑 Key Generator</h1>
                <p>Get your premium key instantly</p>
            </div>

            <div class="stats">
                <div class="stat-box">
                    <h3><?php echo $stats['available']; ?></h3>
                    <p>Keys Available</p>
                </div>
                <div class="stat-box">
                    <h3><?php echo $stats['redeemed']; ?></h3>
                    <p>Keys Redeemed</p>
                </div>
            </div>

            <button class="get-key-btn" id="getKeyBtn" onclick="getKey()">
                Get Your Key
            </button>

            <div class="timer-info" id="timerInfo"></div>
        </div>

        <div class="footer">
            <p>Powered by Advanced Key System | <a href="admin.php" style="color: white; text-decoration: none;">Admin Panel</a></p>
        </div>
    </div>

    <!-- Modal -->
    <div id="keyModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="success-icon">✓</div>
                <h2>Your Key is Ready!</h2>
            </div>

            <div class="key-display">
                <div class="key-value" id="keyValue"></div>
            </div>

            <button class="copy-btn" onclick="copyKey()">📋 Copy Key</button>

            <div class="next-redeem" id="nextRedeemTime"></div>

            <button class="close-btn" onclick="closeModal()">Close</button>
        </div>
    </div>

    <script>
        // Check if user can redeem
        function checkRedeemStatus() {
            const lastRedeem = localStorage.getItem('lastRedeemTime');
            const userId = getUserId();
            
            if (lastRedeem) {
                const timePassed = Date.now() - parseInt(lastRedeem);
                const sixHours = 6 * 60 * 60 * 1000;
                
                if (timePassed < sixHours) {
                    const remaining = sixHours - timePassed;
                    updateTimer(remaining);
                    document.getElementById('getKeyBtn').disabled = true;
                    return false;
                }
            }
            return true;
        }

        // Generate unique user ID based on browser fingerprint
        function getUserId() {
            let userId = localStorage.getItem('userId');
            if (!userId) {
                userId = generateFingerprint();
                localStorage.setItem('userId', userId);
            }
            return userId;
        }

        function generateFingerprint() {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            ctx.textBaseline = 'top';
            ctx.font = '14px Arial';
            ctx.fillText('fingerprint', 2, 2);
            
            const fingerprint = canvas.toDataURL() + 
                               navigator.userAgent + 
                               navigator.language + 
                               screen.colorDepth + 
                               screen.width + 
                               screen.height;
            
            return btoa(fingerprint).substring(0, 32);
        }

        function updateTimer(remaining) {
            const hours = Math.floor(remaining / (60 * 60 * 1000));
            const minutes = Math.floor((remaining % (60 * 60 * 1000)) / (60 * 1000));
            const seconds = Math.floor((remaining % (60 * 1000)) / 1000);
            
            document.getElementById('timerInfo').innerHTML = 
                `⏰ Next key available in: <strong>${hours}h ${minutes}m ${seconds}s</strong>`;
            
            if (remaining > 0) {
                setTimeout(() => updateTimer(remaining - 1000), 1000);
            } else {
                document.getElementById('getKeyBtn').disabled = false;
                document.getElementById('timerInfo').innerHTML = '';
                location.reload();
            }
        }

        async function getKey() {
            const userId = getUserId();
            
            try {
                const response = await fetch('api.php?action=getKey', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ userId: userId })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    localStorage.setItem('lastRedeemTime', Date.now().toString());
                    showModal(data.key, data.nextRedeem);
                    location.reload(); // Refresh stats
                } else {
                    alert(data.message || 'Error getting key. Please try again.');
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

        function showModal(key, nextRedeem) {
            document.getElementById('keyValue').textContent = key;
            document.getElementById('nextRedeemTime').innerHTML = 
                `⏰ Next redemption available: <strong>${nextRedeem}</strong>`;
            document.getElementById('keyModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('keyModal').style.display = 'none';
        }

        function copyKey() {
            const keyValue = document.getElementById('keyValue').textContent;
            navigator.clipboard.writeText(keyValue).then(() => {
                const btn = event.target;
                const originalText = btn.textContent;
                btn.textContent = '✓ Copied!';
                setTimeout(() => {
                    btn.textContent = originalText;
                }, 2000);
            });
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('keyModal');
            if (event.target == modal) {
                closeModal();
            }
        }

        // Check status on page load
        checkRedeemStatus();
    </script>
</body>
</html>
