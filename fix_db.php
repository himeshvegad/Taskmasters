<?php
require_once 'config/database.php';

// Clear and reset services table
$conn->query("TRUNCATE TABLE services");

// Insert unique services only
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

// Add unique constraint
$conn->query("ALTER TABLE services DROP INDEX IF EXISTS unique_service");
$conn->query("ALTER TABLE services ADD UNIQUE KEY unique_service (name, category)");

header("Location: services.php?category=student&fixed=1");
exit;
?>
