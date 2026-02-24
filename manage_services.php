<?php
require_once 'config/database.php';

// Check for duplicates
$check_query = "SELECT name, category, COUNT(*) as count 
                FROM services 
                GROUP BY name, category 
                HAVING count > 1";
$duplicates = $conn->query($check_query);

$has_duplicates = $duplicates->num_rows > 0;

// Remove duplicates if requested
if (isset($_POST['remove_duplicates'])) {
    $delete_query = "DELETE s1 FROM services s1
                     INNER JOIN services s2 
                     WHERE s1.id > s2.id 
                     AND s1.name = s2.name 
                     AND s1.category = s2.category";
    
    if ($conn->query($delete_query)) {
        $message = '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">Duplicates removed successfully!</div>';
        header("Refresh:2");
    } else {
        $message = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">Error: ' . $conn->error . '</div>';
    }
}

// Get all services
$all_services = $conn->query("SELECT * FROM services ORDER BY category, name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Manager - TaskMasters</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-6xl mx-auto px-4 py-12">
        <h1 class="text-3xl font-bold mb-8 text-center">Service Manager</h1>

        <?php if(isset($message)) echo $message; ?>

        <!-- Duplicate Check -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold mb-4">Duplicate Check</h2>
            
            <?php if ($has_duplicates): ?>
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                    <strong>Warning:</strong> Found <?php echo $duplicates->num_rows; ?> duplicate service(s)
                </div>
                
                <table class="w-full mb-4">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-4 py-2 text-left">Service Name</th>
                            <th class="px-4 py-2 text-left">Category</th>
                            <th class="px-4 py-2 text-left">Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($dup = $duplicates->fetch_assoc()): ?>
                        <tr class="border-b">
                            <td class="px-4 py-2"><?php echo htmlspecialchars($dup['name']); ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($dup['category']); ?></td>
                            <td class="px-4 py-2"><?php echo $dup['count']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <form method="POST">
                    <button type="submit" name="remove_duplicates" class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700">
                        Remove All Duplicates
                    </button>
                </form>
            <?php else: ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    <strong>Success:</strong> No duplicate services found!
                </div>
            <?php endif; ?>
        </div>

        <!-- All Services -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold mb-4">All Services (<?php echo $all_services->num_rows; ?>)</h2>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-4 py-2 text-left">ID</th>
                            <th class="px-4 py-2 text-left">Category</th>
                            <th class="px-4 py-2 text-left">Name</th>
                            <th class="px-4 py-2 text-left">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($service = $all_services->fetch_assoc()): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2"><?php echo $service['id']; ?></td>
                            <td class="px-4 py-2 capitalize"><?php echo htmlspecialchars($service['category']); ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($service['name']); ?></td>
                            <td class="px-4 py-2">₹<?php echo htmlspecialchars($service['price']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6 text-center">
            <a href="index.php" class="text-purple-600 hover:underline">← Back to Home</a>
        </div>
    </div>
</body>
</html>
