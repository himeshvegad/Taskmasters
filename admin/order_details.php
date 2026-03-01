<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

$conn = getDBConnection();
$order_id = $_GET['id'] ?? 0;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['verify_payment'])) {
        $conn->query("UPDATE guest_orders SET status = 'in_progress' WHERE id = $order_id");
        header('Location: order_details.php?id=' . $order_id);
        exit;
    }
    
    if (isset($_POST['upload_delivery'])) {
        $file = $_FILES['delivered_file'];
        $upload_dir = '../uploads/delivered/';
        $file_name = time() . '_' . basename($file['name']);
        $target_path = $upload_dir . $file_name;
        
        if (move_uploaded_file($file['tmp_name'], $target_path)) {
            $conn->query("UPDATE guest_orders SET delivered_file = '$file_name', status = 'delivered' WHERE id = $order_id");
            header('Location: order_details.php?id=' . $order_id);
            exit;
        }
    }
}

// Get order details
$order = $conn->query("SELECT * FROM guest_orders WHERE id = $order_id")->fetch_assoc();
if (!$order) {
    die('Order not found');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - TaskMasters Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <nav class="bg-gradient-to-r from-purple-600 to-purple-800 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <a href="dashboard.php" class="hover:text-gray-200">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
                <h1 class="text-2xl font-bold">Order Details</h1>
            </div>
            <a href="logout.php" class="bg-white text-purple-600 px-4 py-2 rounded-lg hover:bg-gray-100">
                <i class="fas fa-sign-out-alt mr-2"></i>Logout
            </a>
        </div>
    </nav>

    <div class="max-w-5xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Order Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Info -->
                <div class="bg-white rounded-xl shadow p-6">
                    <h2 class="text-xl font-bold mb-4">Order Information</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-500 text-sm">Order ID</p>
                            <p class="font-semibold"><?php echo $order['order_id']; ?></p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Status</p>
                            <p class="font-semibold capitalize"><?php echo str_replace('_', ' ', $order['status']); ?></p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Service</p>
                            <p class="font-semibold"><?php echo htmlspecialchars($order['service_name']); ?></p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Category</p>
                            <p class="font-semibold capitalize"><?php echo $order['category']; ?></p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Original Price</p>
                            <p class="font-semibold">₹<?php echo number_format($order['original_price'], 2); ?></p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Final Price (20% off)</p>
                            <p class="font-semibold text-green-600">₹<?php echo number_format($order['final_price'], 2); ?></p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Deadline</p>
                            <p class="font-semibold"><?php echo date('M d, Y', strtotime($order['deadline'])); ?></p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Order Date</p>
                            <p class="font-semibold"><?php echo date('M d, Y H:i', strtotime($order['created_at'])); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Customer Info -->
                <div class="bg-white rounded-xl shadow p-6">
                    <h2 class="text-xl font-bold mb-4">Customer Information</h2>
                    <div class="space-y-3">
                        <div>
                            <p class="text-gray-500 text-sm">Name</p>
                            <p class="font-semibold"><?php echo htmlspecialchars($order['customer_name']); ?></p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Email</p>
                            <p class="font-semibold"><?php echo htmlspecialchars($order['customer_email']); ?></p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Phone</p>
                            <p class="font-semibold"><?php echo htmlspecialchars($order['customer_phone']); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Work Details -->
                <div class="bg-white rounded-xl shadow p-6">
                    <h2 class="text-xl font-bold mb-4">Work Details</h2>
                    <div class="space-y-3">
                        <div>
                            <p class="text-gray-500 text-sm">Title</p>
                            <p class="font-semibold"><?php echo htmlspecialchars($order['work_title']); ?></p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Description</p>
                            <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($order['work_description'])); ?></p>
                        </div>
                        <?php if ($order['special_instructions']): ?>
                        <div>
                            <p class="text-gray-500 text-sm">Special Instructions</p>
                            <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($order['special_instructions'])); ?></p>
                        </div>
                        <?php endif; ?>
                        <?php if ($order['requirement_file']): ?>
                        <div>
                            <p class="text-gray-500 text-sm">Requirement File</p>
                            <a href="../uploads/requirements/<?php echo $order['requirement_file']; ?>" 
                               class="text-purple-600 hover:text-purple-800 font-medium" download>
                                <i class="fas fa-download mr-2"></i>Download File
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="space-y-6">
                <!-- Payment Verification -->
                <?php if ($order['status'] === 'paid'): ?>
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                    <h3 class="font-bold text-yellow-800 mb-4">Payment Verification</h3>
                    <p class="text-sm text-yellow-700 mb-4">Customer has confirmed payment. Verify and start work.</p>
                    <form method="POST">
                        <button type="submit" name="verify_payment" 
                                class="w-full bg-yellow-600 text-white py-2 rounded-lg hover:bg-yellow-700">
                            Verify & Start Work
                        </button>
                    </form>
                </div>
                <?php endif; ?>

                <!-- Upload Delivery -->
                <?php if ($order['status'] === 'in_progress'): ?>
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                    <h3 class="font-bold text-blue-800 mb-4">Upload Delivery</h3>
                    <form method="POST" enctype="multipart/form-data">
                        <input type="file" name="delivered_file" required 
                               class="w-full mb-4 text-sm border rounded-lg p-2">
                        <button type="submit" name="upload_delivery" 
                                class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                            Upload & Mark Delivered
                        </button>
                    </form>
                </div>
                <?php endif; ?>

                <!-- Delivered -->
                <?php if ($order['status'] === 'delivered' && $order['delivered_file']): ?>
                <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                    <h3 class="font-bold text-green-800 mb-4">Delivered File</h3>
                    <a href="../uploads/delivered/<?php echo $order['delivered_file']; ?>" 
                       class="block w-full bg-green-600 text-white text-center py-2 rounded-lg hover:bg-green-700" download>
                        <i class="fas fa-download mr-2"></i>Download File
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
