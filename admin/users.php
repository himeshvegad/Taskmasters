<?php
session_start();
require_once '../config/database.php';
require_once 'includes/auth_check.php';

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM orders WHERE user_id = $id");
    $conn->query("DELETE FROM users WHERE id = $id");
    header('Location: users.php');
    exit;
}

// Search
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$where = $search ? "WHERE (name LIKE '%$search%' OR email LIKE '%$search%') AND is_admin = 0" : "WHERE is_admin = 0";

$users = $conn->query("SELECT u.*, COUNT(o.id) as order_count, COALESCE(SUM(o.price), 0) as total_spent 
                       FROM users u 
                       LEFT JOIN orders o ON u.id = o.user_id 
                       $where
                       GROUP BY u.id 
                       ORDER BY u.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - TaskMasters Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <?php include 'includes/sidebar.php'; ?>
    
    <div class="ml-64 p-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">User Management</h1>
            <a href="export.php?type=users" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                <i class="fas fa-download mr-2"></i>Export CSV
            </a>
        </div>

        <!-- Search -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <form method="GET" class="flex gap-4">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" 
                       placeholder="Search by name or email..." 
                       class="flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600">
                <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
                <?php if($search): ?>
                <a href="users.php" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">Clear</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Orders</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Spent</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Joined</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php while($user = $users->fetch_assoc()): ?>
                    <tr>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($user['id']); ?></td>
                        <td class="px-6 py-4 font-medium"><?php echo htmlspecialchars($user['name']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($user['email']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($user['phone']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($user['order_count']); ?></td>
                        <td class="px-6 py-4 font-bold">₹<?php echo number_format($user['total_spent'], 2); ?></td>
                        <td class="px-6 py-4"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                        <td class="px-6 py-4">
                            <a href="user_orders.php?id=<?php echo htmlspecialchars($user['id']); ?>" class="text-blue-600 hover:text-blue-800 mr-3">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="?delete=<?php echo htmlspecialchars($user['id']); ?>" 
                               onclick="return confirm('Delete this user and all their orders?')" 
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
