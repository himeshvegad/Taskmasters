<?php
session_start();
require_once '../config/database.php';
require_once 'includes/auth_check.php';

// Handle add/edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $category = $conn->real_escape_string($_POST['category']);
    $name = $conn->real_escape_string($_POST['name']);
    $price = $conn->real_escape_string($_POST['price']);
    $description = $conn->real_escape_string($_POST['description']);
    
    if ($id) {
        $conn->query("UPDATE services SET category='$category', name='$name', price='$price', description='$description' WHERE id=$id");
    } else {
        $conn->query("INSERT INTO services (category, name, price, description) VALUES ('$category', '$name', '$price', '$description')");
    }
    header('Location: services.php');
    exit;
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM services WHERE id = $id");
    header('Location: services.php');
    exit;
}

$services = $conn->query("SELECT * FROM services ORDER BY category, name");
$edit_service = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $edit_service = $conn->query("SELECT * FROM services WHERE id = $edit_id")->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services - TaskMasters Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <?php include 'includes/sidebar.php'; ?>
    
    <div class="ml-64 p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Service Management</h1>

        <!-- Add/Edit Form -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold mb-4"><?php echo $edit_service ? 'Edit Service' : 'Add New Service'; ?></h2>
            <form method="POST" class="grid grid-cols-2 gap-4">
                <?php if($edit_service): ?>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($edit_service['id']); ?>">
                <?php endif; ?>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Category</label>
                    <select name="category" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600">
                        <option value="Student" <?php echo ($edit_service && $edit_service['category'] == 'Student') ? 'selected' : ''; ?>>Student</option>
                        <option value="Business" <?php echo ($edit_service && $edit_service['category'] == 'Business') ? 'selected' : ''; ?>>Business</option>
                        <option value="Individual" <?php echo ($edit_service && $edit_service['category'] == 'Individual') ? 'selected' : ''; ?>>Individual</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Service Name</label>
                    <input type="text" name="name" required value="<?php echo $edit_service ? htmlspecialchars($edit_service['name']) : ''; ?>"
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600">
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Price</label>
                    <input type="text" name="price" required value="<?php echo $edit_service ? htmlspecialchars($edit_service['price']) : ''; ?>"
                           placeholder="e.g., ₹499 or ₹9,999+"
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600">
                </div>
                
                <div class="col-span-2">
                    <label class="block text-gray-700 font-semibold mb-2">Description</label>
                    <textarea name="description" rows="3" 
                              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600"><?php echo $edit_service ? htmlspecialchars($edit_service['description']) : ''; ?></textarea>
                </div>
                
                <div class="col-span-2 flex gap-4">
                    <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700">
                        <i class="fas fa-save mr-2"></i><?php echo $edit_service ? 'Update' : 'Add'; ?> Service
                    </button>
                    <?php if($edit_service): ?>
                    <a href="services.php" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">Cancel</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Services List -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Service Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php while($service = $services->fetch_assoc()): ?>
                    <tr>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($service['id']); ?></td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                <?php echo $service['category'] == 'Student' ? 'bg-blue-100 text-blue-800' : 
                                          ($service['category'] == 'Business' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'); ?>">
                                <?php echo htmlspecialchars($service['category']); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 font-medium"><?php echo htmlspecialchars($service['name']); ?></td>
                        <td class="px-6 py-4 font-bold"><?php echo htmlspecialchars($service['price']); ?></td>
                        <td class="px-6 py-4 text-sm text-gray-600"><?php echo htmlspecialchars($service['description']); ?></td>
                        <td class="px-6 py-4">
                            <a href="?edit=<?php echo htmlspecialchars($service['id']); ?>" class="text-blue-600 hover:text-blue-800 mr-3">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="?delete=<?php echo htmlspecialchars($service['id']); ?>" 
                               onclick="return confirm('Delete this service?')" 
                               class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
