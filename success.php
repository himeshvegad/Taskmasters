<?php
require_once 'config/db.php';
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : '';
$order = $conn->query("SELECT * FROM guest_orders WHERE order_id = '$order_id'")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed - TaskMasters</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { font-family: 'Inter', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center px-6">
        <div class="max-w-2xl w-full bg-white rounded-2xl shadow-xl p-12 text-center">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-check text-4xl text-green-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Order Confirmed!</h1>
            <p class="text-lg text-gray-600 mb-8">Your payment has been submitted for verification.</p>
            
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-6 mb-8">
                <p class="text-sm text-gray-600 mb-2">Your Order ID</p>
                <p class="text-2xl font-bold text-purple-600"><?php echo htmlspecialchars($order_id); ?></p>
            </div>

            <div class="text-left bg-gray-50 rounded-lg p-6 mb-8">
                <h3 class="font-bold text-gray-900 mb-4">What happens next?</h3>
                <div class="space-y-3">
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-purple-600 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3 mt-0.5">1</div>
                        <p class="text-gray-700">We'll verify your payment within 2-4 hours</p>
                    </div>
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-purple-600 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3 mt-0.5">2</div>
                        <p class="text-gray-700">Our team will start working on your order</p>
                    </div>
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-purple-600 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3 mt-0.5">3</div>
                        <p class="text-gray-700">You'll receive the completed work via email within 24-48 hours</p>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
                <p class="text-sm text-blue-800">
                    <i class="fas fa-envelope mr-2"></i>
                    Order confirmation sent to <strong><?php echo htmlspecialchars($order['email']); ?></strong>
                </p>
            </div>

            <a href="home.php" class="inline-block gradient-bg text-white px-8 py-3 rounded-lg font-semibold hover:opacity-90 transition">
                <i class="fas fa-home mr-2"></i>Back to Home
            </a>
        </div>
    </div>
</body>
</html>
