<?php
session_start();
require_once '../config/database.php';
require_once 'includes/auth_check.php';

// Get statistics
$total_users = $conn->query("SELECT COUNT(*) as count FROM users WHERE is_admin = 0")->fetch_assoc()['count'];
$total_orders = $conn->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'];
$total_revenue = $conn->query("SELECT SUM(price) as total FROM orders WHERE payment_verified = 1")->fetch_assoc()['total'] ?? 0;

// Orders by category
$student_orders = $conn->query("SELECT COUNT(*) as count FROM orders WHERE category = 'Student'")->fetch_assoc()['count'];
$business_orders = $conn->query("SELECT COUNT(*) as count FROM orders WHERE category = 'Business'")->fetch_assoc()['count'];
$individual_orders = $conn->query("SELECT COUNT(*) as count FROM orders WHERE category = 'Individual'")->fetch_assoc()['count'];

// Recent orders
$recent_orders = $conn->query("SELECT o.*, u.name as user_name, u.email 
                               FROM orders o 
                               JOIN users u ON o.user_id = u.id 
                               ORDER BY o.created_at DESC LIMIT 10");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - TaskMasters Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <?php include 'includes/sidebar.php'; ?>
    
    <div class="ml-64 p-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Dashboard Overview</h1>
            <div class="text-gray-600">
                <i class="fas fa-user-shield mr-2"></i><?php echo htmlspecialchars($_SESSION['user_name']); ?>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Users</p>
                        <p class="text-3xl font-bold text-gray-800"><?php echo $total_users; ?></p>
                    </div>
                    <div class="bg-blue-100 p-4 rounded-full">
                        <i class="fas fa-users text-2xl text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Orders</p>
                        <p class="text-3xl font-bold text-gray-800"><?php echo $total_orders; ?></p>
                    </div>
                    <div class="bg-green-100 p-4 rounded-full">
                        <i class="fas fa-shopping-cart text-2xl text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Revenue</p>
                        <p class="text-3xl font-bold text-gray-800">₹<?php echo number_format($total_revenue, 2); ?></p>
                    </div>
                    <div class="bg-purple-100 p-4 rounded-full">
                        <i class="fas fa-rupee-sign text-2xl text-purple-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Active Services</p>
                        <p class="text-3xl font-bold text-gray-800">13</p>
                    </div>
                    <div class="bg-yellow-100 p-4 rounded-full">
                        <i class="fas fa-briefcase text-2xl text-yellow-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold mb-4">Orders by Category</h3>
                <canvas id="categoryChart"></canvas>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold mb-4">Category Distribution</h3>
                <canvas id="pieChart"></canvas>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h3 class="text-lg font-bold">Recent Orders</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Service</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php while($order = $recent_orders->fetch_assoc()): ?>
                        <tr>
                            <td class="px-6 py-4">#<?php echo htmlspecialchars($order['id']); ?></td>
                            <td class="px-6 py-4">
                                <div class="font-medium"><?php echo htmlspecialchars($order['user_name']); ?></div>
                                <div class="text-sm text-gray-500"><?php echo htmlspecialchars($order['email']); ?></div>
                            </td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($order['service_name']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($order['category']); ?></td>
                            <td class="px-6 py-4 font-bold">₹<?php echo htmlspecialchars($order['price']); ?></td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    <?php echo $order['status'] == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                              ($order['status'] == 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'); ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', htmlspecialchars($order['status']))); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="order_details.php?id=<?php echo htmlspecialchars($order['id']); ?>" class="text-purple-600 hover:text-purple-800">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Category Bar Chart
        const ctx1 = document.getElementById('categoryChart').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['Student', 'Business', 'Individual'],
                datasets: [{
                    label: 'Orders',
                    data: [<?php echo $student_orders; ?>, <?php echo $business_orders; ?>, <?php echo $individual_orders; ?>],
                    backgroundColor: ['#3B82F6', '#10B981', '#F59E0B']
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } }
            }
        });

        // Pie Chart
        const ctx2 = document.getElementById('pieChart').getContext('2d');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Student', 'Business', 'Individual'],
                datasets: [{
                    data: [<?php echo $student_orders; ?>, <?php echo $business_orders; ?>, <?php echo $individual_orders; ?>],
                    backgroundColor: ['#3B82F6', '#10B981', '#F59E0B']
                }]
            },
            options: { responsive: true }
        });
    </script>
</body>
</html>
