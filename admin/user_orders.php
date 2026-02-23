<?php
session_start();
require_once '../config/database.php';
require_once 'includes/auth_check.php';

$user_id = (int)$_GET['id'];
$user = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();
$orders = $conn->query("SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Orders - TaskMasters Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <?php include 'includes/sidebar.php'; ?>
    
    <div class="ml-64 p-8">
        <div class="mb-6">
            <a href="users.php" class="text-purple-600 hover:text-purple-800">
                <i class="fas fa-arrow-left mr-2"></i>Back to Users
            </a>
        </div>

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-2xl font-bold mb-4">User Details</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600">Name</p>
                    <p class="font-semibold"><?php echo htmlspecialchars($user['name']); ?></p>
                </div>
                <div>
                    <p class="text-gray-600">Email</p>
                    <p class="font-semibold"><?php echo htmlspecialchars($user['email']); ?></p>
                </div>
                <div>
                    <p class="text-gray-600">Phone</p>
                    <p class="font-semibold"><?php echo htmlspecialchars($user['phone']); ?></p>
                </div>
                <div>
                    <p class="text-gray-600">Joined</p>
                    <p class="font-semibold"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h3 class="text-xl font-bold">Order History</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Service</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php while($order = $orders->fetch_assoc()): ?>
                        <tr>
                            <td class="px-6 py-4">#<?php echo htmlspecialchars($order['id']); ?></td>
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
                            <td class="px-6 py-4"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                            <td class="px-6 py-4">
                                <a href="order_details.php?id=<?php echo htmlspecialchars($order['id']); ?>" class="text-blue-600 hover:text-blue-800">
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
</body>
</html>
