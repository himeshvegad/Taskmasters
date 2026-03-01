<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

$conn = getDBConnection();

// Get statistics
$stats_query = "SELECT 
    COUNT(*) as total_orders,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_orders,
    SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) as paid_orders,
    SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress_orders,
    SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered_orders,
    SUM(final_price) as total_revenue
FROM guest_orders";
$stats = $conn->query($stats_query)->fetch_assoc();

// Get recent orders
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$where = $filter !== 'all' ? "WHERE status = '$filter'" : '';
$orders_query = "SELECT * FROM guest_orders $where ORDER BY created_at DESC";
$orders = $conn->query($orders_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - TaskMasters</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <nav class="bg-gradient-to-r from-purple-600 to-purple-800 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold">TaskMasters Admin</h1>
            <a href="logout.php" class="bg-white text-purple-600 px-4 py-2 rounded-lg hover:bg-gray-100">
                <i class="fas fa-sign-out-alt mr-2"></i>Logout
            </a>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Orders</p>
                        <p class="text-3xl font-bold text-purple-600"><?php echo $stats['total_orders']; ?></p>
                    </div>
                    <i class="fas fa-shopping-cart text-4xl text-purple-200"></i>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Pending Payment</p>
                        <p class="text-3xl font-bold text-yellow-600"><?php echo $stats['paid_orders']; ?></p>
                    </div>
                    <i class="fas fa-clock text-4xl text-yellow-200"></i>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">In Progress</p>
                        <p class="text-3xl font-bold text-blue-600"><?php echo $stats['in_progress_orders']; ?></p>
                    </div>
                    <i class="fas fa-spinner text-4xl text-blue-200"></i>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Delivered</p>
                        <p class="text-3xl font-bold text-green-600"><?php echo $stats['delivered_orders']; ?></p>
                    </div>
                    <i class="fas fa-check-circle text-4xl text-green-200"></i>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow p-4 mb-6">
            <div class="flex gap-2">
                <a href="?filter=all" class="px-4 py-2 rounded-lg <?php echo $filter === 'all' ? 'bg-purple-600 text-white' : 'bg-gray-100'; ?>">All</a>
                <a href="?filter=pending" class="px-4 py-2 rounded-lg <?php echo $filter === 'pending' ? 'bg-purple-600 text-white' : 'bg-gray-100'; ?>">Pending</a>
                <a href="?filter=paid" class="px-4 py-2 rounded-lg <?php echo $filter === 'paid' ? 'bg-purple-600 text-white' : 'bg-gray-100'; ?>">Paid</a>
                <a href="?filter=in_progress" class="px-4 py-2 rounded-lg <?php echo $filter === 'in_progress' ? 'bg-purple-600 text-white' : 'bg-gray-100'; ?>">In Progress</a>
                <a href="?filter=delivered" class="px-4 py-2 rounded-lg <?php echo $filter === 'delivered' ? 'bg-purple-600 text-white' : 'bg-gray-100'; ?>">Delivered</a>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Service</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php while ($order = $orders->fetch_assoc()): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium"><?php echo $order['order_id']; ?></td>
                        <td class="px-6 py-4 text-sm">
                            <div><?php echo htmlspecialchars($order['customer_name']); ?></div>
                            <div class="text-gray-500"><?php echo htmlspecialchars($order['customer_email']); ?></div>
                        </td>
                        <td class="px-6 py-4 text-sm"><?php echo htmlspecialchars($order['service_name']); ?></td>
                        <td class="px-6 py-4 text-sm font-semibold">₹<?php echo number_format($order['final_price'], 2); ?></td>
                        <td class="px-6 py-4 text-sm">
                            <?php
                            $status_colors = [
                                'pending' => 'bg-gray-100 text-gray-800',
                                'paid' => 'bg-yellow-100 text-yellow-800',
                                'in_progress' => 'bg-blue-100 text-blue-800',
                                'delivered' => 'bg-green-100 text-green-800'
                            ];
                            $color = $status_colors[$order['status']] ?? 'bg-gray-100 text-gray-800';
                            ?>
                            <span class="px-2 py-1 rounded-full text-xs font-medium <?php echo $color; ?>">
                                <?php echo ucfirst(str_replace('_', ' ', $order['status'])); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                        <td class="px-6 py-4 text-sm">
                            <a href="order_details.php?id=<?php echo $order['id']; ?>" class="text-purple-600 hover:text-purple-800 font-medium">
                                View Details
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
