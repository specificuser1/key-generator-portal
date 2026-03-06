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
    <title>KEY GENERATOR PORTAL</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700;900&family=Rajdhani:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Rajdhani', sans-serif;
            background: #000000;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* Animated Background */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a0000 50%, #0a0a0a 100%);
            z-index: 0;
        }

        /* Floating Particles */
        .particle {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            opacity: 0;
            animation: float 15s infinite;
        }

        @keyframes float {
            0% {
                transform: translateY(100vh) translateX(0) scale(0);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh) translateX(100px) scale(1);
                opacity: 0;
            }
        }

        /* Scanline Effect */
        .scanline {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: rgba(255, 0, 0, 0.3);
            animation: scan 4s linear infinite;
            z-index: 1;
            pointer-events: none;
        }

        @keyframes scan {
            0% { transform: translateY(0); }
            100% { transform: translateY(100vh); }
        }


        /* Top Key Display Bar */
        .key-bar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: linear-gradient(135deg, #ff0000 0%, #8b0000 100%);
            padding: 15px 20px;
            box-shadow: 0 5px 30px rgba(255, 0, 0, 0.5);
            z-index: 1000;
            display: none;
            animation: slideDown 0.5s ease;
            border-bottom: 2px solid #ff0000;
        }

        .key-bar.active {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .key-bar-content {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
            flex: 1;
        }

        .key-bar-label {
            color: #fff;
            font-size: 0.9em;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .key-bar-value {
            background: rgba(0, 0, 0, 0.4);
            padding: 10px 20px;
            border-radius: 8px;
            color: #00ff00;
            font-family: 'Orbitron', monospace;
            font-size: 1.1em;
            font-weight: 700;
            letter-spacing: 2px;
            border: 1px solid rgba(255, 0, 0, 0.3);
            flex: 1;
            min-width: 250px;
        }

        .key-bar-copy {
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: #fff;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .key-bar-copy:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.5);
            transform: scale(1.05);
        }

        .key-bar-timer {
            color: #fff;
            font-size: 0.95em;
            font-weight: 600;
            padding: 10px 15px;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 8px;
            border: 1px solid rgba(255, 0, 0, 0.3);
        }

        /* Main Container */
        .container {
            position: relative;
            z-index: 10;
            max-width: 1200px;
            width: 100%;
            margin: 80px auto 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 50px;
            padding-top: 30px;
        }

        .header h1 {
            font-family: 'Orbitron', sans-serif;
            font-size: 4em;
            font-weight: 900;
            background: linear-gradient(135deg, #ff0000 0%, #ff6b6b 50%, #ff0000 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 0 30px rgba(255, 0, 0, 0.5);
            letter-spacing: 5px;
            margin-bottom: 15px;
            animation: glow 2s ease-in-out infinite;
        }

        @keyframes glow {
            0%, 100% {
                text-shadow: 0 0 20px rgba(255, 0, 0, 0.5),
                             0 0 40px rgba(255, 0, 0, 0.3);
            }
            50% {
                text-shadow: 0 0 30px rgba(255, 0, 0, 0.8),
                             0 0 60px rgba(255, 0, 0, 0.5);
            }
        }

        .header .subtitle {
            color: #fff;
            font-size: 1.3em;
            font-weight: 300;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .card {
            background: rgba(20, 0, 0, 0.8);
            border: 2px solid rgba(255, 0, 0, 0.3);
            border-radius: 20px;
            padding: 50px;
            box-shadow: 0 20px 60px rgba(255, 0, 0, 0.3),
                        inset 0 0 50px rgba(255, 0, 0, 0.05);
            backdrop-filter: blur(10px);
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-box {
            background: linear-gradient(135deg, rgba(255, 0, 0, 0.2) 0%, rgba(139, 0, 0, 0.2) 100%);
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            border: 2px solid rgba(255, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .stat-box::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 0, 0, 0.1) 0%, transparent 70%);
            animation: rotate 10s linear infinite;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .stat-box:hover {
            border-color: rgba(255, 0, 0, 0.6);
            box-shadow: 0 10px 30px rgba(255, 0, 0, 0.4);
            transform: translateY(-5px);
        }

        .stat-box h3 {
            position: relative;
            z-index: 1;
            font-size: 3em;
            color: #ff0000;
            margin-bottom: 10px;
            font-family: 'Orbitron', monospace;
            font-weight: 900;
            text-shadow: 0 0 20px rgba(255, 0, 0, 0.5);
        }

        .stat-box p {
            position: relative;
            z-index: 1;
            color: #fff;
            font-size: 1.1em;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .get-key-btn {
            width: 100%;
            padding: 25px;
            background: linear-gradient(135deg, #ff0000 0%, #cc0000 100%);
            color: #fff;
            border: 2px solid rgba(255, 0, 0, 0.5);
            border-radius: 15px;
            font-size: 1.5em;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(255, 0, 0, 0.4);
            font-family: 'Orbitron', sans-serif;
            text-transform: uppercase;
            letter-spacing: 3px;
            position: relative;
            overflow: hidden;
        }

        .get-key-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .get-key-btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .get-key-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(255, 0, 0, 0.6);
            border-color: rgba(255, 0, 0, 0.8);
        }

        .get-key-btn:disabled {
            background: linear-gradient(135deg, #333 0%, #222 100%);
            cursor: not-allowed;
            transform: none;
            border-color: rgba(255, 255, 255, 0.2);
        }

        .get-key-btn span {
            position: relative;
            z-index: 1;
        }

        .timer-info {
            text-align: center;
            margin-top: 20px;
            color: #ff6b6b;
            font-size: 1.1em;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .footer {
            text-align: center;
            margin-top: 50px;
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.9em;
            letter-spacing: 1px;
        }

        .footer a {
            color: #ff0000;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .footer a:hover {
            color: #ff6b6b;
            text-shadow: 0 0 10px rgba(255, 0, 0, 0.5);
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 2.5em;
                letter-spacing: 3px;
            }

            .card {
                padding: 30px 20px;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .key-bar-content {
                flex-direction: column;
                gap: 10px;
            }

            .key-bar-value {
                min-width: auto;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Background Animation -->
    <div class="bg-animation"></div>
    <div class="scanline"></div>

    <!-- Top Key Display Bar -->
    <div class="key-bar" id="keyBar">
        <div class="key-bar-content">
            <span class="key-bar-label">Your Key:</span>
            <div class="key-bar-value" id="keyBarValue">XXXX-XXXX-XXXX-XXXX</div>
            <button class="key-bar-copy" onclick="copyKeyFromBar()">
                <span id="copyIcon">🔑</span>
                <span id="copyText">Copy</span>
            </button>
            <div class="key-bar-timer" id="keyBarTimer">Next key in: 6h 0m 0s</div>
        </div>
    </div>

    <div class="container">
        <div class="header">
            <h1>KEY GENERATOR PORTAL</h1>
            <p class="subtitle">PROGRAMMED BY SUBHAN</p>
        </div>

        <div class="card">
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
                <span>👉 GET YOUR KEY 👈</span>
            </button>

            <div class="timer-info" id="timerInfo"></div>
        </div>

        <div class="footer">
            <p>KEY GENERATOR PORTAL © 2018-2026| <a href="admin.php">Admin Access</a></p>
        </div>
    </div>

    <script>
        // Create floating particles
        function createParticles() {
            const colors = ['#ff0000', '#ff3333', '#ff6666', '#cc0000'];
            for (let i = 0; i < 30; i++) {
                setTimeout(() => {
                    const particle = document.createElement('div');
                    particle.className = 'particle';
                    particle.style.left = Math.random() * 100 + '%';
                    particle.style.width = (Math.random() * 4 + 2) + 'px';
                    particle.style.height = particle.style.width;
                    particle.style.background = colors[Math.floor(Math.random() * colors.length)];
                    particle.style.boxShadow = `0 0 ${Math.random() * 20 + 10}px ${colors[Math.floor(Math.random() * colors.length)]}`;
                    particle.style.animationDelay = Math.random() * 15 + 's';
                    particle.style.animationDuration = (Math.random() * 10 + 10) + 's';
                    document.body.appendChild(particle);
                }, i * 200);
            }
        }

        createParticles();
        setInterval(createParticles, 30000);

        // Check if user can redeem
        function checkRedeemStatus() {
            const lastRedeem = localStorage.getItem('lastRedeemTime');
            const currentKey = localStorage.getItem('currentKey');
            const userId = getUserId();
            
            if (lastRedeem && currentKey) {
                const timePassed = Date.now() - parseInt(lastRedeem);
                const sixHours = 6 * 60 * 60 * 1000;
                
                if (timePassed < sixHours) {
                    const remaining = sixHours - timePassed;
                    showKeyBar(currentKey, remaining);
                    updateTimer(remaining);
                    document.getElementById('getKeyBtn').disabled = true;
                    return false;
                }
            }
            return true;
        }

        // Show key bar
        function showKeyBar(key, remaining) {
            const keyBar = document.getElementById('keyBar');
            const keyBarValue = document.getElementById('keyBarValue');
            
            keyBarValue.textContent = key;
            keyBar.classList.add('active');
            
            updateKeyBarTimer(remaining);
        }

        // Hide key bar
        function hideKeyBar() {
            const keyBar = document.getElementById('keyBar');
            keyBar.classList.remove('active');
        }

        // Update key bar timer
        function updateKeyBarTimer(remaining) {
            const hours = Math.floor(remaining / (60 * 60 * 1000));
            const minutes = Math.floor((remaining % (60 * 60 * 1000)) / (60 * 1000));
            const seconds = Math.floor((remaining % (60 * 1000)) / 1000);
            
            const timerElement = document.getElementById('keyBarTimer');
            if (timerElement) {
                timerElement.textContent = `⏰ Next key in: ${hours}h ${minutes}m ${seconds}s`;
            }
            
            if (remaining > 0) {
                setTimeout(() => updateKeyBarTimer(remaining - 1000), 1000);
            } else {
                hideKeyBar();
                localStorage.removeItem('currentKey');
                localStorage.removeItem('lastRedeemTime');
                document.getElementById('getKeyBtn').disabled = false;
                document.getElementById('timerInfo').innerHTML = '';
                location.reload();
            }
        }

        // Generate unique user ID
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
            ctx.fillText('warrior', 2, 2);
            
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
                    const redeemTime = Date.now();
                    const sixHours = 6 * 60 * 60 * 1000;
                    
                    localStorage.setItem('lastRedeemTime', redeemTime.toString());
                    localStorage.setItem('currentKey', data.key);
                    
                    showKeyBar(data.key, sixHours);
                    
                    document.getElementById('getKeyBtn').disabled = true;
                    updateTimer(sixHours);
                    
                    // Refresh stats
                    setTimeout(() => location.reload(), 500);
                } else {
                    alert(data.message || 'Error getting key. Please try again.');
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

        function copyKeyFromBar() {
            const keyValue = document.getElementById('keyBarValue').textContent;
            navigator.clipboard.writeText(keyValue).then(() => {
                const copyIcon = document.getElementById('copyIcon');
                const copyText = document.getElementById('copyText');
                const originalIcon = copyIcon.textContent;
                const originalText = copyText.textContent;
                
                copyIcon.textContent = '✓';
                copyText.textContent = 'Copied!';
                
                setTimeout(() => {
                    copyIcon.textConten
