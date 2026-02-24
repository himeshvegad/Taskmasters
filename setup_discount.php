<?php
require_once 'config/database.php';

// Add column if not exists
$conn->query("ALTER TABLE users ADD COLUMN IF NOT EXISTS first_order_discount TINYINT(1) DEFAULT 1");

// Set existing users to 0 (already used)
$conn->query("UPDATE users SET first_order_discount = 0 WHERE id > 0");

echo "✓ Database updated! First order discount feature enabled.<br>";
echo "<a href='index.php' style='color:blue;'>Go to Home</a>";
?>
