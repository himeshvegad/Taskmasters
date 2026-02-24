-- New Professional Database Schema (No User Authentication)

CREATE DATABASE IF NOT EXISTS taskmasters_v2;
USE taskmasters_v2;

-- Guest Orders Table (No user accounts needed)
CREATE TABLE guest_orders (
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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_order_id (order_id),
    INDEX idx_payment_status (payment_status),
    INDEX idx_order_status (order_status)
);

-- Admin Users Table (Separate from guest system)
CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Services Table
CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(50) NOT NULL,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT,
    is_active TINYINT(1) DEFAULT 1,
    UNIQUE KEY unique_service (name, category)
);

-- Insert Services
INSERT INTO services (category, name, price, description) VALUES
('student', 'Email Drafting', 199, 'Professional email writing service'),
('student', 'Excel Work', 299, 'Excel spreadsheet and data management'),
('student', 'Presentation (PPT)', 499, 'Professional PowerPoint presentations'),
('student', 'Schedule Maker', 199, 'Custom schedule and timetable creation'),
('business', 'Social Media Management', 9999, 'Monthly social media management'),
('business', 'Product Video (5-10s)', 399, 'Short product promotional video'),
('business', 'Product Video (20-25s)', 699, 'Extended product promotional video'),
('business', 'Product Poster (1)', 199, 'Single product poster design'),
('business', 'Product Poster (5)', 599, 'Five product poster designs'),
('individual', 'Custom Poster', 299, 'Custom poster design'),
('individual', 'Rent Poster', 399, 'Rental property poster'),
('individual', 'Wedding Invitation', 999, 'Wedding invitation card design'),
('individual', 'Photo Editing', 299, 'Professional photo editing');

-- Create default admin (username: admin, password: admin123)
INSERT INTO admin_users (username, password, email) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@taskmasters.com');
