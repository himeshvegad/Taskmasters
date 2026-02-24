<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../config/db.php';

$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$order = $conn->query("SELECT * FROM guest_orders WHERE id = $order_id")->fetch_assoc();

if (!$order) {
    header('Location: dashboard.php');
    exit;
}

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['verify_payment'])) {
        $conn->query("UPDATE guest_orders SET payment_status = 'verified' WHERE id = $order_id");
        header("Location: order_details.php?id=$order_id&success=payment_verified");
        exit;
    }
    
    if (isset($_POST['update_status'])) {
        $new_status = $_POST['order_status'];
        $conn->query("UPDATE guest_orders SET order_status = '$new_status' WHERE id = $order_id");
        header("Location: order_details.php?id=$order_id&success=status_updated");
        exit;
    }
    
    if (isset($_FILES['delivered_file'])) {
        $target_dir = "../uploads/delivered/";
        $file_path = $target_dir . time() . '_' . basename($_FILES['delivered_file']['name']);
        if (move_uploaded_file($_FILES['delivered_file']['tmp_name'], $file_path)) {
            $conn->query("UPDATE guest_orders SET delivered_file = '$file_path', order_status = 'completed' WHERE id = $order_id");
            header("Location: order_details.php?id=$order_id&success=file_uploaded");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { font-family: 'Inter', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    </style>
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <a href="dashboard.php" class="text-purple-600 hover:text-purple-700 font-semibold">
                <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
            </a>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto px-6 py-8">
        <?php if(isset($_GET['success'])): ?>
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                ✓ Action completed successfully
            </div>
        <?php endif; ?>

        <div class="grid md:grid-cols-3 gap-6">
            <!-- Order Details -->
            <div class="md:col-span-2 space-y-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Order Details</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-600">Order ID:</span>
                            <span class="font-mono font-semibold"><?php echo htmlspecialchars($order['order_id']); ?></span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-600">Service:</span>
                            <span class="font-semibold"><?php echo htmlspecialchars($order['service']); ?></span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-600">Amount:</span>
                            <span class="font-semibold text-purple-600">₹<?php echo number_format($order['final_price'], 2); ?></span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-600">Created:</span>
                            <span><?php echo date('d M Y, h:i A', strtotime($order['created_at'])); ?></span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Customer Information</h3>
                    <div class="space-y-2">
                        <p><span class="text-gray-600">Name:</span> <strong><?php echo htmlspecialchars($order['name']); ?></strong></p>
                        <p><span class="text-gray-600">Email:</span> <strong><?php echo htmlspecialchars($order['email']); ?></strong></p>
                        <p><span class="text-gray-600">Phone:</span> <strong><?php echo htmlspecialchars($order['phone']); ?></strong></p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Requirements</h3>
                    <p class="text-gray-700 whitespace-pre-wrap"><?php echo htmlspecialchars($order['requirements']); ?></p>
                    <?php if($order['file_path']): ?>
                        <a href="../<?php echo $order['file_path']; ?>" target="_blank" class="inline-block mt-4 text-purple-600 hover:text-purple-700">
                            <i class="fas fa-download mr-2"></i>Download Uploaded File
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Actions -->
            <div class="space-y-6">
                <!-- Payment Status -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Payment Status</h3>
                    <div class="mb-4">
                        <span class="px-3 py-1 text-sm font-semibold rounded-full 
                            <?php echo $order['payment_status'] == 'verified' ? 'bg-green-100 text-green-700' : 
                                      ($order['payment_status'] == 'paid' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700'); ?>">
                            <?php echo ucfirst($order['payment_status']); ?>
                        </span>
                    </div>
                    <?php if($order['payment_screenshot']): ?>
                        <img src="../<?php echo $order['payment_screenshot']; ?>" class="w-full rounded-lg mb-4">
                        <?php if($order['payment_status'] != 'verified'): ?>
                            <form method="POST">
                                <button type="submit" name="verify_payment" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
                                    Verify Payment
                                </button>
                            </form>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="text-sm text-gray-500">No payment screenshot uploaded yet</p>
                    <?php endif; ?>
                </div>

                <!-- Order Status -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Order Status</h3>
                    <form method="POST">
                        <select name="order_status" class="w-full px-4 py-2 border rounded-lg mb-4">
                            <option value="pending" <?php echo $order['order_status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="in_progress" <?php echo $order['order_status'] == 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                            <option value="completed" <?php echo $order['order_status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                        </select>
                        <button type="submit" name="update_status" class="w-full gradient-bg text-white py-2 rounded-lg hover:opacity-90">
                            Update Status
                        </button>
                    </form>
                </div>

                <!-- Upload Delivery -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Deliver Work</h3>
                    <?php if($order['delivered_file']): ?>
                        <p class="text-sm text-green-600 mb-2">✓ File delivered</p>
                        <a href="../<?php echo $order['delivered_file']; ?>" class="text-purple-600 text-sm">View File</a>
                    <?php else: ?>
                        <form method="POST" enctype="multipart/form-data">
                            <input type="file" name="delivered_file" required class="w-full px-4 py-2 border rounded-lg mb-4">
                            <button type="submit" class="w-full bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700">
                                Upload & Complete
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
