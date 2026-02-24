<?php
// INSTANT DATABASE SETUP

// Connect without database first
$conn = new mysqli('localhost', 'root', '');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h1>Setting up TaskMasters V2...</h1>";

// Create database
if ($conn->query("CREATE DATABASE IF NOT EXISTS taskmasters_v2")) {
    echo "✓ Database created<br>";
} else {
    echo "✗ Error creating database: " . $conn->error . "<br>";
}

// Select database
$conn->select_db('taskmasters_v2');

// Create tables
$tables = [
    "CREATE TABLE IF NOT EXISTS guest_orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id VARCHAR(20) UNIQUE NOT NULL,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        service VARCHAR(100) NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        discount_applied TINYINT(1) DEFAULT 0,
        final_price DECIMAL(10,2) NOT NULL,
        requirements TEXT NOT NULL,
        file_path VARCHAR(255),
        payment_status ENUM('pending', 'paid', 'verified') DEFAULT 'pending',
        payment_screenshot VARCHAR(255),
        order_status ENUM('pending', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
        delivered_file VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )",
    
    "CREATE TABLE IF NOT EXISTS admin_users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    "CREATE TABLE IF NOT EXISTS services (
        id INT AUTO_INCREMENT PRIMARY KEY,
        category VARCHAR(50) NOT NULL,
        name VARCHAR(255) NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        description TEXT,
        is_active TINYINT(1) DEFAULT 1,
        UNIQUE KEY unique_service (name, category)
    )"
];

foreach($tables as $sql) {
    if ($conn->query($sql)) {
        echo "✓ Table created<br>";
    } else {
        echo "✗ Error: " . $conn->error . "<br>";
    }
}

// Insert services
$conn->query("DELETE FROM services");
$services = [
    ['student', 'Email Drafting', 199, 'Professional email writing service'],
    ['student', 'Excel Work', 299, 'Excel spreadsheet and data management'],
    ['student', 'Presentation (PPT)', 499, 'Professional PowerPoint presentations'],
    ['student', 'Schedule Maker', 199, 'Custom schedule and timetable creation'],
    ['business', 'Social Media Management', 9999, 'Monthly social media management'],
    ['business', 'Product Video (5-10s)', 399, 'Short product promotional video'],
    ['business', 'Product Video (20-25s)', 699, 'Extended product promotional video'],
    ['business', 'Product Poster (1)', 199, 'Single product poster design'],
    ['business', 'Product Poster (5)', 599, 'Five product poster designs'],
    ['individual', 'Custom Poster', 299, 'Custom poster design'],
    ['individual', 'Rent Poster', 399, 'Rental property poster'],
    ['individual', 'Wedding Invitation', 999, 'Wedding invitation card design'],
    ['individual', 'Photo Editing', 299, 'Professional photo editing']
];

foreach($services as $s) {
    $stmt = $conn->prepare("INSERT INTO services (category, name, price, description) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $s[0], $s[1], $s[2], $s[3]);
    $stmt->execute();
}
echo "✓ Services inserted<br>";

// Create admin
$conn->query("DELETE FROM admin_users");
$admin_pass = password_hash('admin123', PASSWORD_DEFAULT);
$conn->query("INSERT INTO admin_users (username, password, email) VALUES ('admin', '$admin_pass', 'admin@taskmasters.com')");
echo "✓ Admin created<br>";

// Create directories
$dirs = ['uploads/requirements', 'uploads/payments', 'uploads/delivered'];
foreach($dirs as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
        echo "✓ Created: $dir<br>";
    }
}

echo "<br><h2 style='color: green;'>✅ SETUP COMPLETE!</h2>";
echo "<div style='background: #f0f9ff; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
echo "<h3>🚀 Your New System is Ready!</h3>";
echo "<p><strong>Public URLs:</strong></p>";
echo "<ul>";
echo "<li><a href='home.php' style='color: #7c3aed; font-weight: bold;'>Homepage (New Design)</a></li>";
echo "<li><a href='order.php' style='color: #7c3aed; font-weight: bold;'>Place Order</a></li>";
echo "</ul>";
echo "<p><strong>Admin Panel:</strong></p>";
echo "<ul>";
echo "<li><a href='admin/login.php' style='color: #7c3aed; font-weight: bold;'>Admin Login</a></li>";
echo "<li>Username: <code style='background: #e5e7eb; padding: 2px 6px; border-radius: 4px;'>admin</code></li>";
echo "<li>Password: <code style='background: #e5e7eb; padding: 2px 6px; border-radius: 4px;'>admin123</code></li>";
echo "</ul>";
echo "</div>";

$conn->close();
?>
