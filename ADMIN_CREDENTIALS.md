# 🔐 Admin Login Credentials

## Default Admin Account

After running the database installer, use these credentials:

### Admin Login
**URL:** http://localhost/Taskmasters/admin/login.php

**Email:** admin@taskmasters.com  
**Password:** admin123

---

## Quick Access

1. Go to: http://localhost/Taskmasters/admin/login.php
2. Enter:
   - Email: `admin@taskmasters.com`
   - Password: `admin123`
3. Click "Login to Admin Panel"

---

## ⚠️ Important Security Notes

### For Production:
1. **Change the password immediately** after first login
2. **Use a strong password** (minimum 12 characters)
3. **Enable HTTPS** on your server
4. **Change the admin email** to your actual email

### How to Change Password:
1. Go to phpMyAdmin: http://localhost/phpmyadmin
2. Open `taskmasters` database
3. Click `users` table
4. Find admin user (admin@taskmasters.com)
5. Click Edit
6. Generate new password hash:
   ```php
   <?php echo password_hash('your_new_password', PASSWORD_DEFAULT); ?>
   ```
7. Replace the password field with new hash
8. Save

---

## Admin Panel Features

Once logged in, you can:
- ✅ View all customer orders
- ✅ See customer details (name, email, phone)
- ✅ View payment screenshots
- ✅ Verify payments
- ✅ Update order status
- ✅ Upload completed work files
- ✅ Download customer files

---

## Troubleshooting

### "Admin account not found"
- Run the installer again: http://localhost/Taskmasters/install.php
- Or manually insert admin via phpMyAdmin

### "Invalid password"
- Make sure you're using: `admin123`
- Check caps lock is off
- Try resetting password via phpMyAdmin

---

**Remember:** This is a default account for development. Change credentials before going live!
