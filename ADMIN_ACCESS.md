# 🔐 Admin Panel Access Guide

## Admin Login URL

**Direct Admin Login:** http://localhost/Taskmasters/admin/login.php

---

## 🚀 Quick Setup (3 Steps)

### Step 1: Create Admin Account
1. Go to: http://localhost/phpmyadmin
2. Click `taskmasters` database
3. Click `users` table
4. Click **Insert** tab
5. Fill in:
   - **name**: Admin
   - **email**: admin@taskmasters.com
   - **phone**: 1234567890
   - **password**: (leave empty for now)
   - **is_admin**: 1
   - **created_at**: CURRENT_TIMESTAMP
6. Click **Go**

### Step 2: Set Password
1. Register at: http://localhost/Taskmasters/register.php
   - Use email: admin@taskmasters.com
   - Set your password
2. This will update the password for admin account

### Step 3: Login to Admin Panel
Go to: **http://localhost/Taskmasters/admin/login.php**
- Email: admin@taskmasters.com
- Password: (your password)

---

## 📋 Alternative Method (Easier)

### Create Admin via Registration
1. Register normally: http://localhost/Taskmasters/register.php
2. Go to phpMyAdmin: http://localhost/phpmyadmin
3. Open `taskmasters` → `users` table
4. Find your user → Click **Edit**
5. Change `is_admin` from `0` to `1`
6. Click **Go**
7. Login at: http://localhost/Taskmasters/admin/login.php

---

## 🎯 Admin Panel Features

Once logged in, you can:
- ✅ View all customer orders
- ✅ See customer details (name, email, phone)
- ✅ View payment screenshots
- ✅ Verify payments
- ✅ Update order status (Pending → In Progress → Delivered)
- ✅ Upload completed work files
- ✅ Download customer uploaded files

---

## 📍 Important URLs

| Purpose | URL |
|---------|-----|
| **Admin Login** | http://localhost/Taskmasters/admin/login.php |
| Admin Dashboard | http://localhost/Taskmasters/admin/dashboard.php |
| User Website | http://localhost/Taskmasters/ |
| User Login | http://localhost/Taskmasters/login.php |
| phpMyAdmin | http://localhost/phpmyadmin |

---

## 🔒 Security Notes

- Admin login is **separate** from user login
- Only accounts with `is_admin = 1` can access admin panel
- Admin cannot login through user login page
- Users cannot access admin panel

---

## ✅ Quick Test

1. **Admin Login**: http://localhost/Taskmasters/admin/login.php
2. **User Login**: http://localhost/Taskmasters/login.php

Both are now completely separate!
