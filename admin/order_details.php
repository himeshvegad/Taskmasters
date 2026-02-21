<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: ../login.php');
    exit;
}

$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$order = $conn->query("SELECT o.*, u.name as user_name, u.email, u.phone 
                       FROM orders o 
                       JOIN users u ON o.user_id = u.id 
                       WHERE o.id = $order_id")->fetch_assoc();

if (!$order) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['verify_payment'])) {
        $conn->query("UPDATE orders SET payment_verified = 1 WHERE id = $order_id");
        header("Location: order_details.php?id=$order_id&success=payment_verified");
        exit;
    }
    
    if (isset($_POST['update_status'])) {
        $status = $conn->real_escape_string($_POST['status']);
        $conn->query("UPDATE orders SET status = '$status' WHERE id = $order_id");
        header("Location: order_details.php?id=$order_id&success=status_updated");
        exit;
    }
    
    if (isset($_FILES['delivered_file'])) {
        $target_dir = "../uploads/delivered/";
        $file_path = $target_dir . time() . '_' . basename($_FILES['delivered_file']['name']);
        if (move_uploaded_file($_FILES['delivered_file']['tmp_name'], $file_path)) {
            $conn->query("UPDATE orders SET delivered_file = '$file_path', status = 'delivered' WHERE id = $order_id");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <nav class="bg-purple-800 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="dashboard.php" class="text-2xl font-bold">Admin Panel</a>
                </div>
                <div class="flex items-center">
                    <a href="../logout.php" class="bg-red-500 px-4 py-2 rounded-lg hover:bg-red-600">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto px-4 py-12">
        <a href="dashboard.php" class="text-purple-600 hover:text-purple-800 mb-4 inline-block">
            <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
        </a>

        <?php if(isset($_GET['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                Action completed successfully!
            </div>
        <?php endif; ?>

        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-2xl font-bold mb-4">Order Information</h3>
                <div class="space-y-3">
                    <p><span class="font-semibold">Order ID:</span> #<?php echo htmlspecialchars($order['id']); ?></p>
                    <p><span class="font-semibold">Service:</span> <?php echo htmlspecialchars($order['service_name']); ?></p>
                    <p><span class="font-semibold">Category:</span> <?php echo htmlspecialchars(ucfirst($order['category'])); ?></p>
                    <p><span class="font-semibold">Title:</span> <?php echo htmlspecialchars($order['title']); ?></p>
                    <p><span class="font-semibold">Amount:</span> ₹<?php echo htmlspecialchars($order['price']); ?></p>
                    <p><span class="font-semibold">Deadline:</span> <?php echo htmlspecialchars(date('d M Y', strtotime($order['deadline']))); ?></p>
                    <p><span class="font-semibold">Created:</span> <?php echo htmlspecialchars(date('d M Y H:i', strtotime($order['created_at']))); ?></p>
                </div>

                <div class="mt-6 pt-6 border-t">
                    <h4 class="font-semibold mb-2">Work Summary:</h4>
                    <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($order['work_summary'])); ?></p>
                </div>

                <?php if($order['special_instructions']): ?>
                    <div class="mt-4">
                        <h4 class="font-semibold mb-2">Special Instructions:</h4>
                        <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($order['special_instructions'])); ?></p>
                    </div>
                <?php endif; ?>

                <?php if($order['file_path']): ?>
                    <div class="mt-4">
                        <a href="../<?php echo htmlspecialchars($order['file_path']); ?>" download 
                           class="text-purple-600 hover:text-purple-800">
                            <i class="fas fa-download mr-2"></i>Download Uploaded File
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-2xl font-bold mb-4">Customer Details</h3>
                    <div class="space-y-2">
                        <p><span class="font-semibold">Name:</span> <?php echo htmlspecialchars($order['user_name']); ?></p>
                        <p><span class="font-semibold">Email:</span> <?php echo htmlspecialchars($order['email']); ?></p>
                        <p><span class="font-semibold">Phone:</span> <?php echo htmlspecialchars($order['phone']); ?></p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-2xl font-bold mb-4">Payment Status</h3>
                    <?php if($order['payment_screenshot']): ?>
                        <img src="../<?php echo htmlspecialchars($order['payment_screenshot']); ?>" alt="Payment Screenshot" class="w-full rounded mb-4">
                        <?php if(!$order['payment_verified']): ?>
                            <form method="POST">
                                <button type="submit" name="verify_payment" 
                                        class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
                                    Verify Payment
                                </button>
                            </form>
                        <?php else: ?>
                            <p class="text-green-600 font-semibold"><i class="fas fa-check-circle mr-2"></i>Payment Verified</p>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="text-red-600">No payment screenshot uploaded yet</p>
                    <?php endif; ?>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-2xl font-bold mb-4">Update Status</h3>
                    <form method="POST" class="space-y-4">
                        <select name="status" class="w-full px-4 py-2 border rounded-lg">
                            <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="in_progress" <?php echo $order['status'] == 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                            <option value="delivered" <?php echo $order['status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                        </select>
                        <button type="submit" name="update_status" 
                                class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                            Update Status
                        </button>
                    </form>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-2xl font-bold mb-4">Upload Delivered File</h3>
                    <form method="POST" enctype="multipart/form-data" class="space-y-4">
                        <input type="file" name="delivered_file" required class="w-full px-4 py-2 border rounded-lg">
                        <button type="submit" class="w-full bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700">
                            Upload File
                        </button>
                    </form>
                    <?php if($order['delivered_file']): ?>
                        <p class="mt-4 text-green-600"><i class="fas fa-check-circle mr-2"></i>File already uploaded</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
