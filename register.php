<?php
session_start();
require_once 'config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);
    
    // Hash password using bcrypt (PASSWORD_DEFAULT uses bcrypt)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Check if email exists using prepared statement
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $check = $stmt->get_result();
    
    if ($check->num_rows > 0) {
        $error = 'Email already registered';
    } else {
        // Insert new user with prepared statement and set first_order_discount to 1
        $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password, first_order_discount) VALUES (?, ?, ?, ?, 1)");
        $stmt->bind_param("ssss", $name, $email, $phone, $hashed_password);
        
        if ($stmt->execute()) {
            header('Location: login.php?registered=1');
            exit;
        } else {
            $error = 'Registration failed';
        }
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - TaskMasters</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full bg-white rounded-lg shadow-xl p-8">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-purple-600">TaskMasters</h2>
                <p class="text-gray-600 mt-2">Create your account</p>
            </div>

            <?php if($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-gray-700 mb-2">Full Name</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600">
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600">
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Phone Number</label>
                    <input type="tel" name="phone" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600">
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600">
                </div>
                <button type="submit" class="w-full bg-purple-600 text-white py-3 rounded-lg hover:bg-purple-700 font-semibold">
                    Register
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-600">Already have an account? <a href="login.php" class="text-purple-600 hover:underline">Sign In</a></p>
            </div>

            <div class="mt-6">
                <a href="index.php" class="block text-center text-gray-600 hover:text-purple-600">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Home
                </a>
            </div>
        </div>
    </div>
</body>
</html>
