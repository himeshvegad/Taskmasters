# TaskMasters - Digital Task Service Platform

## Project Overview
TaskMasters is a complete web application for a digital task service platform built with PHP, MySQL, HTML, CSS (Tailwind), and JavaScript.

## Technology Stack

### Frontend
- **HTML5** - Structure and markup
- **Tailwind CSS** - Utility-first CSS framework (via CDN)
- **JavaScript** - Client-side interactivity
- **Font Awesome 6.4.0** - Icons

### Backend
- **PHP 8.x** - Server-side scripting
- **MySQL** - Database management

### Server
- **Apache** - Web server (via XAMPP)

## Project Structure

```
Taskmasters/
├── admin/
│   ├── dashboard.php          # Admin panel - view all orders
│   └── order_details.php      # Admin order management
├── config/
│   ├── database.php           # Database connection
│   └── setup.sql              # Database schema
├── uploads/
│   ├── payments/              # Payment screenshots
│   └── delivered/             # Delivered files
├── index.php                  # Landing page
├── login.php                  # User login
├── register.php               # User registration
├── logout.php                 # Logout functionality
├── services.php               # Service selection
├── order.php                  # Order form
├── checkout.php               # Payment page
├── dashboard.php              # User dashboard
├── privacy.php                # Privacy policy
├── terms.php                  # Terms of service
├── refund.php                 # Refund policy
├── header.php                 # Header include
└── footer.php                 # Footer include
```

## Database Schema

### Tables

#### users
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- name (VARCHAR 255)
- email (VARCHAR 255, UNIQUE)
- phone (VARCHAR 20)
- password (VARCHAR 255, hashed)
- is_admin (TINYINT, default 0)
- created_at (TIMESTAMP)

#### orders
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- user_id (INT, FOREIGN KEY)
- category (VARCHAR 50)
- service_name (VARCHAR 255)
- price (DECIMAL 10,2)
- title (VARCHAR 255)
- work_summary (TEXT)
- deadline (DATE)
- special_instructions (TEXT)
- file_path (VARCHAR 255)
- status (ENUM: pending, in_progress, delivered)
- payment_verified (TINYINT, default 0)
- payment_screenshot (VARCHAR 255)
- delivered_file (VARCHAR 255)
- created_at (TIMESTAMP)

#### services
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- category (VARCHAR 50)
- name (VARCHAR 255)
- price (VARCHAR 50)
- description (TEXT)

## Features Implemented

### User Features
✅ Landing page with animations
✅ Service search functionality
✅ User registration and login
✅ Service browsing by category
✅ Order placement with file upload
✅ UPI payment with QR code
✅ User dashboard to track orders
✅ Order status tracking (Pending, In Progress, Delivered)
✅ Download delivered files

### Admin Features
✅ Admin dashboard
✅ View all orders
✅ Verify payments
✅ Update order status
✅ Upload delivered files
✅ View customer details

### Additional Features
✅ Trust indicators (10-Day Refund, Secure Payments, Fast Delivery)
✅ Floating WhatsApp and Email buttons
✅ Legal pages (Privacy, Terms, Refund)
✅ Responsive design
✅ Session management

## Installation Instructions

### Prerequisites
1. **XAMPP** (includes Apache, PHP, MySQL)
   - Download: https://www.apachefriends.org/download.html
   - Install XAMPP to default location (C:\xampp)

### Quick Setup (2 Minutes)

1. **Start XAMPP Services**
   - Open XAMPP Control Panel
   - Start Apache
   - Start MySQL

2. **Install Database (ONE CLICK)**
   - Open browser: http://localhost/Taskmasters/install.php
   - Click "Install Database"
   - Done!

3. **Access the Website**
   - Open browser: http://localhost/Taskmasters/

### Alternative Setup (Manual)

1. **Start XAMPP Services**
   - Open XAMPP Control Panel
   - Start Apache
   - Start MySQL

2. **Create Database**
   - Open browser and go to: http://localhost/phpmyadmin
   - Click "New" to create database
   - Database name: `taskmasters`
   - Click "Create"

3. **Import Database Schema**
   - Select `taskmasters` database
   - Click "Import" tab
   - Choose file: `c:\xampp\htdocs\Taskmasters\config\setup.sql`
   - Click "Go"

4. **Access the Website**
   - Open browser: http://localhost/Taskmasters/

5. **Create Admin Account**
   - Register a new account
   - Go to phpMyAdmin
   - Open `taskmasters` database
   - Click on `users` table
   - Find your user and edit
   - Change `is_admin` from 0 to 1
   - Save

6. **Admin Access**
   - Login with admin account
   - You'll be redirected to: http://localhost/Taskmasters/admin/dashboard.php

## Service Catalog

### Student Services
- Presentation (PPT) - ₹499
- Email Drafting - ₹199
- Schedule Maker - ₹199
- Excel Work - ₹299

### Business Services
- Social Media Management - ₹9,999-₹39,999/month
- Product Video (5-10s) - ₹399
- Product Video (20-25s) - ₹699
- Product Poster (1) - ₹199
- Product Poster (5) - ₹599

### Individual Services
- Custom Poster - ₹299
- Rent Poster - ₹399
- Wedding Invitation - ₹999
- Photo Editing - ₹299

## User Flow

1. **Landing Page** → Browse services
2. **Register/Login** → Create account
3. **Select Service** → Choose from categories
4. **Order Form** → Fill details and upload files
5. **Checkout** → Pay via UPI QR code
6. **Dashboard** → Track order status
7. **Delivery** → Download completed work

## Admin Workflow

1. **Login** → Access admin panel
2. **View Orders** → See all customer orders
3. **Verify Payment** → Check payment screenshots
4. **Update Status** → Mark as In Progress
5. **Upload File** → Deliver completed work
6. **Mark Delivered** → Customer can download

## Configuration

### Database Settings
Edit `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'taskmasters');
```

### Payment UPI ID
Edit `checkout.php` line 52:
```php
upi://pay?pa=YOUR_UPI_ID@upi
```

### Contact Information
Edit `index.php`:
- WhatsApp: Line 145 and 189
- Email: Line 149 and 193

## VS Code Extensions (Recommended)

1. **PHP Intelephense** - PHP code intelligence
2. **PHP Debug** - Debug PHP code
3. **Tailwind CSS IntelliSense** - Tailwind autocomplete
4. **MySQL** - Database management

## Quick Command Reference

| Action | Command |
|--------|---------|
| Start Apache | XAMPP Control Panel → Start Apache |
| Start MySQL | XAMPP Control Panel → Start MySQL |
| Access Website | http://localhost/Taskmasters/ |
| Access phpMyAdmin | http://localhost/phpmyadmin |
| Admin Panel | http://localhost/Taskmasters/admin/dashboard.php |

## Troubleshooting

### Apache Won't Start
- Port 80 is in use
- Solution: Stop Skype or change Apache port in httpd.conf

### MySQL Won't Start
- Port 3306 is in use
- Solution: Stop other MySQL services

### Database Connection Error
- Check XAMPP MySQL is running
- Verify database name is `taskmasters`
- Check credentials in `config/database.php`

### File Upload Not Working
- Check folder permissions
- Ensure `uploads/` directory exists
- PHP upload_max_filesize setting

### Page Not Found
- Verify file is in correct directory
- Check Apache is running
- Clear browser cache

## Security Notes

⚠️ **This is a development version. For production:**

1. **Use HTTPS** - Enable SSL certificate
2. **Secure Passwords** - Already using password_hash()
3. **SQL Injection** - Use prepared statements
4. **File Upload** - Add file type validation
5. **Session Security** - Set secure session cookies
6. **Environment Variables** - Move credentials to .env
7. **CSRF Protection** - Add CSRF tokens to forms
8. **Input Validation** - Sanitize all user inputs
9. **Rate Limiting** - Prevent brute force attacks
10. **Backup** - Regular database backups

## Production Deployment

### For Netlify (Static Hosting)
❌ **Netlify does NOT support PHP/MySQL**

Netlify only hosts static sites (HTML, CSS, JS). For this PHP application, use:

### Recommended Hosting Options:

1. **Shared Hosting** (Easiest)
   - Hostinger, Bluehost, SiteGround
   - Includes PHP + MySQL
   - cPanel for management
   - ~$3-10/month

2. **VPS Hosting** (More Control)
   - DigitalOcean, Linode, Vultr
   - Install LAMP stack
   - ~$5-20/month

3. **Cloud Hosting**
   - AWS EC2 + RDS
   - Google Cloud Platform
   - Azure

### Deployment Steps (Shared Hosting):

1. Export database from phpMyAdmin
2. Upload files via FTP/cPanel File Manager
3. Import database on hosting
4. Update `config/database.php` with hosting credentials
5. Set folder permissions (755 for folders, 644 for files)
6. Test all functionality

## Future Enhancements

- Email notifications (PHPMailer)
- WhatsApp API integration
- Payment gateway (Razorpay, PayU)
- Google OAuth login
- Invoice generation
- Review/Rating system
- Notification system
- Advanced search
- Analytics dashboard

## Support

For issues or questions:
- Email: support@taskmasters.com
- WhatsApp: +91 1234567890

## License

© 2024 TaskMasters. All rights reserved.
"# Taskmasters" 
#   T a s k m a s t e r s  
 #   T a s k m a s t e r s  
 #   T a s k m a s t e r s  
 