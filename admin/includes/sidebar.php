<aside class="w-64 bg-gray-900 text-white min-h-screen fixed left-0 top-0">
    <div class="p-6">
        <h1 class="text-2xl font-bold text-purple-400">TaskMasters</h1>
        <p class="text-sm text-gray-400">Admin Panel</p>
    </div>
    <nav class="mt-6">
        <a href="dashboard.php" class="flex items-center px-6 py-3 hover:bg-gray-800 <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'bg-purple-600' : ''; ?>">
            <i class="fas fa-chart-line w-6"></i>
            <span>Dashboard</span>
        </a>
        <a href="users.php" class="flex items-center px-6 py-3 hover:bg-gray-800 <?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'bg-purple-600' : ''; ?>">
            <i class="fas fa-users w-6"></i>
            <span>Users</span>
        </a>
        <a href="orders.php" class="flex items-center px-6 py-3 hover:bg-gray-800 <?php echo basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'bg-purple-600' : ''; ?>">
            <i class="fas fa-shopping-cart w-6"></i>
            <span>Orders</span>
        </a>
        <a href="services.php" class="flex items-center px-6 py-3 hover:bg-gray-800 <?php echo basename($_SERVER['PHP_SELF']) == 'services.php' ? 'bg-purple-600' : ''; ?>">
            <i class="fas fa-briefcase w-6"></i>
            <span>Services</span>
        </a>
        <a href="../logout.php" class="flex items-center px-6 py-3 hover:bg-red-600 mt-6">
            <i class="fas fa-sign-out-alt w-6"></i>
            <span>Logout</span>
        </a>
    </nav>
</aside>
