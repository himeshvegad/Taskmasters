<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$order = $conn->query("SELECT * FROM orders WHERE id = $order_id AND user_id = {$_SESSION['user_id']}")->fetch_assoc();

if (!$order) {
    header('Location: dashboard.php');
    exit;
}

// Check if user is eligible for first order discount
$user = $conn->query("SELECT first_order_discount FROM users WHERE id = {$_SESSION['user_id']}")->fetch_assoc();
$is_first_order = isset($user['first_order_discount']) && $user['first_order_discount'] == 1;

// Calculate discount
$original_price = floatval($order['price']);
$discount_amount = $is_first_order ? ($original_price * 0.20) : 0;
$final_price = $original_price - $discount_amount;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['payment_screenshot'])) {
    $target_dir = "uploads/payments/";
    $file_path = $target_dir . time() . '_' . basename($_FILES['payment_screenshot']['name']);
    
    if (move_uploaded_file($_FILES['payment_screenshot']['tmp_name'], $file_path)) {
        // Update order with payment screenshot and mark discount as used
        $conn->query("UPDATE orders SET payment_screenshot = '$file_path' WHERE id = $order_id");
        
        // Mark first order discount as used
        if ($is_first_order) {
            $conn->query("UPDATE users SET first_order_discount = 0 WHERE id = {$_SESSION['user_id']}");
        }
        
        header('Location: dashboard.php?payment_submitted=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - TaskMasters</title>
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
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 py-12">
        <div class="bg-white rounded-lg shadow-xl p-8">
            <h2 class="text-3xl font-bold mb-6 text-center">Complete Payment</h2>
            
            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">Order Summary</h3>
                    <div class="bg-gray-50 p-4 rounded-lg space-y-2">
                        <p><span class="font-semibold">Service:</span> <?php echo htmlspecialchars($order['service_name']); ?></p>
                        <p><span class="font-semibold">Title:</span> <?php echo htmlspecialchars($order['title']); ?></p>
                        <p><span class="font-semibold">Deadline:</span> <?php echo htmlspecialchars(date('d M Y', strtotime($order['deadline']))); ?></p>
                        <hr class="my-4">
                        
                        <?php if ($is_first_order): ?>
                            <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-3">
                                <p class="text-green-700 font-bold text-sm flex items-center">
                                    <i class="fas fa-gift mr-2"></i> 20% OFF - First Order Only!
                                </p>
                            </div>
                            <p class="text-gray-500">
                                <span class="font-semibold">Original Price:</span> 
                                <span class="line-through">₹<?php echo number_format($original_price, 2); ?></span>
                            </p>
                            <p class="text-green-600 font-semibold">
                                Discount (20%): -₹<?php echo number_format($discount_amount, 2); ?>
                            </p>
                            <p class="text-2xl font-bold text-purple-600">Final Price: ₹<?php echo number_format($final_price, 2); ?></p>
                        <?php else: ?>
                            <p class="text-2xl font-bold text-purple-600">Total: ₹<?php echo number_format($original_price, 2); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <h3 class="text-xl font-bold mb-4">Payment Details</h3>
                    <div class="text-center">
                        <div class="bg-gray-100 p-6 rounded-lg mb-4">
                            <img src="uploads/delivered/DV_QR_website.jpeg" 
                                 alt="Payment QR Code" 
                                 class="mx-auto w-64 h-64 object-contain rounded-lg">
                            <p class="mt-4 text-sm text-gray-600">Scan with any UPI app</p>
                            <p class="mt-2 text-lg font-semibold text-purple-600">dhruvit.patel@oksbi</p>
                        </div>
                        
                        <div class="text-left bg-blue-50 p-4 rounded-lg mb-4">
                            <p class="font-semibold mb-2">UPI ID:</p>
                            <p class="text-lg">dhruvit.patel@oksbi</p>
                            <p class="font-semibold mt-3 mb-2">Amount to Pay:</p>
                            <p class="text-2xl font-bold text-purple-600">₹<?php echo number_format($final_price, 2); ?></p>
                            <?php if ($is_first_order): ?>
                                <p class="text-sm text-green-600 mt-2">
                                    <i class="fas fa-check-circle"></i> 20% discount applied!
                                </p>
                            <?php endif; ?>
                        </div>

                        <form method="POST" enctype="multipart/form-data" class="mt-6">
                            <label class="block text-left font-semibold mb-2">Upload Payment Screenshot *</label>
                            <input type="file" name="payment_screenshot" required accept="image/*" 
                                   class="w-full px-4 py-2 border rounded-lg mb-4">
                            <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 font-semibold">
                                <i class="fas fa-check mr-2"></i>Confirm Payment
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="mt-8 bg-yellow-50 border-l-4 border-yellow-400 p-4">
                <p class="font-semibold mb-2"><i class="fas fa-info-circle mr-2"></i>Important:</p>
                <ul class="list-disc list-inside text-sm space-y-1">
                    <li>Complete the payment using the QR code or UPI ID</li>
                    <li>Upload the payment screenshot for verification</li>
                    <li>Your order will be processed after payment verification</li>
                    <li>You will receive updates via email and WhatsApp</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
