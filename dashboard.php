<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$orders = $conn->query("SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - TaskMasters</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="index.php" class="text-2xl font-bold text-purple-600">TaskMasters</a>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                    <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold">My Orders</h2>
            <a href="services.php" class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700">
                <i class="fas fa-plus mr-2"></i>New Order
            </a>
        </div>

        <?php if(isset($_GET['payment_submitted'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                Payment submitted successfully! We will verify and start working on your order.
            </div>
        <?php endif; ?>

        <div class="space-y-4">
            <?php if($orders->num_rows > 0): ?>
                <?php while($order = $orders->fetch_assoc()): ?>
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold mb-2"><?php echo htmlspecialchars($order['service_name']); ?></h3>
                                <p class="text-gray-600 mb-2"><?php echo htmlspecialchars($order['title']); ?></p>
                                <p class="text-sm text-gray-500">Order ID: #<?php echo htmlspecialchars($order['id']); ?></p>
                                <p class="text-sm text-gray-500">Deadline: <?php echo htmlspecialchars(date('d M Y', strtotime($order['deadline']))); ?></p>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-purple-600 mb-2">₹<?php echo htmlspecialchars($order['price']); ?></p>
                                <?php
                                $status_colors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'in_progress' => 'bg-blue-100 text-blue-800',
                                    'delivered' => 'bg-green-100 text-green-800'
                                ];
                                $status_class = $status_colors[$order['status']];
                                ?>
                                <span class="px-4 py-2 rounded-full text-sm font-semibold <?php echo $status_class; ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $order['status'])); ?>
                                </span>
                            </div>
                        </div>

                        <?php if($order['status'] == 'delivered' && $order['delivered_file']): ?>
                            <div class="mt-4 pt-4 border-t">
                                <a href="<?php echo htmlspecialchars($order['delivered_file']); ?>" download 
                                   class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 inline-block">
                                    <i class="fas fa-download mr-2"></i>Download Delivered File
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if(!$order['payment_verified'] && !$order['payment_screenshot']): ?>
                            <div class="mt-4 pt-4 border-t">
                                <a href="checkout.php?order_id=<?php echo htmlspecialchars($order['id']); ?>" 
                                   class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 inline-block">
                                    Complete Payment
                                </a>
                            </div>
                        <?php elseif(!$order['payment_verified']): ?>
                            <div class="mt-4 pt-4 border-t">
                                <p class="text-sm text-orange-600"><i class="fas fa-clock mr-2"></i>Payment verification pending</p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="bg-white rounded-lg shadow-lg p-12 text-center">
                    <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                    <p class="text-xl text-gray-600 mb-4">No orders yet</p>
                    <a href="services.php" class="bg-purple-600 text-white px-8 py-3 rounded-lg hover:bg-purple-700 inline-block">
                        Browse Services
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
