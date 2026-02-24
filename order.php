<?php
require_once 'config/db.php';
require_once 'config/email.php';

$service_name = isset($_GET['service']) ? $_GET['service'] : '';
$price = isset($_GET['price']) ? floatval($_GET['price']) : 0;
$discounted_price = $price * 0.8;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $service = trim($_POST['service']);
    $requirements = trim($_POST['requirements']);
    $price = floatval($_POST['price']);
    $final_price = $price * 0.8; // 20% discount
    
    $order_id = generateOrderId();
    $file_path = null;
    
    // Handle file upload
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $target_dir = "uploads/requirements/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        $file_path = $target_dir . time() . '_' . basename($_FILES['file']['name']);
        move_uploaded_file($_FILES['file']['tmp_name'], $file_path);
    }
    
    $stmt = $conn->prepare("INSERT INTO guest_orders (order_id, name, email, phone, service, price, discount_applied, final_price, requirements, file_path) VALUES (?, ?, ?, ?, ?, ?, 1, ?, ?, ?)");
    $stmt->bind_param("sssssddss", $order_id, $name, $email, $phone, $service, $price, $final_price, $requirements, $file_path);
    
    if ($stmt->execute()) {
        // Send order confirmation email
        $orderData = [
            'order_id' => $order_id,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'service' => $service,
            'price' => $price,
            'final_price' => $final_price,
            'payment_status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ];
        sendOrderConfirmationEmail($orderData);
        
        header("Location: payment.php?order_id=" . $order_id);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place Order - TaskMasters</title>
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

    <div class="max-w-4xl mx-auto px-6 py-12">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Place Your Order</h1>
            <p class="text-gray-600 mb-8">Fill in the details below. No account required.</p>

            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name *</label>
                        <input type="text" name="name" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address *</label>
                        <input type="email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number *</label>
                        <input type="tel" name="phone" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Service *</label>
                        <select name="service" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="">Select a service</option>
                            <?php
                            $services = $conn->query("SELECT * FROM services WHERE is_active = 1 ORDER BY name");
                            while($s = $services->fetch_assoc()):
                            ?>
                            <option value="<?php echo htmlspecialchars($s['name']); ?>" data-price="<?php echo $s['price']; ?>" <?php echo ($s['name'] == $service_name) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($s['name']); ?> - ₹<?php echo number_format($s['price'] * 0.8, 0); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Detailed Requirements *</label>
                    <textarea name="requirements" required rows="5" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="Describe your requirements in detail..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Upload File (Optional)</label>
                    <input type="file" name="file" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <p class="text-sm text-gray-500 mt-2">Upload any reference files, documents, or images</p>
                </div>

                <input type="hidden" name="price" id="price" value="<?php echo $price; ?>">

                <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-gray-700 font-semibold">Original Price:</span>
                        <span class="text-gray-500 line-through" id="original-price">₹<?php echo number_format($price, 2); ?></span>
                    </div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-green-700 font-semibold">Discount (20%):</span>
                        <span class="text-green-600" id="discount">-₹<?php echo number_format($price * 0.2, 2); ?></span>
                    </div>
                    <div class="flex items-center justify-between text-xl font-bold border-t border-purple-200 pt-4 mt-4">
                        <span class="text-gray-900">Total to Pay:</span>
                        <span class="text-purple-600" id="final-price">₹<?php echo number_format($discounted_price, 2); ?></span>
                    </div>
                    <div class="mt-3 text-sm text-purple-700 flex items-center">
                        <i class="fas fa-gift mr-2"></i>
                        <span>20% OFF applied - First order discount!</span>
                    </div>
                </div>

                <button type="submit" class="w-full gradient-bg text-white py-4 rounded-lg font-bold text-lg hover:opacity-90 transition">
                    Proceed to Payment <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </form>
        </div>
    </div>

    <script>
    document.querySelector('select[name="service"]').addEventListener('change', function() {
        const price = parseFloat(this.options[this.selectedIndex].dataset.price) || 0;
        const discounted = price * 0.8;
        const discount = price * 0.2;
        
        document.getElementById('price').value = price;
        document.getElementById('original-price').textContent = '₹' + price.toFixed(2);
        document.getElementById('discount').textContent = '-₹' + discount.toFixed(2);
        document.getElementById('final-price').textContent = '₹' + discounted.toFixed(2);
    });
    </script>
</body>
</html>
