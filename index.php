<?php
session_start();
require_once 'config/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskMasters - Digital Task Service Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeInUp { animation: fadeInUp 0.8s ease-out; }
        .floating-btn { position: fixed; bottom: 20px; z-index: 1000; }
        .hero-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-purple-600">TaskMasters</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="dashboard.php" class="text-gray-700 hover:text-purple-600">Dashboard</a>
                        <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="text-gray-700 hover:text-purple-600">Sign In</a>
                        <a href="register.php" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">Get Started</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-gradient text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center animate-fadeInUp">
            <h2 class="text-5xl font-bold mb-6">Professional Digital Services</h2>
            <p class="text-xl mb-8">Get your tasks done by experts - Fast, Reliable, Affordable</p>
            
            <!-- Search Bar -->
            <div class="max-w-2xl mx-auto mb-8">
                <div class="relative">
                    <input type="text" id="searchBar" placeholder="Search for services..." 
                           class="w-full px-6 py-4 rounded-full text-gray-800 text-lg focus:outline-none focus:ring-4 focus:ring-purple-300">
                    <button class="absolute right-2 top-2 bg-purple-600 text-white px-6 py-2 rounded-full hover:bg-purple-700">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>

            <!-- Trust Indicators -->
            <div class="flex justify-center space-x-8 mt-12">
                <div class="text-center">
                    <i class="fas fa-shield-alt text-4xl mb-2"></i>
                    <p class="font-semibold">10-Day Refund</p>
                </div>
                <div class="text-center">
                    <i class="fas fa-lock text-4xl mb-2"></i>
                    <p class="font-semibold">Secure Payments</p>
                </div>
                <div class="text-center">
                    <i class="fas fa-bolt text-4xl mb-2"></i>
                    <p class="font-semibold">Fast Delivery</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Service Categories -->
    <section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h3 class="text-3xl font-bold text-center mb-12">Our Services</h3>
        
        <div class="grid md:grid-cols-3 gap-8">
            <!-- Student Services -->
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition-shadow">
                <div class="text-center mb-4">
                    <i class="fas fa-graduation-cap text-5xl text-blue-500"></i>
                    <h4 class="text-2xl font-bold mt-4">Student</h4>
                </div>
                <ul class="space-y-3">
                    <li class="flex justify-between border-b pb-2">
                        <span>Presentation (PPT)</span>
                        <span class="font-bold text-purple-600">₹499</span>
                    </li>
                    <li class="flex justify-between border-b pb-2">
                        <span>Email Drafting</span>
                        <span class="font-bold text-purple-600">₹199</span>
                    </li>
                    <li class="flex justify-between border-b pb-2">
                        <span>Schedule Maker</span>
                        <span class="font-bold text-purple-600">₹199</span>
                    </li>
                    <li class="flex justify-between border-b pb-2">
                        <span>Excel Work</span>
                        <span class="font-bold text-purple-600">₹299</span>
                    </li>
                </ul>
                <a href="services.php?category=student" class="block mt-6 bg-blue-500 text-white text-center py-3 rounded-lg hover:bg-blue-600">
                    Select Service
                </a>
            </div>

            <!-- Business Services -->
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition-shadow">
                <div class="text-center mb-4">
                    <i class="fas fa-briefcase text-5xl text-green-500"></i>
                    <h4 class="text-2xl font-bold mt-4">Business</h4>
                </div>
                <ul class="space-y-3">
                    <li class="flex justify-between border-b pb-2">
                        <span>Social Media Mgmt</span>
                        <span class="font-bold text-purple-600">₹9,999+</span>
                    </li>
                    <li class="flex justify-between border-b pb-2">
                        <span>Product Video (5-10s)</span>
                        <span class="font-bold text-purple-600">₹399</span>
                    </li>
                    <li class="flex justify-between border-b pb-2">
                        <span>Product Video (20-25s)</span>
                        <span class="font-bold text-purple-600">₹699</span>
                    </li>
                    <li class="flex justify-between border-b pb-2">
                        <span>Product Poster (1)</span>
                        <span class="font-bold text-purple-600">₹199</span>
                    </li>
                </ul>
                <a href="services.php?category=business" class="block mt-6 bg-green-500 text-white text-center py-3 rounded-lg hover:bg-green-600">
                    Select Service
                </a>
            </div>

            <!-- Individual Services -->
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition-shadow">
                <div class="text-center mb-4">
                    <i class="fas fa-user text-5xl text-purple-500"></i>
                    <h4 class="text-2xl font-bold mt-4">Individual</h4>
                </div>
                <ul class="space-y-3">
                    <li class="flex justify-between border-b pb-2">
                        <span>Custom Poster</span>
                        <span class="font-bold text-purple-600">₹299</span>
                    </li>
                    <li class="flex justify-between border-b pb-2">
                        <span>Rent Poster</span>
                        <span class="font-bold text-purple-600">₹399</span>
                    </li>
                    <li class="flex justify-between border-b pb-2">
                        <span>Wedding Invitation</span>
                        <span class="font-bold text-purple-600">₹999</span>
                    </li>
                    <li class="flex justify-between border-b pb-2">
                        <span>Photo Editing</span>
                        <span class="font-bold text-purple-600">₹299</span>
                    </li>
                </ul>
                <a href="services.php?category=individual" class="block mt-6 bg-purple-500 text-white text-center py-3 rounded-lg hover:bg-purple-600">
                    Select Service
                </a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-purple-600 text-white py-16">
        <div class="max-w-4xl mx-auto text-center px-4">
            <h3 class="text-3xl font-bold mb-4">Need Custom Help?</h3>
            <p class="text-xl mb-8">Can't find what you're looking for? Contact us directly!</p>
            <div class="flex justify-center space-x-4">
                <a href="https://wa.me/1234567890" target="_blank" class="bg-green-500 px-8 py-3 rounded-lg hover:bg-green-600 flex items-center">
                    <i class="fab fa-whatsapp mr-2"></i> WhatsApp
                </a>
                <a href="mailto:support@taskmasters.com" class="bg-blue-500 px-8 py-3 rounded-lg hover:bg-blue-600 flex items-center">
                    <i class="fas fa-envelope mr-2"></i> Email
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <h5 class="text-xl font-bold mb-4">TaskMasters</h5>
                    <p class="text-gray-400">Professional digital services for everyone</p>
                </div>
                <div>
                    <h6 class="font-bold mb-4">Services</h6>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="services.php?category=student" class="hover:text-white">Student</a></li>
                        <li><a href="services.php?category=business" class="hover:text-white">Business</a></li>
                        <li><a href="services.php?category=individual" class="hover:text-white">Individual</a></li>
                    </ul>
                </div>
                <div>
                    <h6 class="font-bold mb-4">Legal</h6>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="privacy.php" class="hover:text-white">Privacy Policy</a></li>
                        <li><a href="terms.php" class="hover:text-white">Terms of Service</a></li>
                        <li><a href="refund.php" class="hover:text-white">Refund Policy</a></li>
                    </ul>
                </div>
                <div>
                    <h6 class="font-bold mb-4">Contact</h6>
                    <ul class="space-y-2 text-gray-400">
                        <li><i class="fas fa-envelope mr-2"></i> support@taskmasters.com</li>
                        <li><i class="fas fa-phone mr-2"></i> +91 7041707025
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2026 TaskMasters. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Floating Action Buttons -->
    <a href="https://wa.me/1234567890" target="_blank" class="floating-btn right-20 bg-green-500 text-white w-14 h-14 rounded-full flex items-center justify-center shadow-lg hover:bg-green-600">
        <i class="fab fa-whatsapp text-2xl"></i>
    </a>
    <a href="mailto:support@taskmasters.com" class="floating-btn right-4 bg-blue-500 text-white w-14 h-14 rounded-full flex items-center justify-center shadow-lg hover:bg-blue-600">
        <i class="fas fa-envelope text-2xl"></i>
    </a>

    <script>
        // Search functionality
        document.getElementById('searchBar').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            // Add search logic here
        });
    </script>
</body>
</html>
