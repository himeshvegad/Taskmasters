<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../config/db.php';

$filter_status = isset($_GET['status']) ? $_GET['status'] : 'all';
$filter_payment = isset($_GET['payment']) ? $_GET['payment'] : 'all';

$query = "SELECT * FROM guest_orders WHERE 1=1";
if ($filter_status != 'all') $query .= " AND order_status = '$filter_status'";
if ($filter_payment != 'all') $query .= " AND payment_status = '$filter_payment'";
$query .= " ORDER BY created_at DESC";

$orders = $conn->query($query);
$stats = $conn->query("SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN payment_status = 'verified' THEN 1 ELSE 0 END) as verified,
    SUM(CASE WHEN order_status = 'completed' THEN 1 ELSE 0 END) as completed,
    SUM(final_price) as revenue
    FROM guest_orders")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - TaskMasters</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { font-family: 'Inter', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 gradient-bg rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-xl">T</span>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">Admin Dashboard</h1>
                        <p class="text-sm text-gray-600">Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></p>
                    </div>
                </div>
                <a href="logout.php" class="text-red-600 hover:text-red-700 font-semibold">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-6 py-8">
        <!-- Stats -->
        <div class="grid md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Orders</p>
                        <p class="text-3xl font-bold text-gray-900"><?php echo $stats['total']; ?></p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Verified</p>
                        <p class="text-3xl font-bold text-green-600"><?php echo $stats['verified']; ?></p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Completed</p>
                        <p class="text-3xl font-bold text-purple-600"><?php echo $stats['completed']; ?></p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-double text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Revenue</p>
                        <p class="text-3xl font-bold text-gray-900">₹<?php echo number_format($stats['revenue'], 0); ?></p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-rupee-sign text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex flex-wrap gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Order Status</label>
                    <select onchange="window.location.href='?status='+this.value+'&payment=<?php echo $filter_payment; ?>'" class="px-4 py-2 border rounded-lg">
                        <option value="all" <?php echo $filter_status == 'all' ? 'selected' : ''; ?>>All</option>
                        <option value="pending" <?php echo $filter_status == 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="in_progress" <?php echo $filter_status == 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                        <option value="completed" <?php echo $filter_status == 'completed' ? 'selected' : ''; ?>>Completed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Payment Status</label>
                    <select onchange="window.location.href='?status=<?php echo $filter_status; ?>&payment='+this.value" class="px-4 py-2 border rounded-lg">
                        <option value="all" <?php echo $filter_payment == 'all' ? 'selected' : ''; ?>>All</option>
                        <option value="pending" <?php echo $filter_payment == 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="paid" <?php echo $filter_payment == 'paid' ? 'selected' : ''; ?>>Paid</option>
                        <option value="verified" <?php echo $filter_payment == 'verified' ? 'selected' : ''; ?>>Verified</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Order ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Service</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Payment</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <?php while($order = $orders->fetch_assoc()): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-mono"><?php echo htmlspecialchars($order['order_id']); ?></td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold"><?php echo htmlspecialchars($order['name']); ?></div>
                            <div class="text-xs text-gray-500"><?php echo htmlspecialchars($order['email']); ?></div>
                        </td>
                        <td class="px-6 py-4 text-sm"><?php echo htmlspecialchars($order['service']); ?></td>
                        <td class="px-6 py-4 text-sm font-semibold">₹<?php echo number_format($order['final_price'], 2); ?></td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                <?php echo $order['payment_status'] == 'verified' ? 'bg-green-100 text-green-700' : 
                                          ($order['payment_status'] == 'paid' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700'); ?>">
                                <?php echo ucfirst($order['payment_status']); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                <?php echo $order['order_status'] == 'completed' ? 'bg-purple-100 text-purple-700' : 
                                          ($order['order_status'] == 'in_progress' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700'); ?>">
                                <?php echo ucfirst(str_replace('_', ' ', $order['order_status'])); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="order_details.php?id=<?php echo $order['id']; ?>" class="text-purple-600 hover:text-purple-700 font-semibold text-sm">
                                View Details →
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
