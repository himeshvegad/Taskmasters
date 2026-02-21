<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: ../login.php');
    exit;
}

$orders = $conn->query("SELECT o.*, u.name as user_name, u.email, u.phone 
                        FROM orders o 
                        JOIN users u ON o.user_id = u.id 
                        ORDER BY o.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - TaskMasters</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <nav class="bg-purple-800 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold">Admin Panel</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span>Admin: <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                    <a href="../logout.php" class="bg-red-500 px-4 py-2 rounded-lg hover:bg-red-600">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-12">
        <h2 class="text-3xl font-bold mb-8">All Orders</h2>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Service</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php while($order = $orders->fetch_assoc()): ?>
                        <tr>
                            <td class="px-6 py-4">#<?php echo htmlspecialchars($order['id']); ?></td>
                            <td class="px-6 py-4">
                                <div><?php echo htmlspecialchars($order['user_name']); ?></div>
                                <div class="text-sm text-gray-500"><?php echo htmlspecialchars($order['email']); ?></div>
                                <div class="text-sm text-gray-500"><?php echo htmlspecialchars($order['phone']); ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold"><?php echo htmlspecialchars($order['service_name']); ?></div>
                                <div class="text-sm text-gray-500"><?php echo htmlspecialchars($order['title']); ?></div>
                            </td>
                            <td class="px-6 py-4 font-bold">₹<?php echo htmlspecialchars($order['price']); ?></td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    <?php echo $order['status'] == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                              ($order['status'] == 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'); ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $order['status'])); ?>
                                </span>
                                <?php if(!$order['payment_verified']): ?>
                                    <span class="block mt-1 text-xs text-red-600">Payment Pending</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <a href="order_details.php?id=<?php echo htmlspecialchars($order['id']); ?>" 
                                   class="text-purple-600 hover:text-purple-800">
                                    <i class="fas fa-eye mr-1"></i>View
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
