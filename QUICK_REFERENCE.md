# TaskMasters - Quick Reference Card

## 🔗 Essential URLs

| Purpose | URL |
|---------|-----|
| Website Home | http://localhost/Taskmasters/ |
| User Login | http://localhost/Taskmasters/login.php |
| User Register | http://localhost/Taskmasters/register.php |
| User Dashboard | http://localhost/Taskmasters/dashboard.php |
| Admin Panel | http://localhost/Taskmasters/admin/dashboard.php |
| phpMyAdmin | http://localhost/phpmyadmin |
| XAMPP Dashboard | http://localhost |

## 🗂️ File Locations

| Item | Path |
|------|------|
| Project Root | C:\xampp\htdocs\Taskmasters\ |
| Database Config | C:\xampp\htdocs\Taskmasters\config\database.php |
| Database Schema | C:\xampp\htdocs\Taskmasters\config\setup.sql |
| Uploads Folder | C:\xampp\htdocs\Taskmasters\uploads\ |
| Admin Files | C:\xampp\htdocs\Taskmasters\admin\ |
| Apache Config | C:\xampp\apache\conf\httpd.conf |
| PHP Config | C:\xampp\php\php.ini |
| Error Logs | C:\xampp\apache\logs\error.log |

## 🗄️ Database Information

| Setting | Value |
|---------|-------|
| Database Name | taskmasters |
| Host | localhost |
| Username | root |
| Password | (empty) |
| Port | 3306 |

### Tables
- **users** - User accounts
- **orders** - Customer orders
- **services** - Service catalog

## 👤 Default Test Credentials

### Regular User
- Email: test@example.com
- Password: password123

### Admin User
- Email: admin@example.com
- Password: admin123
- Note: Set is_admin = 1 in database

## 🎨 Service Pricing

### Student Services
| Service | Price |
|---------|-------|
| Presentation (PPT) | ₹499 |
| Email Drafting | ₹199 |
| Schedule Maker | ₹199 |
| Excel Work | ₹299 |

### Business Services
| Service | Price |
|---------|-------|
| Social Media Management | ₹9,999-₹39,999 |
| Product Video (5-10s) | ₹399 |
| Product Video (20-25s) | ₹699 |
| Product Poster (1) | ₹199 |
| Product Poster (5) | ₹599 |

### Individual Services
| Service | Price |
|---------|-------|
| Custom Poster | ₹299 |
| Rent Poster | ₹399 |
| Wedding Invitation | ₹999 |
| Photo Editing | ₹299 |

## 🔧 XAMPP Control

| Action | Steps |
|--------|-------|
| Start Apache | XAMPP Control → Apache → Start |
| Stop Apache | XAMPP Control → Apache → Stop |
| Start MySQL | XAMPP Control → MySQL → Start |
| Stop MySQL | XAMPP Control → MySQL → Stop |
| Apache Config | XAMPP Control → Apache → Config → httpd.conf |
| PHP Config | XAMPP Control → Apache → Config → php.ini |
| MySQL Config | XAMPP Control → MySQL → Config → my.ini |

## 📝 Common PHP Commands

### Database Connection Test
```php
<?php
$conn = new mysqli('localhost', 'root', '', 'taskmasters');
if ($conn->connect_error) {
    die("Failed: " . $conn->connect_error);
}
echo "Connected!";
?>
```

### Enable Error Display
```php
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
```

### Check PHP Version
```php
<?php phpinfo(); ?>
```

## 🗃️ Common SQL Queries

### View All Users
```sql
SELECT * FROM users;
```

### View All Orders
```sql
SELECT * FROM orders ORDER BY created_at DESC;
```

### Make User Admin
```sql
UPDATE users SET is_admin = 1 WHERE email = 'user@example.com';
```

### View Pending Orders
```sql
SELECT * FROM orders WHERE status = 'pending';
```

### Count Orders by Status
```sql
SELECT status, COUNT(*) as count FROM orders GROUP BY status;
```

### Delete Test Order
```sql
DELETE FROM orders WHERE id = 1;
```

## 🔐 File Permissions

| Folder/File | Permission | Purpose |
|-------------|------------|---------|
| uploads/ | 777 | Allow file uploads |
| uploads/payments/ | 777 | Payment screenshots |
| uploads/delivered/ | 777 | Delivered files |
| config/ | 755 | Configuration files |
| *.php | 644 | PHP files |

## 🐛 Debug Checklist

When something doesn't work:

1. [ ] Is Apache running?
2. [ ] Is MySQL running?
3. [ ] Does database exist?
4. [ ] Are tables created?
5. [ ] Is config/database.php correct?
6. [ ] Check browser console (F12)
7. [ ] Check Apache error log
8. [ ] Clear browser cache
9. [ ] Try different browser
10. [ ] Restart XAMPP services

## 🚨 Common Errors & Fixes

### "Connection failed"
→ Start MySQL in XAMPP

### "Table doesn't exist"
→ Import setup.sql in phpMyAdmin

### "Access denied"
→ Check database credentials

### "Port 80 in use"
→ Close Skype or change Apache port

### "File upload failed"
→ Check uploads/ folder permissions

### Blank white page
→ Enable display_errors in php.ini

## 📊 Order Status Flow

```
pending → in_progress → delivered
```

### Status Meanings
- **pending**: Payment not verified
- **in_progress**: Admin working on it
- **delivered**: File uploaded, ready to download

## 🎯 Testing Workflow

### User Flow Test
1. Register new account
2. Login
3. Browse services
4. Select service
5. Fill order form
6. Upload file
7. Proceed to checkout
8. Upload payment screenshot
9. Check dashboard
10. Verify order appears

### Admin Flow Test
1. Login as admin
2. View orders list
3. Click order details
4. Verify payment
5. Update status
6. Upload delivered file
7. Verify status = delivered

## 📞 Contact Configuration

### Update WhatsApp Number
**File**: index.php (Lines 145, 189)
```php
https://wa.me/919876543210
```

### Update Email
**File**: index.php (Lines 149, 193)
```php
mailto:support@yourdomain.com
```

### Update UPI ID
**File**: checkout.php (Line 52)
```php
upi://pay?pa=yourname@upi
```

## 🔄 Backup Commands

### Backup Database
```bash
cd C:\xampp\mysql\bin
mysqldump -u root taskmasters > backup.sql
```

### Restore Database
```bash
cd C:\xampp\mysql\bin
mysql -u root taskmasters < backup.sql
```

### Backup Files
Copy entire folder:
```
C:\xampp\htdocs\Taskmasters\
```

## 📈 Performance Tips

1. **Enable Caching**
   - Add to .htaccess:
   ```apache
   <IfModule mod_expires.c>
       ExpiresActive On
       ExpiresByType image/jpg "access 1 year"
       ExpiresByType image/jpeg "access 1 year"
       ExpiresByType image/png "access 1 year"
   </IfModule>
   ```

2. **Optimize Images**
   - Compress before upload
   - Use WebP format
   - Max size: 500KB

3. **Database Optimization**
   - Add indexes:
   ```sql
   CREATE INDEX idx_user_id ON orders(user_id);
   CREATE INDEX idx_status ON orders(status);
   ```

## 🔍 Useful Browser Shortcuts

| Action | Shortcut |
|--------|----------|
| Open DevTools | F12 |
| Hard Refresh | Ctrl + F5 |
| Clear Cache | Ctrl + Shift + Delete |
| View Source | Ctrl + U |
| Inspect Element | Ctrl + Shift + C |

## 📱 Mobile Testing

### Browser DevTools
1. Press F12
2. Click device icon (Ctrl+Shift+M)
3. Select device (iPhone, Android)
4. Test responsive design

### Real Device Testing
1. Find your local IP: `ipconfig`
2. Access from phone: `http://192.168.x.x/Taskmasters/`
3. Ensure phone on same WiFi

## 🎓 Learning Resources

| Topic | Resource |
|-------|----------|
| PHP | https://www.php.net/manual/en/ |
| MySQL | https://dev.mysql.com/doc/ |
| Tailwind | https://tailwindcss.com/docs |
| Apache | https://httpd.apache.org/docs/ |
| XAMPP | https://www.apachefriends.org/faq.html |

## 🆘 Emergency Contacts

### Technical Support
- XAMPP Forum: https://community.apachefriends.org/
- Stack Overflow: https://stackoverflow.com/questions/tagged/php
- PHP Reddit: https://www.reddit.com/r/PHP/

### Hosting Support (When Deployed)
- Hostinger: 24/7 Live Chat
- Bluehost: 1-888-401-4678
- SiteGround: Ticket System

## ✅ Daily Checklist

### Before Starting Work
- [ ] Start XAMPP Apache
- [ ] Start XAMPP MySQL
- [ ] Open project in VS Code
- [ ] Open browser to localhost

### After Finishing Work
- [ ] Backup database
- [ ] Commit changes (if using Git)
- [ ] Stop XAMPP services
- [ ] Close all applications

## 🎯 Project Milestones

- [x] Setup XAMPP
- [x] Create database
- [x] Build landing page
- [x] User authentication
- [x] Service catalog
- [x] Order system
- [x] Payment integration
- [x] Admin panel
- [ ] Email notifications
- [ ] WhatsApp integration
- [ ] Deploy to production

---

**Print this page for quick reference!** 📄
