# TaskMasters - Complete Setup Guide for Beginners

## 📋 Prerequisites Checklist

Before starting, make sure you have:
- [ ] Windows PC (Windows 10 or 11)
- [ ] At least 2GB free disk space
- [ ] Internet connection
- [ ] Administrator access on your PC

---

## 🚀 Step-by-Step Installation

### Step 1: Install XAMPP

1. **Download XAMPP**
   - Go to: https://www.apachefriends.org/download.html
   - Click on "XAMPP for Windows"
   - Download the latest version (PHP 8.x)

2. **Install XAMPP**
   - Run the downloaded installer
   - If Windows asks "Do you want to allow this app?", click **Yes**
   - Click **Next** through all screens
   - Install to default location: `C:\xampp`
   - Uncheck "Learn more about Bitnami" at the end
   - Click **Finish**

3. **Start XAMPP**
   - Open "XAMPP Control Panel" from Start Menu
   - Click **Start** next to "Apache"
   - Click **Start** next to "MySQL"
   - Both should show green "Running" status

✅ **Checkpoint**: Apache and MySQL should be running (green background)

---

### Step 2: Verify Installation

1. **Test Apache**
   - Open your web browser
   - Go to: `http://localhost`
   - You should see XAMPP welcome page

2. **Test phpMyAdmin**
   - Go to: `http://localhost/phpmyadmin`
   - You should see phpMyAdmin interface

✅ **Checkpoint**: Both pages should load without errors

---

### Step 3: Create Database

1. **Open phpMyAdmin**
   - Browser: `http://localhost/phpmyadmin`

2. **Create New Database**
   - Click "New" in left sidebar
   - Database name: `taskmasters`
   - Collation: `utf8mb4_general_ci`
   - Click **Create**

3. **Import Database Schema**
   - Click on `taskmasters` database (left sidebar)
   - Click "Import" tab at top
   - Click "Choose File"
   - Navigate to: `C:\xampp\htdocs\Taskmasters\config\setup.sql`
   - Click **Go** at bottom
   - Wait for "Import has been successfully finished"

✅ **Checkpoint**: You should see 3 tables: users, orders, services

---

### Step 4: Verify Project Files

1. **Check Project Location**
   - Open File Explorer
   - Navigate to: `C:\xampp\htdocs\Taskmasters`
   - You should see all PHP files

2. **Verify Folder Structure**
   ```
   Taskmasters/
   ├── admin/
   ├── config/
   ├── uploads/
   ├── index.php
   ├── login.php
   └── ... (other files)
   ```

✅ **Checkpoint**: All files and folders are present

---

### Step 5: Access the Website

1. **Open Website**
   - Browser: `http://localhost/Taskmasters/`
   - You should see the TaskMasters landing page

2. **Test Registration**
   - Click "Get Started" or "Register"
   - Fill in the form:
     - Name: Your Name
     - Email: test@example.com
     - Phone: 1234567890
     - Password: password123
   - Click **Register**
   - You should be redirected to login page

3. **Test Login**
   - Email: test@example.com
   - Password: password123
   - Click **Sign In**
   - You should see the dashboard

✅ **Checkpoint**: You can register and login successfully

---

### Step 6: Create Admin Account

1. **Open phpMyAdmin**
   - Browser: `http://localhost/phpmyadmin`

2. **Edit User**
   - Click `taskmasters` database
   - Click `users` table
   - Click "Browse" tab
   - Find your user (test@example.com)
   - Click "Edit" (pencil icon)
   - Change `is_admin` from `0` to `1`
   - Click **Go**

3. **Test Admin Access**
   - Logout from website
   - Login again with same credentials
   - You should be redirected to Admin Dashboard

✅ **Checkpoint**: Admin panel is accessible

---

## 🎯 Quick Start Guide

### For Users

1. **Browse Services**
   - Go to: `http://localhost/Taskmasters/`
   - Click on any service category

2. **Place an Order**
   - Login/Register
   - Select a service
   - Fill order form
   - Upload files (optional)
   - Proceed to checkout

3. **Make Payment**
   - Scan QR code with UPI app
   - Upload payment screenshot
   - Click "Confirm Payment"

4. **Track Order**
   - Go to Dashboard
   - View order status
   - Download delivered files

### For Admin

1. **Access Admin Panel**
   - Login with admin account
   - Auto-redirect to: `http://localhost/Taskmasters/admin/dashboard.php`

2. **Manage Orders**
   - View all orders in table
   - Click "View" to see details

3. **Process Order**
   - Verify payment screenshot
   - Click "Verify Payment"
   - Update status to "In Progress"
   - Upload completed file
   - Status auto-changes to "Delivered"

---

## 🛠️ VS Code Setup (Optional)

### Install VS Code

1. Download: https://code.visualstudio.com/
2. Install with default settings
3. Open VS Code

### Install Extensions

1. Click Extensions icon (left sidebar)
2. Search and install:
   - **PHP Intelephense** (by Ben Mewburn)
   - **Tailwind CSS IntelliSense** (by Tailwind Labs)
   - **MySQL** (by Jun Han)
   - **Live Server** (by Ritwick Dey)

### Open Project

1. File → Open Folder
2. Select: `C:\xampp\htdocs\Taskmasters`
3. Click "Select Folder"

---

## 📝 Configuration

### Update Contact Information

**File**: `index.php`

**WhatsApp Number** (Line 145 & 189):
```php
<a href="https://wa.me/YOUR_NUMBER" target="_blank">
```
Replace `YOUR_NUMBER` with your WhatsApp number (e.g., 919876543210)

**Email Address** (Line 149 & 193):
```php
<a href="mailto:YOUR_EMAIL@example.com">
```
Replace with your email address

### Update Payment UPI ID

**File**: `checkout.php` (Line 52)

```php
upi://pay?pa=YOUR_UPI_ID@upi&pn=TaskMasters&am=<?php echo $order['price']; ?>&cu=INR
```
Replace `YOUR_UPI_ID@upi` with your actual UPI ID

---

## 🔧 Troubleshooting

### Problem: Apache won't start

**Error**: Port 80 already in use

**Solution**:
1. Close Skype (uses port 80)
2. OR change Apache port:
   - XAMPP Control → Apache Config → httpd.conf
   - Find: `Listen 80`
   - Change to: `Listen 8080`
   - Save and restart Apache
   - Access site: `http://localhost:8080/Taskmasters/`

### Problem: MySQL won't start

**Error**: Port 3306 already in use

**Solution**:
1. Open Task Manager (Ctrl+Shift+Esc)
2. Find "MySQL" processes
3. End all MySQL tasks
4. Start MySQL in XAMPP

### Problem: Database connection error

**Error**: "Connection failed"

**Solution**:
1. Check MySQL is running in XAMPP
2. Verify database name is `taskmasters`
3. Check `config/database.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'taskmasters');
   ```

### Problem: Page shows blank/white screen

**Solution**:
1. Enable error display:
   - Open `php.ini` in XAMPP
   - Find: `display_errors = Off`
   - Change to: `display_errors = On`
   - Restart Apache
2. Check browser console (F12) for errors

### Problem: File upload not working

**Solution**:
1. Check folder exists: `C:\xampp\htdocs\Taskmasters\uploads\`
2. Right-click folder → Properties → Security
3. Give "Full Control" to "Users"

### Problem: Images/CSS not loading

**Solution**:
1. Clear browser cache (Ctrl+Shift+Delete)
2. Hard refresh page (Ctrl+F5)
3. Check browser console for 404 errors

---

## ✅ Verification Checklist

After setup, verify everything works:

- [ ] XAMPP Apache is running
- [ ] XAMPP MySQL is running
- [ ] Database `taskmasters` exists
- [ ] 3 tables created (users, orders, services)
- [ ] Landing page loads: `http://localhost/Taskmasters/`
- [ ] Can register new user
- [ ] Can login successfully
- [ ] Can browse services
- [ ] Can create order
- [ ] Can access checkout
- [ ] Admin account created
- [ ] Admin panel accessible
- [ ] Can view orders in admin
- [ ] Can update order status

---

## 📚 Common Commands

| Task | Command/URL |
|------|-------------|
| Start Apache | XAMPP Control Panel → Start Apache |
| Start MySQL | XAMPP Control Panel → Start MySQL |
| Stop Apache | XAMPP Control Panel → Stop Apache |
| Stop MySQL | XAMPP Control Panel → Stop MySQL |
| Website | http://localhost/Taskmasters/ |
| phpMyAdmin | http://localhost/phpmyadmin |
| Admin Panel | http://localhost/Taskmasters/admin/dashboard.php |
| Config File | C:\xampp\htdocs\Taskmasters\config\database.php |
| Error Logs | C:\xampp\apache\logs\error.log |

---

## 🎓 Learning Resources

### PHP Basics
- W3Schools PHP: https://www.w3schools.com/php/
- PHP Manual: https://www.php.net/manual/en/

### MySQL
- W3Schools SQL: https://www.w3schools.com/sql/
- phpMyAdmin Docs: https://docs.phpmyadmin.net/

### Tailwind CSS
- Tailwind Docs: https://tailwindcss.com/docs
- Tailwind Cheatsheet: https://nerdcave.com/tailwind-cheat-sheet

---

## 🆘 Getting Help

### Check Logs

**Apache Error Log**:
```
C:\xampp\apache\logs\error.log
```

**PHP Error Log**:
```
C:\xampp\php\logs\php_error_log
```

### Enable Debug Mode

Add to top of any PHP file:
```php
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
```

### Test Database Connection

Create `test.php` in `C:\xampp\htdocs\`:
```php
<?php
$conn = new mysqli('localhost', 'root', '', 'taskmasters');
if ($conn->connect_error) {
    die("Failed: " . $conn->connect_error);
}
echo "Connected successfully!";
?>
```

Access: `http://localhost/test.php`

---

## 🎉 Success!

If you've completed all steps, your TaskMasters platform is ready!

**Next Steps**:
1. Customize contact information
2. Update UPI payment details
3. Test complete order flow
4. Add your own services
5. Deploy to production hosting

---

## 📞 Support

If you encounter issues:
1. Check Troubleshooting section above
2. Review error logs
3. Search error message online
4. Check XAMPP forums: https://community.apachefriends.org/

---

**Happy Coding! 🚀**
