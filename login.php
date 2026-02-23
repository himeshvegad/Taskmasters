<?php
session_start();
require_once 'config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, name, email, password, is_admin FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['is_admin'] = $user['is_admin'];
            
            // Role-based redirect
            if ($user['is_admin'] == 1) {
                header('Location: admin/dashboard.php');
            } else {
                header('Location: dashboard.php');
            }
            exit;
        } else {
            $error = 'Incorrect password';
        }
    } else {
        $error = 'Email not found';
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TaskMasters</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full bg-white rounded-lg shadow-xl p-8">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-purple-600">TaskMasters</h2>
                <p class="text-gray-600 mt-2">Sign in to your account</p>
            </div>

            <?php if(isset($_GET['registered'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">Registration successful! Please login.</div>
            <?php endif; ?>

            <?php if($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600">
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <input type="password" id="userPassword" name="password" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600">
                        <button type="button" onclick="toggleUserPassword()" class="absolute right-3 top-2 text-gray-500 hover:text-gray-700">
                            <i id="userEyeIcon" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" class="w-full bg-purple-600 text-white py-3 rounded-lg hover:bg-purple-700 font-semibold">
                    Sign In
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-600">Don't have an account? <a href="register.php" class="text-purple-600 hover:underline">Register</a></p>
            </div>

            <div class="mt-6">
                <a href="index.php" class="block text-center text-gray-600 hover:text-purple-600">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Home
                </a>
            </div>
        </div>
    </div>

    <script>
        function toggleUserPassword() {
            const passwordInput = document.getElementById('userPassword');
            const eyeIcon = document.getElementById('userEyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
