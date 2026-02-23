<?php
session_start();
require_once '../config/database.php';
require_once 'includes/auth_check.php';

$type = $_GET['type'] ?? 'users';

if ($type == 'users') {
    $result = $conn->query("SELECT u.id, u.name, u.email, u.phone, u.created_at, 
                            COUNT(o.id) as order_count, COALESCE(SUM(o.price), 0) as total_spent 
                            FROM users u 
                            LEFT JOIN orders o ON u.id = o.user_id 
                            WHERE u.is_admin = 0
                            GROUP BY u.id");
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="users_' . date('Y-m-d') . '.csv"');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Name', 'Email', 'Phone', 'Orders', 'Total Spent', 'Joined Date']);
    
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $row['id'],
            $row['name'],
            $row['email'],
            $row['phone'],
            $row['order_count'],
            '₹' . number_format($row['total_spent'], 2),
            date('Y-m-d H:i:s', strtotime($row['created_at']))
        ]);
    }
    fclose($output);
    
} elseif ($type == 'orders') {
    $result = $conn->query("SELECT o.id, u.name, u.email, o.category, o.service_name, o.price, 
                            o.status, o.payment_verified, o.created_at 
                            FROM orders o 
                            JOIN users u ON o.user_id = u.id 
                            ORDER BY o.created_at DESC");
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="orders_' . date('Y-m-d') . '.csv"');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Order ID', 'Customer', 'Email', 'Category', 'Service', 'Price', 'Status', 'Payment Verified', 'Date']);
    
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $row['id'],
            $row['name'],
            $row['email'],
            $row['category'],
            $row['service_name'],
            '₹' . $row['price'],
            ucfirst(str_replace('_', ' ', $row['status'])),
            $row['payment_verified'] ? 'Yes' : 'No',
            date('Y-m-d H:i:s', strtotime($row['created_at']))
        ]);
    }
    fclose($output);
}
exit;
?>
