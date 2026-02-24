<?php
require_once 'config/database.php';

echo "<h1>Removing Duplicates...</h1>";

// Step 1: Show current duplicates
echo "<h2>Current Duplicates:</h2>";
$check = $conn->query("SELECT name, category, COUNT(*) as count FROM services GROUP BY name, category HAVING count > 1");
while($row = $check->fetch_assoc()) {
    echo "- {$row['name']} ({$row['category']}): {$row['count']} copies<br>";
}

// Step 2: Delete all services
echo "<h2>Clearing services table...</h2>";
$conn->query("TRUNCATE TABLE services");
echo "✓ Cleared<br>";

// Step 3: Insert unique services only
echo "<h2>Inserting unique services...</h2>";
$conn->query("INSERT INTO services (category, name, price, description) VALUES
('student', 'Email Drafting', '199', 'Professional email writing'),
('student', 'Excel Work', '299', 'Excel spreadsheet work and data management'),
('student', 'Presentation (PPT)', '499', 'Professional PowerPoint presentations'),
('student', 'Schedule Maker', '199', 'Custom schedule and timetable creation'),
('business', 'Social Media Management', '9999-39999', 'Monthly social media management'),
('business', 'Product Video (5-10s)', '399', 'Short product promotional video'),
('business', 'Product Video (20-25s)', '699', 'Extended product promotional video'),
('business', 'Product Poster (1)', '199', 'Single product poster design'),
('business', 'Product Poster (5)', '599', 'Five product poster designs'),
('individual', 'Custom Poster', '299', 'Custom poster design'),
('individual', 'Rent Poster', '399', 'Rental property poster'),
('individual', 'Wedding Invitation', '999', 'Wedding invitation card design'),
('individual', 'Photo Editing', '299', 'Professional photo editing')");
echo "✓ Inserted 13 unique services<br>";

// Step 4: Add unique constraint
echo "<h2>Adding unique constraint...</h2>";
$conn->query("ALTER TABLE services DROP INDEX IF EXISTS unique_service");
$conn->query("ALTER TABLE services ADD UNIQUE KEY unique_service (name, category)");
echo "✓ Constraint added<br>";

// Step 5: Verify
echo "<h2>Final Count:</h2>";
$total = $conn->query("SELECT COUNT(*) as count FROM services")->fetch_assoc()['count'];
echo "Total services: <strong>{$total}</strong><br>";

echo "<h2 style='color: green;'>✓ DONE! All duplicates removed.</h2>";
echo "<a href='services.php?category=student' style='display:inline-block;margin:20px;padding:10px 20px;background:blue;color:white;text-decoration:none;border-radius:5px;'>View Services</a>";
echo "<a href='index.php' style='display:inline-block;margin:20px;padding:10px 20px;background:purple;color:white;text-decoration:none;border-radius:5px;'>Go Home</a>";
?>
