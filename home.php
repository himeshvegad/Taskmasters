<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskMasters - Professional Digital Services</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { font-family: 'Inter', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .card-hover { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .card-hover:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <div class="w-10 h-10 gradient-bg rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-xl">T</span>
                    </div>
                    <span class="text-2xl font-bold text-gray-900">TaskMasters</span>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#services" class="text-gray-600 hover:text-purple-600 font-medium transition">Services</a>
                    <a href="#how-it-works" class="text-gray-600 hover:text-purple-600 font-medium transition">How It Works</a>
                    <a href="#contact" class="text-gray-600 hover:text-purple-600 font-medium transition">Contact</a>
                    <a href="order.php" class="gradient-bg text-white px-6 py-2.5 rounded-lg font-semibold hover:opacity-90 transition">Get Started</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="gradient-bg text-white py-24">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-4xl mx-auto">
                <div class="inline-block bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full mb-6">
                    <span class="text-sm font-semibold">🎉 20% OFF on Your First Order</span>
                </div>
                <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
                    Professional Digital<br/>Services Made Simple
                </h1>
                <p class="text-xl text-purple-100 mb-10 leading-relaxed">
                    Get expert help with presentations, Excel work, email drafting, and more.<br/>
                    Fast delivery. Professional quality. No account required.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#services" class="bg-white text-purple-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-50 transition inline-flex items-center justify-center">
                        Browse Services <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                    <a href="order.php" class="bg-purple-800 text-white px-8 py-4 rounded-lg font-semibold hover:bg-purple-900 transition inline-flex items-center justify-center">
                        Place Order Now
                    </a>
                </div>
                
                <!-- Trust Indicators -->
                <div class="grid grid-cols-3 gap-8 mt-16 max-w-2xl mx-auto">
                    <div class="text-center">
                        <div class="text-3xl mb-2">⚡</div>
                        <div class="font-semibold">Fast Delivery</div>
                        <div class="text-sm text-purple-200">24-48 hours</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl mb-2">🔒</div>
                        <div class="font-semibold">Secure Payment</div>
                        <div class="text-sm text-purple-200">100% Safe</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl mb-2">✨</div>
                        <div class="font-semibold">Pro Quality</div>
                        <div class="text-sm text-purple-200">Expert work</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Our Services</h2>
                <p class="text-xl text-gray-600">Choose from our range of professional services</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php
                require_once 'config/db.php';
                $services = $conn->query("SELECT * FROM services WHERE is_active = 1 ORDER BY category, name");
                $current_category = '';
                
                while($service = $services->fetch_assoc()):
                    $original_price = $service['price'];
                    $discounted_price = $original_price * 0.8;
                ?>
                <div class="bg-white border border-gray-200 rounded-2xl p-6 card-hover cursor-pointer" onclick="window.location.href='order.php?service=<?php echo urlencode($service['name']); ?>&price=<?php echo $service['price']; ?>'">
                    <div class="text-3xl mb-4">
                        <?php 
                        $icons = ['Email Drafting' => '✉️', 'Excel Work' => '📊', 'Presentation (PPT)' => '📽️', 'Schedule Maker' => '📅'];
                        echo $icons[$service['name']] ?? '📄';
                        ?>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2"><?php echo htmlspecialchars($service['name']); ?></h3>
                    <p class="text-sm text-gray-600 mb-4"><?php echo htmlspecialchars($service['description']); ?></p>
                    <div class="flex items-baseline gap-2 mb-4">
                        <span class="text-2xl font-bold text-purple-600">₹<?php echo number_format($discounted_price, 0); ?></span>
                        <span class="text-sm text-gray-400 line-through">₹<?php echo number_format($original_price, 0); ?></span>
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full font-semibold">20% OFF</span>
                    </div>
                    <button class="w-full gradient-bg text-white py-2.5 rounded-lg font-semibold hover:opacity-90 transition">
                        Order Now
                    </button>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section id="how-it-works" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">How It Works</h2>
                <p class="text-xl text-gray-600">Simple 3-step process</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center text-white text-2xl font-bold mx-auto mb-4">1</div>
                    <h3 class="text-xl font-bold mb-2">Choose Service</h3>
                    <p class="text-gray-600">Select the service you need from our catalog</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center text-white text-2xl font-bold mx-auto mb-4">2</div>
                    <h3 class="text-xl font-bold mb-2">Submit Details</h3>
                    <p class="text-gray-600">Fill the form with your requirements and make payment</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center text-white text-2xl font-bold mx-auto mb-4">3</div>
                    <h3 class="text-xl font-bold mb-2">Get Delivery</h3>
                    <p class="text-gray-600">Receive your completed work within 24-48 hours</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="gradient-bg text-white py-20">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold mb-4">Ready to Get Started?</h2>
            <p class="text-xl text-purple-100 mb-8">No account needed. Place your order in minutes.</p>
            <a href="order.php" class="inline-block bg-white text-purple-600 px-10 py-4 rounded-lg font-bold hover:bg-gray-50 transition text-lg">
                Place Your Order Now
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 gradient-bg rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold">T</span>
                        </div>
                        <span class="text-xl font-bold">TaskMasters</span>
                    </div>
                    <p class="text-gray-400">Professional digital services for everyone</p>
                </div>
                <div>
                    <h6 class="font-bold mb-4">Quick Links</h6>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#services" class="hover:text-white transition">Services</a></li>
                        <li><a href="order.php" class="hover:text-white transition">Place Order</a></li>
                        <li><a href="admin" class="hover:text-white transition">Admin Login</a></li>
                    </ul>
                </div>
                <div>
                    <h6 class="font-bold mb-4">Legal</h6>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-white transition">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-white transition">Refund Policy</a></li>
                    </ul>
                </div>
                <div>
                    <h6 class="font-bold mb-4">Contact</h6>
                    <ul class="space-y-2 text-gray-400">
                        <li><i class="fas fa-envelope mr-2"></i> support@taskmasters.com</li>
                        <li><i class="fas fa-phone mr-2"></i> +91 7041707025</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center text-gray-400">
                <p>&copy; 2024 TaskMasters. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Floating WhatsApp -->
    <a href="https://wa.me/917041707025" target="_blank" class="fixed bottom-6 right-6 bg-green-500 text-white w-14 h-14 rounded-full flex items-center justify-center shadow-lg hover:bg-green-600 transition z-50">
        <i class="fab fa-whatsapp text-2xl"></i>
    </a>
</body>
</html>
