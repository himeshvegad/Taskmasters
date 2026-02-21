<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Install TaskMasters</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="max-w-2xl w-full bg-white rounded-lg shadow-xl p-8">
            <h1 class="text-3xl font-bold text-purple-600 mb-6 text-center">TaskMasters Installation</h1>
            
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $conn = new mysqli('localhost', 'root', '');
                
                if ($conn->connect_error) {
                    echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">Connection failed: ' . $conn->connect_error . '</div>';
                } else {
                    $sql = file_get_contents('config/setup.sql');
                    
                    if ($conn->multi_query($sql)) {
                        do {
                            if ($result = $conn->store_result()) {
                                $result->free();
                            }
                        } while ($conn->next_result());
                        
                        echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                                <p class="font-bold">✅ Installation Successful!</p>
                                <p class="mt-2">Database and tables created successfully.</p>
                              </div>';
                        echo '<div class="text-center mt-6">
                                <a href="index.php" class="bg-purple-600 text-white px-8 py-3 rounded-lg hover:bg-purple-700 inline-block">
                                    Go to Website
                                </a>
                              </div>';
                    } else {
                        echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">Error: ' . $conn->error . '</div>';
                    }
                    $conn->close();
                }
            } else {
            ?>
            
            <div class="space-y-4 mb-6">
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                    <p class="font-semibold mb-2">📋 Prerequisites:</p>
                    <ul class="list-disc list-inside text-sm space-y-1">
                        <li>XAMPP Apache is running</li>
                        <li>XAMPP MySQL is running</li>
                    </ul>
                </div>
                
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <p class="font-semibold mb-2">⚠️ This will:</p>
                    <ul class="list-disc list-inside text-sm space-y-1">
                        <li>Create database: <code class="bg-gray-200 px-2 py-1 rounded">taskmasters</code></li>
                        <li>Create 3 tables: users, orders, services</li>
                        <li>Insert 13 sample services</li>
                    </ul>
                </div>
            </div>
            
            <form method="POST" class="text-center">
                <button type="submit" class="bg-purple-600 text-white px-8 py-3 rounded-lg hover:bg-purple-700 font-semibold text-lg">
                    Install Database
                </button>
            </form>
            
            <div class="mt-8 text-center text-sm text-gray-600">
                <p>Already installed? <a href="index.php" class="text-purple-600 hover:underline">Go to website</a></p>
            </div>
            
            <?php } ?>
        </div>
    </div>
</body>
</html>
