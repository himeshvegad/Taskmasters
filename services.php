<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : 'student';
$result = $conn->query("SELECT * FROM services WHERE category = '$category'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services - TaskMasters</title>
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

    <div class="max-w-7xl mx-auto px-4 py-12">
        <h2 class="text-3xl font-bold mb-8 text-center capitalize"><?php echo htmlspecialchars($category); ?> Services</h2>

        <div class="flex justify-center space-x-4 mb-8">
            <a href="?category=student" class="px-6 py-2 rounded-lg <?php echo $category == 'student' ? 'bg-blue-500 text-white' : 'bg-white text-gray-700'; ?>">Student</a>
            <a href="?category=business" class="px-6 py-2 rounded-lg <?php echo $category == 'business' ? 'bg-green-500 text-white' : 'bg-white text-gray-700'; ?>">Business</a>
            <a href="?category=individual" class="px-6 py-2 rounded-lg <?php echo $category == 'individual' ? 'bg-purple-500 text-white' : 'bg-white text-gray-700'; ?>">Individual</a>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while($service = $result->fetch_assoc()): ?>
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-xl font-bold mb-2"><?php echo htmlspecialchars($service['name']); ?></h3>
                    <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($service['description']); ?></p>
                    <div class="flex justify-between items-center">
                        <span class="text-2xl font-bold text-purple-600">₹<?php echo htmlspecialchars($service['price']); ?></span>
                        <a href="order.php?service_id=<?php echo $service['id']; ?>" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700">
                            Order Now
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
