<?php
/**
 * User Account Dashboard
 */
$user = $data['user'];
?>

<div class="bg-gray-50 dark:bg-black py-10 pb-8">
    <div class="max-w-[1280px] mx-auto px-4 sm:px-10">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-[#111712] dark:text-white">My Account</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Manage your profile, orders, and preferences</p>
            </div>
            <a href="/logout" class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-[#111712] border border-gray-200 dark:border-gray-800 rounded-lg text-red-600 hover:bg-red-50 dark:hover:bg-red-900/10 transition-colors shadow-sm font-medium">
                <span class="material-symbols-outlined text-[20px]">logout</span>
                Logout
            </a>
        </div>

        <!-- Success/Error Messages -->
        <?php if (Session::hasFlash('success')): ?>
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl flex items-center gap-3">
                <span class="material-symbols-outlined text-green-600">check_circle</span>
                <p class="text-green-700 dark:text-green-300 font-medium"><?= Session::getFlash('success') ?></p>
            </div>
        <?php endif; ?>

        <?php if (Session::hasFlash('error')): ?>
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl flex items-center gap-3">
                <span class="material-symbols-outlined text-red-600">error</span>
                <p class="text-red-700 dark:text-red-300 font-medium"><?= Session::getFlash('error') ?></p>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Sidebar Navigation -->
            <div class="md:col-span-1 space-y-6">
                <!-- User Card -->
                <div class="bg-white dark:bg-[#111712] rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm p-6 text-center">
                     <div class="w-20 h-20 mx-auto bg-gradient-to-br from-[#20df29] to-green-600 rounded-full flex items-center justify-center text-white text-2xl font-bold mb-4 shadow-lg shadow-green-500/30">
                        <?= strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)) ?>
                    </div>
                    <h2 class="text-lg font-bold text-[#111712] dark:text-white">
                        <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-3"><?= htmlspecialchars($user['email']) ?></p>
                    <span class="inline-flex px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 text-xs font-bold rounded-full uppercase tracking-wider">
                        <?= ucfirst($user['role']) ?>
                    </span>
                </div>

                <!-- Menu -->
                <nav class="bg-white dark:bg-[#111712] rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                    <a href="/account" class="flex items-center gap-3 px-5 py-3 bg-[#20df29]/10 text-[#20df29] font-bold border-l-4 border-[#20df29]">
                        <span class="material-symbols-outlined">dashboard</span>
                        Dashboard
                    </a>
                    <a href="/orders" class="flex items-center gap-3 px-5 py-3 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-[#111712] dark:hover:text-white transition-colors border-l-4 border-transparent">
                        <span class="material-symbols-outlined">shopping_bag</span>
                        My Orders
                    </a>
                    <a href="/favorites" class="flex items-center gap-3 px-5 py-3 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-[#111712] dark:hover:text-white transition-colors border-l-4 border-transparent">
                        <span class="material-symbols-outlined">favorite</span>
                        Wishlist
                    </a>
                     <a href="/account/profile" class="flex items-center gap-3 px-5 py-3 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-[#111712] dark:hover:text-white transition-colors border-l-4 border-transparent">
                        <span class="material-symbols-outlined">person</span>
                        Edit Profile
                    </a>
                    <a href="/account/addresses" class="flex items-center gap-3 px-5 py-3 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-[#111712] dark:hover:text-white transition-colors border-l-4 border-transparent">
                        <span class="material-symbols-outlined">location_on</span>
                        Addresses
                    </a>
                    <a href="/account/change-password" class="flex items-center gap-3 px-5 py-3 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-[#111712] dark:hover:text-white transition-colors border-l-4 border-transparent">
                        <span class="material-symbols-outlined">lock</span>
                        Change Password
                    </a>
                    <?php if ($user['role'] === 'admin'): ?>
                    <a href="/admin" class="flex items-center gap-3 px-5 py-3 text-white bg-gradient-to-r from-[#20df29] to-green-600 hover:from-green-600 hover:to-[#20df29] transition-all duration-300 border-l-4 border-white font-bold shadow-lg shadow-green-500/30">
                        <span class="material-symbols-outlined">admin_panel_settings</span>
                        Admin Panel
                    </a>
                    <?php endif; ?>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="md:col-span-3 space-y-6">
                <!-- Overview Stats -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-white dark:bg-[#111712] p-5 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm flex items-center gap-4">
                        <div class="p-3 bg-blue-50 dark:bg-blue-900/20 text-blue-600 rounded-lg">
                            <span class="material-symbols-outlined text-2xl">shopping_cart</span>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase">Total Orders</p>
                            <p class="text-xl font-bold text-[#111712] dark:text-white"><?= $data['totalOrders'] ?? 0 ?></p>
                        </div>
                    </div>
                     <div class="bg-white dark:bg-[#111712] p-5 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm flex items-center gap-4">
                        <div class="p-3 bg-purple-50 dark:bg-purple-900/20 text-purple-600 rounded-lg">
                            <span class="material-symbols-outlined text-2xl">favorite</span>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase">Wishlist Items</p>
                            <p class="text-xl font-bold text-[#111712] dark:text-white"><?= $data['wishlistCount'] ?? 0 ?></p>
                        </div>
                    </div>
                     <div class="bg-white dark:bg-[#111712] p-5 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm flex items-center gap-4">
                        <div class="p-3 bg-orange-50 dark:bg-orange-900/20 text-orange-600 rounded-lg">
                            <span class="material-symbols-outlined text-2xl">local_shipping</span>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase">Pending</p>
                            <p class="text-xl font-bold text-[#111712] dark:text-white"><?= $data['pendingOrders'] ?? 0 ?></p>
                        </div>
                    </div>
                </div>

                <!-- Account Info & Security -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Personal Info -->
                    <div class="bg-white dark:bg-[#111712] rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm p-6 h-full flex flex-col">
                        <div class="flex justify-between items-center mb-6">
                             <h3 class="text-lg font-bold text-[#111712] dark:text-white flex items-center gap-2">
                                <span class="material-symbols-outlined text-gray-400">badge</span>
                                Personal Info
                            </h3>
                            <a href="/account/profile" class="text-sm font-bold text-[#20df29] hover:underline">Edit</a>
                        </div>
                        
                        <div class="space-y-4 flex-1">
                            <div class="flex justify-between py-2 border-b border-gray-50 dark:border-gray-800">
                                <span class="text-gray-500 dark:text-gray-400">Full Name</span>
                                <span class="font-medium text-[#111712] dark:text-white"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-50 dark:border-gray-800">
                                <span class="text-gray-500 dark:text-gray-400">Email</span>
                                <span class="font-medium text-[#111712] dark:text-white"><?= htmlspecialchars($user['email']) ?></span>
                            </div>
                             <div class="flex justify-between py-2 border-b border-gray-50 dark:border-gray-800">
                                <span class="text-gray-500 dark:text-gray-400">Phone</span>
                                <span class="font-medium text-[#111712] dark:text-white"><?= htmlspecialchars($user['phone'] ?? 'Not set') ?></span>
                            </div>
                             <div class="flex justify-between py-2">
                                <span class="text-gray-500 dark:text-gray-400">Joined</span>
                                <span class="font-medium text-[#111712] dark:text-white"><?= date('M d, Y', strtotime($user['created_at'])) ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Security -->
                    <div class="bg-white dark:bg-[#111712] rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm p-6 h-full flex flex-col">
                         <div class="flex justify-between items-center mb-6">
                             <h3 class="text-lg font-bold text-[#111712] dark:text-white flex items-center gap-2">
                                <span class="material-symbols-outlined text-gray-400">security</span>
                                Security
                            </h3>
                            <a href="/account/change-password" class="text-sm font-bold text-[#20df29] hover:underline">Change Password</a>
                        </div>

                        <div class="space-y-4 flex-1">
                             <div class="flex justify-between py-2 border-b border-gray-50 dark:border-gray-800">
                                <span class="text-gray-500 dark:text-gray-400">Account Status</span>
                                <span class="inline-flex items-center gap-1.5 font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded-full text-xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span>
                                    <?= ucfirst($user['status']) ?>
                                </span>
                            </div>
                             <div class="flex justify-between py-2 border-b border-gray-50 dark:border-gray-800">
                                <span class="text-gray-500 dark:text-gray-400">Email Verification</span>
                                <?php if ($user['email_verified_at']): ?>
                                     <span class="font-bold text-green-600 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[16px]">verified</span> Verified
                                    </span>
                                <?php else: ?>
                                    <span class="font-bold text-amber-600 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[16px]">warning</span> Pending
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="text-gray-500 dark:text-gray-400">Last Login</span>
                                <span class="font-medium text-[#111712] dark:text-white"><?= $user['last_login_at'] ? date('M d, Y H:i', strtotime($user['last_login_at'])) : 'Never' ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="bg-white dark:bg-[#111712] rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm p-6">
                    <div class="flex justify-between items-center mb-6">
                         <h3 class="text-lg font-bold text-[#111712] dark:text-white">Recent Orders</h3>
                         <a href="/orders" class="text-sm font-bold text-[#20df29] hover:underline">View All</a>
                    </div>
                    
                    <div class="text-center py-8 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-dashed border-gray-200 dark:border-gray-700">
                        <div class="inline-flex p-4 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-400 mb-3">
                            <span class="material-symbols-outlined text-3xl">shopping_basket</span>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 font-medium mb-4">No recent orders found</p>
                        <a href="/products" class="inline-flex items-center gap-2 px-6 py-2 bg-[#111712] text-white font-bold rounded-lg hover:bg-black transition-colors">
                            Start Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
