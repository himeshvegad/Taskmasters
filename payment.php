<?php
require_once 'config/db.php';
require_once 'config/email.php';

$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : '';
$order = $conn->query("SELECT * FROM guest_orders WHERE order_id = '$order_id'")->fetch_assoc();

if (!$order) {
    header('Location: home.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update payment status to pending verification
    $conn->query("UPDATE guest_orders SET payment_status = 'paid' WHERE order_id = '$order_id'");
    
    // Send confirmation email
    sendOrderConfirmationEmail($order);
    
    header('Location: success.php?order_id=' . $order_id);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - TaskMasters</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { font-family: 'Inter', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    </style>
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <a href="home.php" class="flex items-center space-x-2">
                <div class="w-10 h-10 gradient-bg rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-xl">T</span>
                </div>
                <span class="text-2xl font-bold text-gray-900">TaskMasters</span>
            </a>
        </div>
    </nav>

    <div class="max-w-5xl mx-auto px-6 py-12">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="gradient-bg text-white p-6">
                <h1 class="text-2xl font-bold">Complete Payment</h1>
                <p class="text-purple-100">Order ID: <?php echo htmlspecialchars($order['order_id']); ?></p>
            </div>

            <div class="p-8">
                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Order Summary -->
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Order Summary</h2>
                        <div class="bg-gray-50 rounded-lg p-6 space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Service:</span>
                                <span class="font-semibold"><?php echo htmlspecialchars($order['service']); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Customer:</span>
                                <span class="font-semibold"><?php echo htmlspecialchars($order['name']); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Email:</span>
                                <span class="font-semibold text-sm"><?php echo htmlspecialchars($order['email']); ?></span>
                            </div>
                            <hr class="my-4">
                            <div class="flex justify-between text-gray-500">
                                <span>Original Price:</span>
                                <span class="line-through">₹<?php echo number_format($order['price'], 2); ?></span>
                            </div>
                            <div class="flex justify-between text-green-600">
                                <span>Discount (20%):</span>
                                <span>-₹<?php echo number_format($order['price'] - $order['final_price'], 2); ?></span>
                            </div>
                            <div class="flex justify-between text-2xl font-bold text-purple-600 pt-4 border-t">
                                <span>Total to Pay:</span>
                                <span>₹<?php echo number_format($order['final_price'], 2); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Payment Method</h2>
                        
                        <div class="bg-gradient-to-br from-purple-50 to-blue-50 rounded-lg p-6 mb-6">
                            <div class="text-center">
                                <img src="uploads/delivered/DV_QR_website.jpeg" 
                                     alt="Payment QR Code" 
                                     class="w-64 h-64 mx-auto object-contain rounded-lg shadow-md mb-4">
                                <p class="text-sm text-gray-600 mb-2">Scan with any UPI app</p>
                                <p class="text-lg font-bold text-purple-600">dhruvit.patel@oksbi</p>
                            </div>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                                <div class="text-sm text-blue-800">
                                    <p class="font-semibold mb-1">Payment Instructions:</p>
                                    <ol class="list-decimal list-inside space-y-1">
                                        <li>Scan QR code or use UPI ID above</li>
                                        <li>Pay exactly ₹<?php echo number_format($order['final_price'], 2); ?></li>
                                        <li>Complete the payment in your UPI app</li>
                                        <li>Click "Confirm Payment" below after payment</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <form method="POST">
                            <button type="submit" class="w-full gradient-bg text-white py-4 rounded-lg font-bold hover:opacity-90 transition">
                                <i class="fas fa-check-circle mr-2"></i>I Have Completed Payment
                            </button>
                        </form>
                        
                        <p class="text-xs text-gray-500 text-center mt-4">
                            Our team will verify your payment and start working on your order within 2-4 hours.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
