<?php
session_start();
require_once '../config/database.php';
require_once 'includes/auth_check.php';

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM orders WHERE id = $id");
    header('Location: orders.php');
    exit;
}

// Filter
$category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';
$status = isset($_GET['status']) ? $conn->real_escape_string($_GET['status']) : '';

$where = [];
if ($category) $where[] = "o.category = '$category'";
if ($status) $where[] = "o.status = '$status'";
$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$orders = $conn->query("SELECT o.*, u.name as user_name, u.email, u.phone 
                        FROM orders o 
                        JOIN users u ON o.user_id = u.id 
                        $where_sql
                        ORDER BY o.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - TaskMasters Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <?php include 'includes/sidebar.php'; ?>
    
    <div class="ml-64 p-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Order Management</h1>
            <a href="export.php?type=orders" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                <i class="fas fa-download mr-2"></i>Export CSV
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <form method="GET" class="flex gap-4">
                <select name="category" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600">
                    <option value="">All Categories</option>
                    <option value="Student" <?php echo $category == 'Student' ? 'selected' : ''; ?>>Student</option>
                    <option value="Business" <?php echo $category == 'Business' ? 'selected' : ''; ?>>Business</option>
                    <option value="Individual" <?php echo $category == 'Individual' ? 'selected' : ''; ?>>Individual</option>
                </select>
                <select name="status" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600">
                    <option value="">All Status</option>
                    <option value="pending" <?php echo $status == 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="in_progress" <?php echo $status == 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                    <option value="delivered" <?php echo $status == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                </select>
                <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                <?php if($category || $status): ?>
                <a href="orders.php" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">Clear</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Orders Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Service</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php while($order = $orders->fetch_assoc()): ?>
                    <tr>
                        <td class="px-6 py-4">#<?php echo htmlspecialchars($order['id']); ?></td>
                        <td class="px-6 py-4">
                            <div class="font-medium"><?php echo htmlspecialchars($order['user_name']); ?></div>
                            <div class="text-sm text-gray-500"><?php echo htmlspecialchars($order['email']); ?></div>
                        </td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($order['service_name']); ?></td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                <?php echo $order['category'] == 'Student' ? 'bg-blue-100 text-blue-800' : 
                                          ($order['category'] == 'Business' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'); ?>">
                                <?php echo htmlspecialchars($order['category']); ?>
                            </span>
                        </td>
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
                            <a href="order_details.php?id=<?php echo htmlspecialchars($order['id']); ?>" class="text-blue-600 hover:text-blue-800 mr-3">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="?delete=<?php echo htmlspecialchars($order['id']); ?>" 
                               onclick="return confirm('Delete this order?')" 
                               class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
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
