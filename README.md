🔑 Advanced Key Generator Portal
A professional key management system with admin panel, user tracking, and automatic cooldown system.
✨ Features
Public Features:
🎨 Modern, gradient UI design
⏰ 6-hour redemption cooldown per user
🔒 Browser fingerprint tracking (works across page refresh, new tabs, and even different browsers on same device)
📊 Real-time key statistics
📱 Fully responsive design
🎯 One-click key redemption
📋 Copy-to-clipboard functionality
Admin Features:
🔐 License-based authentication
📝 Bulk key addition (comma or newline separated)
🎲 Random key generator
📊 Complete statistics dashboard
📈 Real-time key management
🗂️ Export fresh/redeemed keys
👥 User tracking with IP addresses
🔄 Recent redemptions log
❌ Individual key deletion
🗑️ Bulk key clearing
📁 File Structure
key-generator-portal/
├── index.php              # Public key redemption page
├── admin.php             # Admin dashboard (license protected)
├── api.php               # API endpoints for AJAX requests
├── config.php            # Configuration settings
├── functions.php         # Core business logic
├── logout.php            # Admin logout
├── .htaccess            # Apache configuration
├── data/                # Data storage directory
│   ├── freshkeys.json   # Available keys
│   ├── redeemedkeys.json # Redeemed keys with user data
│   ├── users.json       # User tracking data
│   └── admin_license.txt # Admin license key
└── README.md            # This file
🚀 Deployment on Railway
Method 1: Using GitHub
Create GitHub Repository
Create a new repository on GitHub
Upload all project files to the repository
Deploy on Railway
Go to Railway.app
Click "New Project"
Select "Deploy from GitHub repo"
Choose your repository
Railway will auto-detect it's a PHP project
Configure Environment
Railway will automatically set up PHP environment
No additional environment variables needed
Access Your Site
Railway will provide a public URL like: your-project.up.railway.app
Public portal: https://your-project.up.railway.app/
Admin panel: https://your-project.up.railway.app/admin.php
Method 2: Using Railway CLI
# Install Railway CLI
npm i -g @railway/cli

# Login to Railway
railway login

# Initialize project
railway init

# Deploy
railway up
Important Railway Configuration
Create a railway.json in your project root:
{
  "build": {
    "builder": "NIXPACKS"
  },
  "deploy": {
    "startCommand": "php -S 0.0.0.0:$PORT -t .",
    "restartPolicyType": "ON_FAILURE",
    "restartPolicyMaxRetries": 10
  }
}
🔧 Local Development Setup
Prerequisites:
PHP 7.4 or higher
Apache/Nginx web server
Or use PHP built-in server
Quick Start:
Clone/Download the project
git clone your-repo-url
cd key-generator-portal
Set permissions
chmod 755 data/
chmod 644 data/*.json
chmod 644 data/*.txt
Start PHP server
php -S localhost:8000
Access the portal
Public: http://localhost:8000/
Admin: http://localhost:8000/admin.php
🔐 Admin Access
Default License Key:
ADMIN-2024-SUPER-SECRET
Change License Key:
Edit data/admin_license.txt and put your custom license key:
YOUR-CUSTOM-LICENSE-KEY-HERE
📝 Usage Guide
For Public Users:
Visit the main page
Click "Get Your Key" button
Copy the generated key from popup
Wait 6 hours before next redemption
For Admins:
Go to /admin.php
Enter license key
Access admin dashboard
Adding Keys:
Method 1: Manual Entry
Enter keys in textarea (one per line or comma-separated)
Click "Add Keys"
Method 2: Generate Random
Click "Generate 10 Random Keys"
Review generated keys
Click "Add Keys"
Example keys format:
KEY-ABCD-1234-WXYZ
KEY-EFGH-5678-STUV
KEY-IJKL-9012-MNOP
Managing Keys:
View All Keys: Scroll to bottom section
Delete Key: Click delete button next to any key
Export Fresh Keys: Download all available keys
Export Redeemed: Download all used keys with user data
Clear All: Remove all keys (use with caution!)
🔒 Security Features
User Tracking
Browser fingerprinting
IP address logging
User agent tracking
Persistent across sessions
Admin Protection
License-based authentication
Session management
Secure logout
Data Protection
.htaccess protection for data files
JSON file-based storage
No direct file access
Rate Limiting
6-hour cooldown per user
Cannot bypass with refresh/new browser
🎨 Customization
Change Colors:
Edit the gradient in CSS files:
Public Portal (index.php):
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
Admin Panel (admin.php):
background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
Change Cooldown Time:
Edit config.php:
define('REDEEM_COOLDOWN', 6 * 60 * 60); // Change 6 to desired hours
Modify Key Format:
Edit functions.php in generateRandomKey() function to change key structure.
📊 Data Storage
All data is stored in JSON files in the data/ directory:
freshkeys.json
[
  {
    "key": "KEY-ABCD-1234-WXYZ",
    "created_at": 1234567890,
    "created_by": "admin"
  }
]
redeemedkeys.json
[
  {
    "key": "KEY-ABCD-1234-WXYZ",
    "user_id": "unique-browser-fingerprint",
    "redeemed_at": 1234567890,
    "ip_address": "192.168.1.1",
    "user_agent": "Mozilla/5.0..."
  }
]
users.json
{
  "user-fingerprint-id": {
    "last_redeem": 1234567890,
    "total_redeems": 5,
    "last_ip": "192.168.1.1"
  }
}
🐛 Troubleshooting
Keys not showing?
Check data/ directory permissions
Ensure JSON files are writable
Check error logs in data/error.log
Can't login to admin?
Verify license key in data/admin_license.txt
Check if sessions are enabled in PHP
Cooldown not working?
Check browser's localStorage
Clear browser cache
Verify time is set correctly on server
Railway deployment issues?
Ensure all files are uploaded
Check Railway build logs
Verify railway.json exists
📱 Browser Support
✅ Chrome/Edge (Latest)
✅ Firefox (Latest)
✅ Safari (Latest)
✅ Opera (Latest)
✅ Mobile browsers
🔄 Updates & Maintenance
Backup Data:
# Backup all data
cp -r data/ data_backup_$(date +%Y%m%d)/

# Backup specific files
cp data/freshkeys.json freshkeys_backup.json
Reset System:
# Clear all keys and users
rm data/*.json
# Restart will recreate empty files
📄 License
This project is provided as-is for personal and commercial use.
🆘 Support
For issues or questions:
Check this README
Review the code comments
Check Railway deployment logs
🎯 Key Features Summary
✅ 6-hour cooldown system
✅ Browser fingerprint tracking
✅ Admin license protection
✅ Bulk key management
✅ Export functionality
✅ Real-time statistics
✅ User tracking
✅ IP logging
✅ Modern UI/UX
✅ Mobile responsive
✅ Railway ready
✅ No database needed
Made with ❤️ for secure key distribution
