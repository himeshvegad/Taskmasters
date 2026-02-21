<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$service_id = isset($_GET['service_id']) ? intval($_GET['service_id']) : 0;
$service = $conn->query("SELECT * FROM services WHERE id = $service_id")->fetch_assoc();

if (!$service) {
    header('Location: services.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $title = $conn->real_escape_string($_POST['title']);
    $work_summary = $conn->real_escape_string($_POST['work_summary']);
    $deadline = $conn->real_escape_string($_POST['deadline']);
    $special_instructions = $conn->real_escape_string($_POST['special_instructions']);
    
    $file_path = '';
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $target_dir = "uploads/";
        $file_path = $target_dir . time() . '_' . basename($_FILES['file']['name']);
        move_uploaded_file($_FILES['file']['tmp_name'], $file_path);
    }
    
    $sql = "INSERT INTO orders (user_id, category, service_name, price, title, work_summary, deadline, special_instructions, file_path) 
            VALUES ($user_id, '{$service['category']}', '{$service['name']}', '{$service['price']}', '$title', '$work_summary', '$deadline', '$special_instructions', '$file_path')";
    
    if ($conn->query($sql)) {
        $order_id = $conn->insert_id;
        header("Location: checkout.php?order_id=$order_id");
        exit;
    } else {
        $error = 'Failed to create order';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - TaskMasters</title>
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
                    <a href="dashboard.php" class="text-gray-700 hover:text-purple-600">Dashboard</a>
                    <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-3xl mx-auto px-4 py-12">
        <div class="bg-white rounded-lg shadow-xl p-8">
            <h2 class="text-3xl font-bold mb-6">Order Details</h2>
            
            <div class="bg-purple-50 p-4 rounded-lg mb-6">
                <h3 class="text-xl font-bold"><?php echo htmlspecialchars($service['name']); ?></h3>
                <p class="text-gray-600"><?php echo htmlspecialchars($service['description']); ?></p>
                <p class="text-2xl font-bold text-purple-600 mt-2">₹<?php echo htmlspecialchars($service['price']); ?></p>
            </div>

            <?php if($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Topic/Title *</label>
                    <input type="text" name="title" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Upload Photo/Document</label>
                    <input type="file" name="file" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600">
                    <p class="text-sm text-gray-500 mt-1">Supported formats: PDF, DOC, DOCX, JPG, PNG (Max 10MB)</p>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Work Summary *</label>
                    <textarea name="work_summary" required rows="4" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600"></textarea>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Target Deadline *</label>
                    <input type="date" name="deadline" required min="<?php echo date('Y-m-d'); ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Special Instructions</label>
                    <textarea name="special_instructions" rows="3" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" placeholder="Any specific requirements or preferences..."></textarea>
                </div>

                <div class="flex space-x-4">
                    <button type="submit" class="flex-1 bg-purple-600 text-white py-3 rounded-lg hover:bg-purple-700 font-semibold">
                        Proceed to Checkout
                    </button>
                    <a href="services.php?category=<?php echo htmlspecialchars($service['category']); ?>" class="flex-1 bg-gray-300 text-gray-700 py-3 rounded-lg hover:bg-gray-400 font-semibold text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
