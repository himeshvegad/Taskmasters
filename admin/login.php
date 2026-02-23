<?php
session_start();
require_once '../config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Use prepared statement
    $stmt = $conn->prepare("SELECT id, name, email, password, is_admin FROM users WHERE email = ? AND is_admin = 1");
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
            
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Incorrect password';
        }
    } else {
        $error = 'Admin account not found';
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - TaskMasters</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-purple-900 to-indigo-900 min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full bg-white rounded-lg shadow-2xl p-8">
            <div class="text-center mb-8">
                <div class="bg-purple-600 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user-shield text-3xl text-white"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-800">Admin Panel</h2>
                <p class="text-gray-600 mt-2">TaskMasters Administration</p>
            </div>

            <?php if($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <i class="fas fa-exclamation-circle mr-2"></i><?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-envelope mr-2"></i>Admin Email
                    </label>
                    <input type="email" name="email" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-lock mr-2"></i>Password
                    </label>
                    <div class="relative">
                        <input type="password" id="adminPassword" name="password" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                        <button type="button" onclick="togglePassword()" class="absolute right-3 top-3 text-gray-500 hover:text-gray-700">
                            <i id="eyeIcon" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" class="w-full bg-purple-600 text-white py-3 rounded-lg hover:bg-purple-700 font-semibold text-lg transition-colors">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login to Admin Panel
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-gray-200">
                <a href="../index.php" class="block text-center text-gray-600 hover:text-purple-600 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Main Website
                </a>
            </div>

            <div class="mt-6 text-center text-sm text-gray-500">
                <p><i class="fas fa-shield-alt mr-1"></i>Secure Admin Access Only</p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('adminPassword');
            const eyeIcon = document.getElementById('eyeIcon');
            
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
