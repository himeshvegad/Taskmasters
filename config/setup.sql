CREATE DATABASE IF NOT EXISTS taskmasters;
USE taskmasters;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_admin TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category VARCHAR(50) NOT NULL,
    service_name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    title VARCHAR(255) NOT NULL,
    work_summary TEXT NOT NULL,
    deadline DATE NOT NULL,
    special_instructions TEXT,
    file_path VARCHAR(255),
    status ENUM('pending', 'in_progress', 'delivered') DEFAULT 'pending',
    payment_verified TINYINT(1) DEFAULT 0,
    payment_screenshot VARCHAR(255),
    delivered_file VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(50) NOT NULL,
    name VARCHAR(255) NOT NULL,
    price VARCHAR(50) NOT NULL,
    description TEXT
);

INSERT INTO services (category, name, price, description) VALUES
('student', 'Presentation (PPT)', '499', 'Professional PowerPoint presentations'),
('student', 'Email Drafting', '199', 'Professional email writing'),
('student', 'Schedule Maker', '199', 'Custom schedule and timetable creation'),
('student', 'Excel Work', '299', 'Excel spreadsheet work and data management'),
('business', 'Social Media Management', '9999-39999', 'Monthly social media management'),
('business', 'Product Video (5-10s)', '399', 'Short product promotional video'),
('business', 'Product Video (20-25s)', '699', 'Extended product promotional video'),
('business', 'Product Poster (1)', '199', 'Single product poster design'),
('business', 'Product Poster (5)', '599', 'Five product poster designs'),
('individual', 'Custom Poster', '299', 'Custom poster design'),
('individual', 'Rent Poster', '399', 'Rental property poster'),
('individual', 'Wedding Invitation', '999', 'Wedding invitation card design'),
('individual', 'Photo Editing', '299', 'Professional photo editing');

-- Create default admin account
-- Email: admin@taskmasters.com
-- Password: admin123
INSERT INTO users (name, email, phone, password, is_admin) 
VALUES ('Admin', 'admin@taskmasters.com', '1234567890', 
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1);
