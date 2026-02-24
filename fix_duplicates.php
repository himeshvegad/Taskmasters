<?php
require_once 'config/database.php';

$message = '';
$action_taken = false;

// Check current state
$total_services = $conn->query("SELECT COUNT(*) as count FROM services")->fetch_assoc()['count'];
$unique_services = $conn->query("SELECT COUNT(DISTINCT name, category) as count FROM services")->fetch_assoc()['count'];
$duplicates_count = $total_services - $unique_services;

// Remove duplicates if button clicked
if (isset($_POST['fix_now'])) {
    // Step 1: Create temporary table with unique services
    $conn->query("CREATE TEMPORARY TABLE services_temp AS 
                  SELECT MIN(id) as id, category, name, price, description 
                  FROM services 
                  GROUP BY category, name");
    
    // Step 2: Clear original table
    $conn->query("DELETE FROM services");
    
    // Step 3: Insert unique services back
    $conn->query("INSERT INTO services (id, category, name, price, description) 
                  SELECT id, category, name, price, description FROM services_temp");
    
    // Step 4: Add unique constraint to prevent future duplicates
    $conn->query("ALTER TABLE services DROP INDEX IF EXISTS unique_service");
    $conn->query("ALTER TABLE services ADD UNIQUE KEY unique_service (name, category)");
    
    $message = '<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p class="font-bold">✓ Success!</p>
                    <p>Removed ' . $duplicates_count . ' duplicate service(s). Database is now clean!</p>
                </div>';
    $action_taken = true;
    
    // Refresh counts
    $total_services = $conn->query("SELECT COUNT(*) as count FROM services")->fetch_assoc()['count'];
    $unique_services = $total_services;
    $duplicates_count = 0;
}

// Get all services grouped
$student_services = $conn->query("SELECT * FROM services WHERE category = 'student' ORDER BY name");
$business_services = $conn->query("SELECT * FROM services WHERE category = 'business' ORDER BY name");
$individual_services = $conn->query("SELECT * FROM services WHERE category = 'individual' ORDER BY name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fix Duplicate Services - TaskMasters</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 py-12">
        <div class="bg-white rounded-lg shadow-xl p-8 mb-8">
            <h1 class="text-4xl font-bold mb-2 text-center text-purple-600">
                <i class="fas fa-tools"></i> Service Duplicate Fixer
            </h1>
            <p class="text-center text-gray-600 mb-8">One-click solution to remove duplicate service cards</p>

            <?php echo $message; ?>

            <!-- Status Dashboard -->
            <div class="grid md:grid-cols-3 gap-6 mb-8">
                <div class="bg-blue-50 rounded-lg p-6 text-center">
                    <div class="text-4xl font-bold text-blue-600"><?php echo $total_services; ?></div>
                    <div class="text-gray-600 mt-2">Total Services</div>
                </div>
                <div class="bg-green-50 rounded-lg p-6 text-center">
                    <div class="text-4xl font-bold text-green-600"><?php echo $unique_services; ?></div>
                    <div class="text-gray-600 mt-2">Unique Services</div>
                </div>
                <div class="bg-<?php echo $duplicates_count > 0 ? 'red' : 'green'; ?>-50 rounded-lg p-6 text-center">
                    <div class="text-4xl font-bold text-<?php echo $duplicates_count > 0 ? 'red' : 'green'; ?>-600">
                        <?php echo $duplicates_count; ?>
                    </div>
                    <div class="text-gray-600 mt-2">Duplicates</div>
                </div>
            </div>

            <?php if ($duplicates_count > 0): ?>
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 mb-6">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-exclamation-triangle text-yellow-500 text-3xl mr-4"></i>
                        <div>
                            <h3 class="text-xl font-bold text-yellow-800">Duplicates Detected!</h3>
                            <p class="text-yellow-700">Found <?php echo $duplicates_count; ?> duplicate service entries in your database.</p>
                        </div>
                    </div>
                    <form method="POST" onsubmit="return confirm('This will remove all duplicate services. Continue?');">
                        <button type="submit" name="fix_now" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-lg transition-colors">
                            <i class="fas fa-magic mr-2"></i> Fix Now (Remove Duplicates)
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <div class="bg-green-50 border-l-4 border-green-500 p-6 mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 text-3xl mr-4"></i>
                        <div>
                            <h3 class="text-xl font-bold text-green-800">All Clean!</h3>
                            <p class="text-green-700">No duplicate services found. Your database is optimized.</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Service Preview -->
        <div class="bg-white rounded-lg shadow-xl p-8">
            <h2 class="text-2xl font-bold mb-6">Current Services</h2>

            <!-- Student Services -->
            <div class="mb-8">
                <h3 class="text-xl font-bold text-blue-600 mb-4">
                    <i class="fas fa-graduation-cap"></i> Student Services (<?php echo $student_services->num_rows; ?>)
                </h3>
                <div class="grid md:grid-cols-4 gap-4">
                    <?php while($s = $student_services->fetch_assoc()): ?>
                        <div class="border rounded-lg p-4 hover:shadow-lg transition-shadow">
                            <div class="font-semibold text-gray-800"><?php echo htmlspecialchars($s['name']); ?></div>
                            <div class="text-purple-600 font-bold mt-2">₹<?php echo htmlspecialchars($s['price']); ?></div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <!-- Business Services -->
            <div class="mb-8">
                <h3 class="text-xl font-bold text-green-600 mb-4">
                    <i class="fas fa-briefcase"></i> Business Services (<?php echo $business_services->num_rows; ?>)
                </h3>
                <div class="grid md:grid-cols-4 gap-4">
                    <?php while($s = $business_services->fetch_assoc()): ?>
                        <div class="border rounded-lg p-4 hover:shadow-lg transition-shadow">
                            <div class="font-semibold text-gray-800"><?php echo htmlspecialchars($s['name']); ?></div>
                            <div class="text-purple-600 font-bold mt-2">₹<?php echo htmlspecialchars($s['price']); ?></div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <!-- Individual Services -->
            <div class="mb-8">
                <h3 class="text-xl font-bold text-purple-600 mb-4">
                    <i class="fas fa-user"></i> Individual Services (<?php echo $individual_services->num_rows; ?>)
                </h3>
                <div class="grid md:grid-cols-4 gap-4">
                    <?php while($s = $individual_services->fetch_assoc()): ?>
                        <div class="border rounded-lg p-4 hover:shadow-lg transition-shadow">
                            <div class="font-semibold text-gray-800"><?php echo htmlspecialchars($s['name']); ?></div>
                            <div class="text-purple-600 font-bold mt-2">₹<?php echo htmlspecialchars($s['price']); ?></div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>

        <div class="mt-8 text-center space-x-4">
            <a href="services.php?category=student" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                <i class="fas fa-eye mr-2"></i> View Services Page
            </a>
            <a href="index.php" class="inline-block bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700">
                <i class="fas fa-home mr-2"></i> Back to Home
            </a>
        </div>
    </div>
</body>
</html>
