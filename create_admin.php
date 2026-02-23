<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Admin - TaskMasters</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full bg-white rounded-lg shadow-xl p-8">
            <h1 class="text-2xl font-bold text-purple-600 mb-6 text-center">Create Admin Account</h1>
            
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                require_once 'config/database.php';
                
                $email = 'admin@taskmasters.com';
                $password = 'admin123';
                $hash = password_hash($password, PASSWORD_DEFAULT);
                
                // Delete existing admin if exists
                $conn->query("DELETE FROM users WHERE email = '$email'");
                
                // Create new admin
                $sql = "INSERT INTO users (name, email, phone, password, is_admin) 
                        VALUES ('Admin', '$email', '1234567890', '$hash', 1)";
                
                if ($conn->query($sql)) {
                    echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            <p class="font-bold">✅ Admin Created Successfully!</p>
                            <p class="mt-2">Email: admin@taskmasters.com</p>
                            <p>Password: admin123</p>
                          </div>';
                    echo '<div class="text-center mt-6">
                            <a href="admin/login.php" class="bg-purple-600 text-white px-8 py-3 rounded-lg hover:bg-purple-700 inline-block">
                                Go to Admin Login
                            </a>
                          </div>';
                } else {
                    echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">Error: ' . $conn->error . '</div>';
                }
                $conn->close();
            } else {
            ?>
            
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                <p class="font-semibold mb-2">This will create:</p>
                <ul class="list-disc list-inside text-sm space-y-1">
                    <li>Email: admin@taskmasters.com</li>
                    <li>Password: admin123</li>
                    <li>Admin access enabled</li>
                </ul>
            </div>
            
            <form method="POST" class="text-center">
                <button type="submit" class="bg-purple-600 text-white px-8 py-3 rounded-lg hover:bg-purple-700 font-semibold text-lg">
                    Create Admin Account
                </button>
            </form>
            
            <div class="mt-6 text-center text-sm text-gray-600">
                <p>Already have admin? <a href="admin/login.php" class="text-purple-600 hover:underline">Login here</a></p>
            </div>
            
            <?php } ?>
        </div>
    </div>
</body>
</html>
