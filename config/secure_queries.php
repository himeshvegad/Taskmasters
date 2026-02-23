<?php
/**
 * SECURE DATABASE QUERY EXAMPLES
 * TaskMasters - Use these patterns throughout your application
 */

// ============================================
// 1. USER AUTHENTICATION
// ============================================

// Login - Check credentials
function loginUser($conn, $email, $password) {
    $stmt = $conn->prepare("SELECT id, name, email, password, is_admin FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            return $user;
        }
    }
    return false;
}

// Register - Create new user
function registerUser($conn, $name, $email, $phone, $password) {
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $phone, $hashed);
    return $stmt->execute();
}

// ============================================
// 2. USER MANAGEMENT
// ============================================

// Get user by ID
function getUserById($conn, $user_id) {
    $stmt = $conn->prepare("SELECT id, name, email, phone, is_admin, created_at FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Get all users (admin only)
function getAllUsers($conn) {
    $stmt = $conn->prepare("SELECT id, name, email, phone, created_at FROM users WHERE is_admin = 0 ORDER BY created_at DESC");
    $stmt->execute();
    return $stmt->get_result();
}

// Search users
function searchUsers($conn, $search) {
    $search_term = "%$search%";
    $stmt = $conn->prepare("SELECT id, name, email, phone FROM users WHERE (name LIKE ? OR email LIKE ?) AND is_admin = 0");
    $stmt->bind_param("ss", $search_term, $search_term);
    $stmt->execute();
    return $stmt->get_result();
}

// Update user
function updateUser($conn, $user_id, $name, $email, $phone) {
    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?");
    $stmt->bind_param("sssi", $name, $email, $phone, $user_id);
    return $stmt->execute();
}

// Delete user
function deleteUser($conn, $user_id) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    return $stmt->execute();
}

// ============================================
// 3. ORDER MANAGEMENT
// ============================================

// Create order
function createOrder($conn, $user_id, $category, $service_name, $price, $title, $work_summary, $deadline, $special_instructions, $file_path) {
    $stmt = $conn->prepare("INSERT INTO orders (user_id, category, service_name, price, title, work_summary, deadline, special_instructions, file_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issdsssss", $user_id, $category, $service_name, $price, $title, $work_summary, $deadline, $special_instructions, $file_path);
    return $stmt->execute();
}

// Get user orders
function getUserOrders($conn, $user_id) {
    $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
}

// Get all orders (admin)
function getAllOrders($conn) {
    $stmt = $conn->prepare("SELECT o.*, u.name as user_name, u.email, u.phone 
                            FROM orders o 
                            JOIN users u ON o.user_id = u.id 
                            ORDER BY o.created_at DESC");
    $stmt->execute();
    return $stmt->get_result();
}

// Get order by ID
function getOrderById($conn, $order_id) {
    $stmt = $conn->prepare("SELECT o.*, u.name as user_name, u.email, u.phone 
                            FROM orders o 
                            JOIN users u ON o.user_id = u.id 
                            WHERE o.id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Update order status
function updateOrderStatus($conn, $order_id, $status) {
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $order_id);
    return $stmt->execute();
}

// Verify payment
function verifyPayment($conn, $order_id) {
    $stmt = $conn->prepare("UPDATE orders SET payment_verified = 1 WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    return $stmt->execute();
}

// Upload delivered file
function uploadDeliveredFile($conn, $order_id, $file_path) {
    $stmt = $conn->prepare("UPDATE orders SET delivered_file = ?, status = 'delivered' WHERE id = ?");
    $stmt->bind_param("si", $file_path, $order_id);
    return $stmt->execute();
}

// Filter orders by category
function getOrdersByCategory($conn, $category) {
    $stmt = $conn->prepare("SELECT o.*, u.name as user_name, u.email 
                            FROM orders o 
                            JOIN users u ON o.user_id = u.id 
                            WHERE o.category = ? 
                            ORDER BY o.created_at DESC");
    $stmt->bind_param("s", $category);
    $stmt->execute();
    return $stmt->get_result();
}

// Filter orders by status
function getOrdersByStatus($conn, $status) {
    $stmt = $conn->prepare("SELECT o.*, u.name as user_name, u.email 
                            FROM orders o 
                            JOIN users u ON o.user_id = u.id 
                            WHERE o.status = ? 
                            ORDER BY o.created_at DESC");
    $stmt->bind_param("s", $status);
    $stmt->execute();
    return $stmt->get_result();
}

// Delete order
function deleteOrder($conn, $order_id) {
    $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    return $stmt->execute();
}

// ============================================
// 4. SERVICE MANAGEMENT
// ============================================

// Get all services
function getAllServices($conn) {
    $stmt = $conn->prepare("SELECT * FROM services ORDER BY category, name");
    $stmt->execute();
    return $stmt->get_result();
}

// Get services by category
function getServicesByCategory($conn, $category) {
    $stmt = $conn->prepare("SELECT * FROM services WHERE category = ? ORDER BY name");
    $stmt->bind_param("s", $category);
    $stmt->execute();
    return $stmt->get_result();
}

// Get service by ID
function getServiceById($conn, $service_id) {
    $stmt = $conn->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Add service
function addService($conn, $category, $name, $price, $description) {
    $stmt = $conn->prepare("INSERT INTO services (category, name, price, description) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $category, $name, $price, $description);
    return $stmt->execute();
}

// Update service
function updateService($conn, $service_id, $category, $name, $price, $description) {
    $stmt = $conn->prepare("UPDATE services SET category = ?, name = ?, price = ?, description = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $category, $name, $price, $description, $service_id);
    return $stmt->execute();
}

// Delete service
function deleteService($conn, $service_id) {
    $stmt = $conn->prepare("DELETE FROM services WHERE id = ?");
    $stmt->bind_param("i", $service_id);
    return $stmt->execute();
}

// ============================================
// 5. STATISTICS (ADMIN DASHBOARD)
// ============================================

// Get total users count
function getTotalUsers($conn) {
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM users WHERE is_admin = 0");
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc()['count'];
}

// Get total orders count
function getTotalOrders($conn) {
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM orders");
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc()['count'];
}

// Get total revenue
function getTotalRevenue($conn) {
    $stmt = $conn->prepare("SELECT SUM(price) as total FROM orders WHERE payment_verified = 1");
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['total'] ?? 0;
}

// Get orders by category count
function getOrderCountByCategory($conn, $category) {
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM orders WHERE category = ?");
    $stmt->bind_param("s", $category);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc()['count'];
}

// Get recent orders
function getRecentOrders($conn, $limit = 10) {
    $stmt = $conn->prepare("SELECT o.*, u.name as user_name, u.email 
                            FROM orders o 
                            JOIN users u ON o.user_id = u.id 
                            ORDER BY o.created_at DESC 
                            LIMIT ?");
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    return $stmt->get_result();
}

// Get user statistics
function getUserStats($conn, $user_id) {
    $stmt = $conn->prepare("SELECT 
                            COUNT(*) as order_count,
                            SUM(price) as total_spent,
                            MAX(created_at) as last_order
                            FROM orders 
                            WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// ============================================
// 6. USAGE EXAMPLES
// ============================================

/*
// Example 1: Login
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = loginUser($conn, $_POST['email'], $_POST['password']);
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['is_admin'] = $user['is_admin'];
        
        if ($user['is_admin'] == 1) {
            header('Location: admin/dashboard.php');
        } else {
            header('Location: dashboard.php');
        }
        exit;
    }
}

// Example 2: Get user orders
$orders = getUserOrders($conn, $_SESSION['user_id']);
while ($order = $orders->fetch_assoc()) {
    echo htmlspecialchars($order['service_name']);
}

// Example 3: Admin dashboard stats
$total_users = getTotalUsers($conn);
$total_orders = getTotalOrders($conn);
$total_revenue = getTotalRevenue($conn);

// Example 4: Filter orders
$student_orders = getOrdersByCategory($conn, 'Student');
$pending_orders = getOrdersByStatus($conn, 'pending');

// Example 5: Update order
updateOrderStatus($conn, $order_id, 'in_progress');
verifyPayment($conn, $order_id);
*/

?>
