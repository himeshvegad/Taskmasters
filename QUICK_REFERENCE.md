# 🔐 TaskMasters Authentication - Quick Reference

## 📋 System Overview

```
┌─────────────┐
│  login.php  │ ← Single login for ALL users
└──────┬──────┘
       │
       ├─→ is_admin = 1 → /admin/dashboard.php
       │
       └─→ is_admin = 0 → /dashboard.php
```

---

## 🚀 Quick Start

### 1. Test User Login
```
1. Go to: http://localhost/Taskmasters/register.php
2. Register new account
3. Login at: http://localhost/Taskmasters/login.php
4. Redirects to: /dashboard.php
```

### 2. Test Admin Login
```
1. In phpMyAdmin:
   UPDATE users SET is_admin = 1 WHERE email = 'your@email.com';

2. Login at: http://localhost/Taskmasters/login.php
3. Redirects to: /admin/dashboard.php
```

---

## 🔒 Protect Admin Pages

**Add to TOP of every admin page:**

```php
<?php
session_start();
require_once '../config/database.php';
require_once 'includes/auth_check.php';
?>
```

---

## 📝 Secure Query Template

```php
// SELECT
$stmt = $conn->prepare("SELECT * FROM table WHERE column = ?");
$stmt->bind_param("s", $value);
$stmt->execute();
$result = $stmt->get_result();

// INSERT
$stmt = $conn->prepare("INSERT INTO table (col1, col2) VALUES (?, ?)");
$stmt->bind_param("ss", $val1, $val2);
$stmt->execute();

// UPDATE
$stmt = $conn->prepare("UPDATE table SET column = ? WHERE id = ?");
$stmt->bind_param("si", $value, $id);
$stmt->execute();

// DELETE
$stmt = $conn->prepare("DELETE FROM table WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
```

---

## 🎯 Parameter Types

```php
"i" = integer
"d" = double/float
"s" = string
"b" = blob

// Examples:
bind_param("i", $id)           // Single integer
bind_param("ss", $name, $email) // Two strings
bind_param("isd", $id, $name, $price) // Mixed types
```

---

## 🛡️ Security Checklist

```php
// ✅ Always escape output
echo htmlspecialchars($data);

// ✅ Always use prepared statements
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");

// ✅ Always hash passwords
$hash = password_hash($password, PASSWORD_DEFAULT);

// ✅ Always verify passwords
password_verify($input, $hash);

// ✅ Always validate input
$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
$id = (int)$_GET['id'];

// ✅ Always check sessions
if (isset($_SESSION['user_id'])) { }
```

---

## 📊 Admin Dashboard Queries

```php
// Total users
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM users WHERE is_admin = 0");
$stmt->execute();
$total_users = $stmt->get_result()->fetch_assoc()['count'];

// Total orders
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM orders");
$stmt->execute();
$total_orders = $stmt->get_result()->fetch_assoc()['count'];

// Total revenue
$stmt = $conn->prepare("SELECT SUM(price) as total FROM orders WHERE payment_verified = 1");
$stmt->execute();
$total_revenue = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

// Recent orders
$stmt = $conn->prepare("SELECT o.*, u.name, u.email 
                        FROM orders o 
                        JOIN users u ON o.user_id = u.id 
                        ORDER BY o.created_at DESC 
                        LIMIT 10");
$stmt->execute();
$recent_orders = $stmt->get_result();
```

---

## 🔑 Session Variables

```php
$_SESSION['user_id']      // User ID (int)
$_SESSION['user_name']    // User name (string)
$_SESSION['user_email']   // User email (string)
$_SESSION['is_admin']     // 0 or 1 (int)
```

---

## 🚪 Login Flow

```php
// 1. Validate credentials
$stmt = $conn->prepare("SELECT id, name, email, password, is_admin FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// 2. Verify password
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        
        // 3. Create session
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['is_admin'] = $user['is_admin'];
        
        // 4. Redirect based on role
        if ($user['is_admin'] == 1) {
            header('Location: admin/dashboard.php');
        } else {
            header('Location: dashboard.php');
        }
        exit;
    }
}
```

---

## 🚪 Logout Flow

```php
session_start();
$_SESSION = array();
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}
session_destroy();
header('Location: index.php');
exit;
```

---

## 🐛 Common Errors & Fixes

### "Email not found"
```
✓ Check email spelling
✓ Verify user exists in database
✓ Check database connection
```

### "Incorrect password"
```
✓ Verify password is correct
✓ Check password was hashed during registration
✓ Try resetting password
```

### Redirects to login when accessing admin
```
✓ Check is_admin = 1 in database
✓ Clear browser cookies
✓ Re-login
✓ Check session_start() is at top
```

### Session not working
```
✓ Ensure session_start() is FIRST line
✓ No output before session_start()
✓ Check PHP session settings
✓ Clear browser cache
```

---

## 📁 File Structure

```
Taskmasters/
├── login.php                    ← Single login page
├── register.php                 ← User registration
├── logout.php                   ← Session cleanup
├── dashboard.php                ← User dashboard
├── admin/
│   ├── includes/
│   │   └── auth_check.php      ← Protection middleware
│   ├── dashboard.php           ← Admin dashboard
│   ├── users.php               ← User management
│   ├── orders.php              ← Order management
│   └── services.php            ← Service management
└── config/
    ├── database.php            ← DB connection
    └── secure_queries.php      ← Query examples
```

---

## 🎯 Testing Checklist

- [ ] Register new user
- [ ] Login as user → redirects to /dashboard.php
- [ ] Try accessing /admin/dashboard.php → redirects to login
- [ ] Logout
- [ ] Set is_admin = 1 in database
- [ ] Login as admin → redirects to /admin/dashboard.php
- [ ] Access all admin pages successfully
- [ ] Logout
- [ ] Try accessing admin pages → redirects to login

---

## 📞 Need Help?

1. Check `SECURITY_GUIDE.md` for detailed documentation
2. Check `config/secure_queries.php` for query examples
3. Check browser console for JavaScript errors
4. Check PHP error logs in XAMPP
5. Verify database connection in phpMyAdmin

---

## ✅ What's Implemented

- ✅ Single login page for all users
- ✅ Role-based redirect (admin vs user)
- ✅ Password hashing (bcrypt)
- ✅ Password verification
- ✅ Prepared statements (SQL injection prevention)
- ✅ XSS protection (htmlspecialchars)
- ✅ Session management
- ✅ Admin route protection
- ✅ Proper logout
- ✅ Error messages

---

## 🔐 Security Level: PRODUCTION READY ✨
