<?php
// ONE-CLICK SETUP FOR NEW TASKMASTERS SYSTEM

require_once 'config/database.php';

echo "<h1>TaskMasters V2 Setup</h1>";
echo "<p>Setting up new professional system...</p>";

// Read and execute SQL
$sql = file_get_contents('config/schema_v2.sql');
$queries = explode(';', $sql);

foreach($queries as $query) {
    $query = trim($query);
    if (!empty($query)) {
        if($conn->query($query)) {
            echo "✓ Query executed<br>";
        } else {
            echo "✗ Error: " . $conn->error . "<br>";
        }
    }
}

// Create upload directories
$dirs = ['uploads/requirements', 'uploads/payments', 'uploads/delivered'];
foreach($dirs as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
        echo "✓ Created directory: $dir<br>";
    }
}

echo "<br><h2>✅ Setup Complete!</h2>";
echo "<p><strong>New System URLs:</strong></p>";
echo "<ul>";
echo "<li><a href='home.php'>Homepage (New Design)</a></li>";
echo "<li><a href='order.php'>Place Order (Guest Checkout)</a></li>";
echo "<li><a href='admin/login.php'>Admin Login</a></li>";
echo "</ul>";
echo "<p><strong>Admin Credentials:</strong><br>Username: admin<br>Password: admin123</p>";
echo "<p><strong>Note:</strong> Old login/register system is now deprecated. Users can order without accounts!</p>";
?>
