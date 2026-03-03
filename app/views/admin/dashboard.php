<?php
$stats = $stats ?? [];
$users = $users ?? [];
$products = $products ?? [];
$lowStock = $lowStock ?? [];
$recentOrders = $recentOrders ?? [];
$categories = $categories ?? [];
$offers = $offers ?? [];
$allProducts = $allProducts ?? [];
$logs = $logs ?? [];
$userMap = $userMap ?? [];
$taxSettings = $taxSettings ?? [];
$changelog = $changelog ?? [];
$success = Session::getFlash('success');
$error = Session::getFlash('error');

// Extract tax values for the form
$globalTaxRate = isset($taxSettings['global_tax_rate']) ? $taxSettings['global_tax_rate']['setting_value'] : '10';
$taxEnabled = isset($taxSettings['tax_enabled']) ? $taxSettings['tax_enabled']['setting_value'] : '1';
$taxLabel = isset($taxSettings['tax_label']) ? $taxSettings['tax_label']['setting_value'] : 'Consumption Tax';
$taxIncluded = isset($taxSettings['tax_included_in_price']) ? $taxSettings['tax_included_in_price']['setting_value'] : '1';
?>

<div class="bg-gray-100 min-h-screen font-sans">

    <!-- Top Header Bar -->
    <header class="bg-gray-900 text-white px-6 py-4 flex justify-between items-center sticky top-0 z-50 shadow-lg">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center font-bold text-lg shadow-lg">A</div>
            <div>
                <h1 class="text-xl font-bold tracking-tight">ADI ARI Fresh</h1>
                <p class="text-xs text-gray-400 uppercase tracking-wider">Admin Command Center</p>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-400 hidden md:block"><?= date('l, M d, Y') ?></span>
            <a href="/" target="_blank" class="hidden sm:flex items-center gap-1 text-sm text-gray-300 hover:text-white transition px-3 py-1.5 rounded-lg border border-gray-700 hover:border-gray-500">
                <span class="material-symbols-outlined text-base">open_in_new</span>
                View Site
            </a>
            <a href="/manager" class="hidden sm:flex items-center gap-1 text-sm text-gray-300 hover:text-white transition px-3 py-1.5 rounded-lg border border-gray-700 hover:border-gray-500">
                <span class="material-symbols-outlined text-base">store</span>
                Manager
            </a>
            <a href="/logout" class="flex items-center gap-1 text-sm text-red-400 hover:text-red-300 transition px-3 py-1.5 rounded-lg border border-red-900/50 hover:border-red-700">
                <span class="material-symbols-outlined text-base">logout</span>
                <span class="hidden sm:inline">Logout</span>
            </a>
        </div>
    </header>

    <div class="max-w-[1800px] mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <!-- Flash Messages -->
        <?php if ($success): ?>
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-xl flex items-center gap-3 shadow-sm">
                <span class="material-symbols-outlined text-green-600">check_circle</span>
                <p class="text-green-700 font-medium"><?= htmlspecialchars($success) ?></p>
            </div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl flex items-center gap-3 shadow-sm">
                <span class="material-symbols-outlined text-red-600">error</span>
                <p class="text-red-700 font-medium"><?= htmlspecialchars($error) ?></p>
            </div>
        <?php endif; ?>

        <!-- ═══════════════════ STATS CARDS ═══════════════════ -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Users</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1"><?= number_format($stats['users'] ?? 0) ?></h3>
                    </div>
                    <div class="p-2 bg-blue-100 rounded-lg"><span class="material-symbols-outlined text-blue-600">people</span></div>
                </div>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Products</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1"><?= number_format($stats['products'] ?? 0) ?></h3>
                    </div>
                    <div class="p-2 bg-green-100 rounded-lg"><span class="material-symbols-outlined text-green-600">inventory_2</span></div>
                </div>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Today's Orders</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1"><?= number_format($stats['today_orders'] ?? 0) ?></h3>
                    </div>
                    <div class="p-2 bg-purple-100 rounded-lg"><span class="material-symbols-outlined text-purple-600">shopping_bag</span></div>
                </div>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Today's Revenue</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1">¥<?= number_format($stats['today_revenue'] ?? 0) ?></h3>
                    </div>
                    <div class="p-2 bg-yellow-100 rounded-lg"><span class="material-symbols-outlined text-yellow-600">payments</span></div>
                </div>
            </div>
        </div>

        <!-- ═══════════════════ TAB NAVIGATION ═══════════════════ -->
        <div class="bg-white rounded-t-xl shadow-sm border border-gray-200 border-b-0 overflow-x-auto">
            <nav class="flex" id="tabNav">
                <button onclick="switchTab('users')" data-tab="users" class="tab-btn active px-6 py-4 text-sm font-bold flex items-center gap-2 border-b-2 border-green-600 text-green-700 bg-green-50/50 whitespace-nowrap">
                    <span class="material-symbols-outlined text-lg">people</span>Users
                    <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2 py-0.5 rounded-full"><?= count($users) ?></span>
                </button>
                <button onclick="switchTab('products')" data-tab="products" class="tab-btn px-6 py-4 text-sm font-bold flex items-center gap-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition whitespace-nowrap">
                    <span class="material-symbols-outlined text-lg">inventory_2</span>Products
                    <span class="bg-gray-100 text-gray-600 text-xs font-bold px-2 py-0.5 rounded-full"><?= count($products) ?></span>
                </button>
                <button onclick="switchTab('orders')" data-tab="orders" class="tab-btn px-6 py-4 text-sm font-bold flex items-center gap-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition whitespace-nowrap">
                    <span class="material-symbols-outlined text-lg">shopping_bag</span>Orders
                    <span class="bg-gray-100 text-gray-600 text-xs font-bold px-2 py-0.5 rounded-full"><?= count($recentOrders) ?></span>
                </button>
                <button onclick="switchTab('categories')" data-tab="categories" class="tab-btn px-6 py-4 text-sm font-bold flex items-center gap-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition whitespace-nowrap">
                    <span class="material-symbols-outlined text-lg">category</span>Categories
                    <span class="bg-gray-100 text-gray-600 text-xs font-bold px-2 py-0.5 rounded-full"><?= count($categories) ?></span>
                </button>
                <button onclick="switchTab('offers')" data-tab="offers" class="tab-btn px-6 py-4 text-sm font-bold flex items-center gap-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition whitespace-nowrap">
                    <span class="material-symbols-outlined text-lg">sell</span>Offers
                    <span class="bg-gray-100 text-gray-600 text-xs font-bold px-2 py-0.5 rounded-full"><?= count($offers) ?></span>
                </button>
                <button onclick="switchTab('lowstock')" data-tab="lowstock" class="tab-btn px-6 py-4 text-sm font-bold flex items-center gap-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition whitespace-nowrap">
                    <span class="material-symbols-outlined text-lg">warning</span>Low Stock
                    <?php if (count($lowStock) > 0): ?>
                        <span class="bg-red-100 text-red-700 text-xs font-bold px-2 py-0.5 rounded-full"><?= count($lowStock) ?></span>
                    <?php endif; ?>
                </button>
                <button onclick="switchTab('logs')" data-tab="logs" class="tab-btn px-6 py-4 text-sm font-bold flex items-center gap-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition whitespace-nowrap">
                    <span class="material-symbols-outlined text-lg">list_alt</span>Logs
                </button>
                <button onclick="switchTab('tax')" data-tab="tax" class="tab-btn px-6 py-4 text-sm font-bold flex items-center gap-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition whitespace-nowrap">
                    <span class="material-symbols-outlined text-lg">receipt_long</span>Tax
                    <span class="bg-amber-100 text-amber-700 text-xs font-bold px-2 py-0.5 rounded-full"><?= $globalTaxRate ?>%</span>
                </button>
                <button onclick="switchTab('changelog')" data-tab="changelog" class="tab-btn px-6 py-4 text-sm font-bold flex items-center gap-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition whitespace-nowrap">
                    <span class="material-symbols-outlined text-lg">history</span>Changelog
                    <span class="bg-gray-100 text-gray-600 text-xs font-bold px-2 py-0.5 rounded-full"><?= count($changelog) ?></span>
                </button>
            </nav>
        </div>

        <!-- ═══════════════════ TAB CONTENT ═══════════════════ -->
        <div class="bg-white rounded-b-xl shadow-sm border border-gray-200 border-t-0 overflow-hidden">

            <!-- ──────── USERS TAB ──────── -->
            <div id="tab-users" class="tab-content">
                <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="font-bold text-gray-800">All Users</h3>
                    <a href="/admin/users" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                        <span class="material-symbols-outlined text-base">open_in_new</span>Full Page
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-bold border-b">
                            <tr>
                                <th class="px-5 py-3">User</th>
                                <th class="px-5 py-3">Email</th>
                                <th class="px-5 py-3">Role</th>
                                <th class="px-5 py-3">Joined</th>
                                <th class="px-5 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            <?php foreach ($users as $u): ?>
                                <tr class="hover:bg-gray-50 transition-colors group">
                                    <td class="px-5 py-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center text-gray-600 font-bold text-sm">
                                                <?= strtoupper(substr($u['first_name'], 0, 1)) ?>
                                            </div>
                                            <span class="font-semibold text-gray-900"><?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) ?></span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3 text-gray-600">
                                        <a href="mailto:<?= htmlspecialchars($u['email']) ?>" class="text-blue-600 hover:text-blue-800"><?= htmlspecialchars($u['email']) ?></a>
                                    </td>
                                    <td class="px-5 py-3">
                                        <form method="post" action="/admin/user/<?= (int)$u['id'] ?>/role" class="inline">
                                            <?= $this->csrfField() ?>
                                            <select name="role" onchange="this.form.submit()" class="text-xs font-bold px-2 py-1 rounded-full border-none cursor-pointer
                                                <?= ($u['role'] === 'admin') ? 'bg-purple-100 text-purple-700' : (($u['role'] === 'manager') ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700') ?>">
                                                <option value="customer" <?= ($u['role'] ?? '') === 'customer' ? 'selected' : '' ?>>Customer</option>
                                                <option value="manager" <?= ($u['role'] ?? '') === 'manager' ? 'selected' : '' ?>>Manager</option>
                                                <option value="admin" <?= ($u['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="px-5 py-3 text-gray-500 text-xs"><?= date('M d, Y', strtotime($u['created_at'] ?? 'now')) ?></td>
                                    <td class="px-5 py-3 text-right">
                                        <?php if ((int)$u['id'] !== (int)Session::get('user_id')): ?>
                                            <form method="post" action="/admin/user/<?= (int)$u['id'] ?>/delete" class="inline" onsubmit="return confirm('Delete this user?');">
                                                <?= $this->csrfField() ?>
                                                <button type="submit" class="p-1 text-gray-400 hover:text-red-500 transition opacity-0 group-hover:opacity-100">
                                                    <span class="material-symbols-outlined text-lg">delete</span>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ──────── PRODUCTS TAB ──────── -->
            <div id="tab-products" class="tab-content hidden">
                <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="font-bold text-gray-800">Recent Products</h3>
                    <div class="flex gap-2">
                        <a href="/manager/product/create" class="text-sm bg-green-600 text-white px-3 py-1.5 rounded-lg hover:bg-green-700 transition flex items-center gap-1 font-bold">
                            <span class="material-symbols-outlined text-base">add</span>Add Product
                        </a>
                        <a href="/manager/products" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1 px-3 py-1.5">
                            <span class="material-symbols-outlined text-base">open_in_new</span>Full Page
                        </a>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-bold border-b">
                            <tr>
                                <th class="px-5 py-3">Product</th>
                                <th class="px-5 py-3">SKU</th>
                                <th class="px-5 py-3">Price</th>
                                <th class="px-5 py-3">Stock</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            <?php foreach ($products as $p): ?>
                                <tr class="hover:bg-gray-50 transition-colors group">
                                    <td class="px-5 py-3">
                                        <div class="flex items-center gap-3">
                                                <img src="<?= htmlspecialchars($this->productImage($p['primary_image'] ?? null)) ?>" alt="" class="w-8 h-8 rounded-lg object-cover"
                                                     onerror="this.onerror=null;this.src='https://placehold.co/32x32?text=IMG';">
                                            <span class="font-semibold text-gray-900"><?= htmlspecialchars($p['name']) ?></span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3 text-xs text-gray-500 font-mono"><?= htmlspecialchars($p['sku'] ?? '-') ?></td>
                                    <td class="px-5 py-3 font-medium">¥<?= number_format($p['price'], 2) ?></td>
                                    <td class="px-5 py-3">
                                        <span class="<?= ($p['stock_quantity'] ?? 0) < 10 ? 'text-red-600 font-bold' : 'text-gray-700' ?>"><?= $p['stock_quantity'] ?? 0 ?></span>
                                    </td>
                                    <td class="px-5 py-3">
                                        <span class="px-2 py-0.5 rounded-full text-xs font-bold <?= ($p['status'] ?? '') === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' ?>"><?= ucfirst($p['status'] ?? 'active') ?></span>
                                    </td>
                                    <td class="px-5 py-3 text-right">
                                        <div class="flex justify-end gap-1 opacity-0 group-hover:opacity-100 transition">
                                            <a href="/manager/product/<?= $p['id'] ?>/edit" class="p-1 text-gray-400 hover:text-blue-600"><span class="material-symbols-outlined text-lg">edit</span></a>
                                            <form method="post" action="/manager/product/<?= $p['id'] ?>/delete" class="inline" onsubmit="return confirm('Delete this product?');">
                                                <?= $this->csrfField() ?>
                                                <button class="p-1 text-gray-400 hover:text-red-500"><span class="material-symbols-outlined text-lg">delete</span></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ──────── ORDERS TAB ──────── -->
            <div id="tab-orders" class="tab-content hidden">
                <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="font-bold text-gray-800">Recent Orders</h3>
                    <a href="/manager/orders" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                        <span class="material-symbols-outlined text-base">open_in_new</span>Full Page
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-bold border-b">
                            <tr>
                                <th class="px-5 py-3">Order #</th>
                                <th class="px-5 py-3">Customer</th>
                                <th class="px-5 py-3">Total</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3">Date</th>
                                <th class="px-5 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            <?php if (empty($recentOrders)): ?>
                                <tr><td colspan="6" class="px-5 py-8 text-center text-gray-400">No orders yet.</td></tr>
                            <?php else: ?>
                                <?php foreach ($recentOrders as $order): ?>
                                    <tr class="hover:bg-gray-50 transition-colors group">
                                        <td class="px-5 py-3 font-medium text-gray-900">#<?= htmlspecialchars($order['order_number'] ?? $order['id']) ?></td>
                                        <td class="px-5 py-3 text-gray-600"><?= htmlspecialchars(($order['first_name'] ?? '') . ' ' . ($order['last_name'] ?? '')) ?></td>
                                        <td class="px-5 py-3 font-medium">¥<?= number_format($order['total_amount'] ?? 0, 2) ?></td>
                                        <td class="px-5 py-3">
                                            <?php
                                            $sc = ['pending'=>'bg-yellow-100 text-yellow-700','confirmed'=>'bg-blue-100 text-blue-700','processing'=>'bg-purple-100 text-purple-700','shipped'=>'bg-indigo-100 text-indigo-700','delivered'=>'bg-green-100 text-green-700','cancelled'=>'bg-red-100 text-red-700'];
                                            $oc = $sc[$order['status'] ?? ''] ?? 'bg-gray-100 text-gray-700';
                                            ?>
                                            <span class="px-2 py-0.5 rounded-full text-xs font-bold <?= $oc ?>"><?= ucfirst($order['status'] ?? '-') ?></span>
                                        </td>
                                        <td class="px-5 py-3 text-xs text-gray-500"><?= date('M d, Y', strtotime($order['created_at'] ?? 'now')) ?></td>
                                        <td class="px-5 py-3 text-right">
                                            <a href="/manager/order/<?= $order['id'] ?>" class="text-blue-600 hover:text-blue-800 text-xs font-bold opacity-0 group-hover:opacity-100 transition">View</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ──────── CATEGORIES TAB ──────── -->
            <div id="tab-categories" class="tab-content hidden">
                <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="font-bold text-gray-800">Categories</h3>
                    <a href="/manager/categories" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                        <span class="material-symbols-outlined text-base">open_in_new</span>Full Page
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-bold border-b">
                            <tr>
                                <th class="px-5 py-3">Category</th>
                                <th class="px-5 py-3">Slug</th>
                                <th class="px-5 py-3">Products</th>
                                <th class="px-5 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            <?php if (empty($categories)): ?>
                                <tr><td colspan="4" class="px-5 py-8 text-center text-gray-400">No categories.</td></tr>
                            <?php else: ?>
                                <?php foreach ($categories as $cat): ?>
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-5 py-3 font-semibold text-gray-900"><?= htmlspecialchars($cat['name']) ?></td>
                                        <td class="px-5 py-3 text-xs font-mono text-gray-500"><?= htmlspecialchars($cat['slug'] ?? '-') ?></td>
                                        <td class="px-5 py-3">
                                            <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2 py-0.5 rounded-full"><?= $cat['product_count'] ?? 0 ?></span>
                                        </td>
                                        <td class="px-5 py-3">
                                            <span class="px-2 py-0.5 rounded-full text-xs font-bold <?= ($cat['status'] ?? 'active') === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' ?>"><?= ucfirst($cat['status'] ?? 'active') ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ──────── OFFERS TAB ──────── -->
            <div id="tab-offers" class="tab-content hidden">
                <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="font-bold text-gray-800">Weekly Deals / Offers</h3>
                    <div class="flex gap-2">
                        <button onclick="openOfferModal()" class="text-sm bg-green-600 text-white px-3 py-1.5 rounded-lg hover:bg-green-700 transition flex items-center gap-1 font-bold">
                            <span class="material-symbols-outlined text-base">add</span>Create Offer
                        </button>
                        <a href="/admin/offers" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1 px-3 py-1.5">
                            <span class="material-symbols-outlined text-base">open_in_new</span>Full Page
                        </a>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-bold border-b">
                            <tr>
                                <th class="px-5 py-3">Product</th>
                                <th class="px-5 py-3">Discount</th>
                                <th class="px-5 py-3">Period</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            <?php if (empty($offers)): ?>
                                <tr><td colspan="5" class="px-5 py-8 text-center text-gray-400">No offers yet.</td></tr>
                            <?php else: ?>
                                <?php foreach ($offers as $offer): ?>
                                    <tr class="hover:bg-gray-50 transition-colors group">
                                        <td class="px-5 py-3">
                                            <div class="flex items-center gap-2">
                                                    <img src="<?= htmlspecialchars($this->productImage($offer['primary_image'] ?? null)) ?>" alt="" class="w-8 h-8 rounded object-cover"
                                                         onerror="this.onerror=null;this.src='https://placehold.co/32x32?text=IMG';">
                                                <span class="font-semibold text-gray-900"><?= htmlspecialchars($offer['product_name'] ?? '-') ?></span>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3">
                                            <span class="text-green-600 font-bold">
                                                <?= $offer['discount_type'] === 'percentage' ? $offer['discount_value'] . '% OFF' : '¥' . number_format($offer['discount_value'], 2) . ' OFF' ?>
                                            </span>
                                        </td>
                                        <td class="px-5 py-3 text-xs text-gray-500"><?= date('M d', strtotime($offer['start_date'])) ?> - <?= date('M d, Y', strtotime($offer['end_date'])) ?></td>
                                        <td class="px-5 py-3">
                                            <?php $osc = ['active'=>'bg-green-100 text-green-700','inactive'=>'bg-gray-100 text-gray-600','expired'=>'bg-red-100 text-red-700'][$offer['status']] ?? 'bg-gray-100 text-gray-600'; ?>
                                            <span class="px-2 py-0.5 rounded-full text-xs font-bold <?= $osc ?>"><?= ucfirst($offer['status']) ?></span>
                                        </td>
                                        <td class="px-5 py-3 text-right">
                                            <form method="post" action="/admin/offer/<?= $offer['id'] ?>/delete" class="inline" onsubmit="return confirm('Delete this offer?');">
                                                <?= $this->csrfField() ?>
                                                <button class="p-1 text-gray-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition"><span class="material-symbols-outlined text-lg">delete</span></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ──────── LOW STOCK TAB ──────── -->
            <div id="tab-lowstock" class="tab-content hidden">
                <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <span class="material-symbols-outlined text-red-500">warning</span>
                        Low Stock Alert
                    </h3>
                    <a href="/manager/inventory" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                        <span class="material-symbols-outlined text-base">open_in_new</span>Inventory
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-red-50 text-xs uppercase text-red-600 font-bold border-b border-red-100">
                            <tr>
                                <th class="px-5 py-3">Product</th>
                                <th class="px-5 py-3">SKU</th>
                                <th class="px-5 py-3">Current Stock</th>
                                <th class="px-5 py-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            <?php if (empty($lowStock)): ?>
                                <tr><td colspan="4" class="px-5 py-8 text-center text-green-600 font-medium">✅ All products are well stocked!</td></tr>
                            <?php else: ?>
                                <?php foreach ($lowStock as $ls): ?>
                                    <tr class="hover:bg-red-50/50 transition-colors">
                                        <td class="px-5 py-3 font-semibold text-gray-900"><?= htmlspecialchars($ls['name']) ?></td>
                                        <td class="px-5 py-3 text-xs font-mono text-gray-500"><?= htmlspecialchars($ls['sku'] ?? '-') ?></td>
                                        <td class="px-5 py-3">
                                            <span class="text-red-600 font-bold text-lg"><?= $ls['stock_quantity'] ?? 0 ?></span>
                                        </td>
                                        <td class="px-5 py-3 text-right">
                                            <a href="/manager/product/<?= $ls['id'] ?>/edit" class="text-sm text-blue-600 hover:text-blue-800 font-bold">Restock</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ──────── ACTIVITY LOGS TAB ──────── -->
            <div id="tab-logs" class="tab-content hidden">
                <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="font-bold text-gray-800">Recent Activity Logs</h3>
                    <a href="/admin/logs" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                        <span class="material-symbols-outlined text-base">open_in_new</span>Full Logs
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-bold border-b">
                            <tr>
                                <th class="px-5 py-3">User</th>
                                <th class="px-5 py-3">Activity</th>
                                <th class="px-5 py-3">Page</th>
                                <th class="px-5 py-3">Details</th>
                                <th class="px-5 py-3">Time</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            <?php if (empty($logs)): ?>
                                <tr><td colspan="5" class="px-5 py-8 text-center text-gray-400">No activity logs.</td></tr>
                            <?php else: ?>
                                <?php foreach ($logs as $log): ?>
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-5 py-3">
                                            <?php if ($log['user_id']): ?>
                                                <?php $lu = $userMap[$log['user_id']] ?? null; ?>
                                                <?php if ($lu): ?>
                                                    <div class="font-semibold text-gray-900 text-xs"><?= htmlspecialchars($lu['first_name'] . ' ' . $lu['last_name']) ?></div>
                                                    <a href="mailto:<?= htmlspecialchars($lu['email']) ?>" class="text-blue-600 hover:text-blue-800 text-xs flex items-center gap-0.5">
                                                        <span class="material-symbols-outlined" style="font-size:12px">mail</span>
                                                        <?= htmlspecialchars($lu['email']) ?>
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-gray-500 text-xs">User #<?= $log['user_id'] ?></span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-gray-400 text-xs">Guest</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-5 py-3">
                                            <?php
                                            $badges = ['user_login'=>'bg-green-100 text-green-800','user_logout'=>'bg-red-100 text-red-800','user_registered'=>'bg-blue-100 text-blue-800','product_view'=>'bg-purple-100 text-purple-800','cart_add'=>'bg-yellow-100 text-yellow-800','order_placed'=>'bg-indigo-100 text-indigo-800'];
                                            $bc = $badges[$log['activity_type']] ?? 'bg-gray-100 text-gray-800';
                                            ?>
                                            <span class="px-2 py-0.5 text-xs font-bold rounded-full <?= $bc ?>"><?= ucwords(str_replace('_', ' ', $log['activity_type'])) ?></span>
                                        </td>
                                        <td class="px-5 py-3 text-xs text-gray-500 max-w-[200px] truncate">
                                            <?= htmlspecialchars($log['page_url'] ?? '-') ?>
                                        </td>
                                        <td class="px-5 py-3">
                                            <?php if ($log['metadata']): ?>
                                                <details class="cursor-pointer">
                                                    <summary class="text-blue-600 hover:text-blue-800 text-xs">View</summary>
                                                    <pre class="mt-1 text-xs bg-gray-50 p-2 rounded overflow-x-auto max-w-[300px]"><?= htmlspecialchars(json_encode(json_decode($log['metadata']), JSON_PRETTY_PRINT)) ?></pre>
                                                </details>
                                            <?php else: ?>
                                                <span class="text-gray-400 text-xs">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-5 py-3 text-xs text-gray-500 whitespace-nowrap">
                                            <?php
                                            $time = strtotime($log['created_at']);
                                            $diff = time() - $time;
                                            if ($diff < 60) echo "<span class='text-green-600 font-medium'>Just now</span>";
                                            elseif ($diff < 3600) echo "<span class='text-blue-600'>" . floor($diff/60) . "m ago</span>";
                                            elseif ($diff < 86400) echo "<span class='text-purple-600'>" . floor($diff/3600) . "h ago</span>";
                                            else echo date('M j, g:i A', $time);
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ──────── TAX & SETTINGS TAB ──────── -->
            <div id="tab-tax" class="tab-content hidden">
                <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-amber-50">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <span class="material-symbols-outlined text-amber-600">receipt_long</span>
                        Tax Configuration
                    </h3>
                </div>

                <div class="p-6">
                    <!-- Global Tax Settings Form -->
                    <div class="max-w-2xl">
                        <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl border border-amber-200 p-6 mb-8">
                            <h4 class="text-lg font-bold text-gray-900 mb-1 flex items-center gap-2">
                                <span class="material-symbols-outlined text-amber-600">settings</span>
                                Global Tax Settings
                            </h4>
                            <p class="text-sm text-gray-500 mb-5">These settings apply to all products unless overridden per-product below.</p>
                            <form method="post" action="/admin/tax/update">
                                <?= Security::getCsrfField() ?>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Tax Rate (%)</label>
                                        <div class="relative">
                                            <input type="number" name="global_tax_rate" step="0.01" min="0" max="100" value="<?= htmlspecialchars($globalTaxRate) ?>" 
                                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent text-lg font-bold" required>
                                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 font-bold">%</span>
                                        </div>
                                        <p class="text-xs text-gray-400 mt-1">Japan consumption tax is 10%</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Tax Label</label>
                                        <input type="text" name="tax_label" value="<?= htmlspecialchars($taxLabel) ?>" 
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent" placeholder="e.g. Consumption Tax">
                                        <p class="text-xs text-gray-400 mt-1">Shown on invoices & receipts</p>
                                    </div>
                                </div>
                                <div class="flex flex-wrap gap-6 mb-5">
                                    <label class="flex items-center gap-3 cursor-pointer bg-white px-4 py-3 rounded-lg border border-gray-200 hover:border-amber-400 transition">
                                        <input type="checkbox" name="tax_enabled" value="1" <?= $taxEnabled ? 'checked' : '' ?> 
                                            class="w-5 h-5 rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                                        <div>
                                            <span class="font-bold text-sm text-gray-700">Tax Enabled</span>
                                            <p class="text-xs text-gray-400">Calculate and apply tax to orders</p>
                                        </div>
                                    </label>
                                    <label class="flex items-center gap-3 cursor-pointer bg-white px-4 py-3 rounded-lg border border-gray-200 hover:border-amber-400 transition">
                                        <input type="checkbox" name="tax_included_in_price" value="1" <?= $taxIncluded ? 'checked' : '' ?> 
                                            class="w-5 h-5 rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                                        <div>
                                            <span class="font-bold text-sm text-gray-700">Tax Included In Price</span>
                                            <p class="text-xs text-gray-400">Product prices already include tax</p>
                                        </div>
                                    </label>
                                </div>
                                <button type="submit" class="px-6 py-2.5 bg-amber-600 text-white rounded-lg font-bold hover:bg-amber-700 transition flex items-center gap-2 shadow-sm">
                                    <span class="material-symbols-outlined text-base">save</span>
                                    Save Tax Settings
                                </button>
                            </form>
                        </div>

                        <!-- Per-Product Tax Overrides -->
                        <h4 class="text-lg font-bold text-gray-900 mb-1 flex items-center gap-2">
                            <span class="material-symbols-outlined text-blue-600">tune</span>
                            Per-Product Tax Rates
                        </h4>
                        <p class="text-sm text-gray-500 mb-4">Override the global tax rate for specific products. Leave blank to use the global rate (<?= htmlspecialchars($globalTaxRate) ?>%).</p>
                    </div>

                    <div class="overflow-x-auto border rounded-xl">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-bold border-b">
                                <tr>
                                    <th class="px-5 py-3">Product</th>
                                    <th class="px-5 py-3">Price</th>
                                    <th class="px-5 py-3">Current Tax Rate</th>
                                    <th class="px-5 py-3">Set Custom Rate</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                <?php foreach ($allProducts as $ap): ?>
                                    <tr class="hover:bg-gray-50 transition-colors group">
                                        <td class="px-5 py-3">
                                            <div class="flex items-center gap-3">
                                                    <img src="<?= htmlspecialchars($this->productImage($ap['primary_image'] ?? null)) ?>" alt="" class="w-8 h-8 rounded-lg object-cover"
                                                         onerror="this.onerror=null;this.src='https://placehold.co/32x32?text=IMG';">
                                                <span class="font-semibold text-gray-900"><?= htmlspecialchars($ap['name']) ?></span>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3 font-medium">¥<?= number_format($ap['price'], 2) ?></td>
                                        <td class="px-5 py-3">
                                            <?php if (isset($ap['tax_rate']) && $ap['tax_rate'] !== null && $ap['tax_rate'] !== ''): ?>
                                                <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2 py-0.5 rounded-full"><?= $ap['tax_rate'] ?>% (custom)</span>
                                            <?php else: ?>
                                                <span class="bg-gray-100 text-gray-600 text-xs font-bold px-2 py-0.5 rounded-full"><?= $globalTaxRate ?>% (global)</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-5 py-3">
                                            <form method="post" action="/admin/tax/product/<?= $ap['id'] ?>" class="flex items-center gap-2">
                                                <?= Security::getCsrfField() ?>
                                                <input type="number" name="tax_rate" step="0.01" min="0" max="100" 
                                                    value="<?= isset($ap['tax_rate']) && $ap['tax_rate'] !== null ? htmlspecialchars($ap['tax_rate']) : '' ?>" 
                                                    placeholder="<?= $globalTaxRate ?>"
                                                    class="w-24 px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                <button type="submit" class="p-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition opacity-0 group-hover:opacity-100"
                                                    title="Update tax rate">
                                                    <span class="material-symbols-outlined text-base">save</span>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ──────── CHANGELOG TAB ──────── -->
            <div id="tab-changelog" class="tab-content hidden">
                <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <span class="material-symbols-outlined text-indigo-600">history</span>
                        Development Changelog
                    </h3>
                    <button onclick="openChangelogModal()" class="text-sm bg-indigo-600 text-white px-3 py-1.5 rounded-lg hover:bg-indigo-700 transition flex items-center gap-1 font-bold">
                        <span class="material-symbols-outlined text-base">add</span>Add Entry
                    </button>
                </div>
                <div class="p-6">
                    <?php if (empty($changelog)): ?>
                        <p class="text-center text-gray-400 py-8">No changelog entries yet.</p>
                    <?php else: ?>
                        <div class="space-y-4 max-w-3xl">
                            <?php foreach ($changelog as $entry): ?>
                                <?php
                                $typeStyles = [
                                    'feature' => ['bg-green-100 text-green-800', 'rocket_launch', 'border-green-200'],
                                    'fix' => ['bg-red-100 text-red-800', 'bug_report', 'border-red-200'],
                                    'improvement' => ['bg-blue-100 text-blue-800', 'trending_up', 'border-blue-200'],
                                    'breaking' => ['bg-orange-100 text-orange-800', 'warning', 'border-orange-200'],
                                ];
                                $ts = $typeStyles[$entry['change_type']] ?? $typeStyles['feature'];
                                ?>
                                <div class="bg-white rounded-xl border <?= $ts[2] ?> p-5 hover:shadow-md transition-shadow">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-2 flex-wrap">
                                                <?php if (!empty($entry['version'])): ?>
                                                    <span class="bg-gray-900 text-white text-xs font-bold px-2.5 py-0.5 rounded-full">v<?= htmlspecialchars($entry['version']) ?></span>
                                                <?php endif; ?>
                                                <span class="<?= $ts[0] ?> text-xs font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                                                    <span class="material-symbols-outlined" style="font-size:14px"><?= $ts[1] ?></span>
                                                    <?= ucfirst($entry['change_type']) ?>
                                                </span>
                                            </div>
                                            <h4 class="font-bold text-gray-900 text-base"><?= htmlspecialchars($entry['title']) ?></h4>
                                            <?php if (!empty($entry['description'])): ?>
                                                <p class="text-sm text-gray-600 mt-1 leading-relaxed"><?= nl2br(htmlspecialchars($entry['description'])) ?></p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-right text-xs text-gray-400 whitespace-nowrap pt-1">
                                            <div><?= date('M d, Y', strtotime($entry['created_at'])) ?></div>
                                            <?php if (!empty($entry['changed_by'])): ?>
                                                <div class="mt-0.5">by <?= htmlspecialchars($entry['changed_by']) ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div><!-- end tab content wrapper -->

    </div><!-- end container -->
</div>

<!-- ═══════════════════ CREATE OFFER MODAL ═══════════════════ -->
<div id="offerModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4">
        <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-t-2xl">
            <h2 class="text-lg font-bold">Create New Offer</h2>
            <button onclick="closeOfferModal()" class="text-white/80 hover:text-white"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form action="/admin/offer/create" method="POST" class="p-6 space-y-4">
            <?= Security::getCsrfField() ?>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Product</label>
                <select name="product_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">Select a product</option>
                    <?php foreach ($allProducts as $ap): ?>
                        <option value="<?= $ap['id'] ?>"><?= htmlspecialchars($ap['name']) ?> (¥<?= number_format($ap['price'], 2) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Discount Type</label>
                    <select name="discount_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="percentage">Percentage (%)</option>
                        <option value="fixed">Fixed Amount (¥)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Value</label>
                    <input type="number" step="0.01" name="discount_value" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Start Date</label>
                    <input type="date" name="start_date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">End Date</label>
                    <input type="date" name="end_date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="flex justify-end gap-3 pt-3">
                <button type="button" onclick="closeOfferModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-bold hover:bg-gray-50">Cancel</button>
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg font-bold hover:bg-green-700">Create</button>
            </div>
        </form>
    </div>
</div>

<!-- ═══════════════════ ADD CHANGELOG MODAL ═══════════════════ -->
<div id="changelogModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4">
        <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-t-2xl">
            <h2 class="text-lg font-bold">Add Changelog Entry</h2>
            <button onclick="closeChangelogModal()" class="text-white/80 hover:text-white"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form action="/admin/changelog/add" method="POST" class="p-6 space-y-4">
            <?= Security::getCsrfField() ?>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Version</label>
                    <input type="text" name="version" placeholder="e.g. 1.6.0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Type</label>
                    <select name="change_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="feature">🚀 Feature</option>
                        <option value="fix">🐛 Fix</option>
                        <option value="improvement">📈 Improvement</option>
                        <option value="breaking">⚠️ Breaking Change</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Title *</label>
                <input type="text" name="title" required placeholder="Short description of change" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3" placeholder="Detailed description of what changed..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"></textarea>
            </div>
            <div class="flex justify-end gap-3 pt-3">
                <button type="button" onclick="closeChangelogModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-bold hover:bg-gray-50">Cancel</button>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700">Add Entry</button>
            </div>
        </form>
    </div>
</div>

<script>
// ═══════════ Tab Switching ═══════════
function switchTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(t => t.classList.add('hidden'));
    // Remove active from all buttons
    document.querySelectorAll('.tab-btn').forEach(b => {
        b.classList.remove('border-green-600', 'text-green-700', 'bg-green-50/50');
        b.classList.add('border-transparent', 'text-gray-500');
    });
    // Show selected tab
    document.getElementById('tab-' + tabName).classList.remove('hidden');
    // Activate button
    const activeBtn = document.querySelector(`[data-tab="${tabName}"]`);
    activeBtn.classList.add('border-green-600', 'text-green-700', 'bg-green-50/50');
    activeBtn.classList.remove('border-transparent', 'text-gray-500');
}

// ═══════════ Offer Modal ═══════════
function openOfferModal() {
    document.getElementById('offerModal').classList.remove('hidden');
    document.getElementById('offerModal').classList.add('flex');
}
function closeOfferModal() {
    document.getElementById('offerModal').classList.add('hidden');
    document.getElementById('offerModal').classList.remove('flex');
}

// ═══════════ Changelog Modal ═══════════
function openChangelogModal() {
    document.getElementById('changelogModal').classList.remove('hidden');
    document.getElementById('changelogModal').classList.add('flex');
}
function closeChangelogModal() {
    document.getElementById('changelogModal').classList.add('hidden');
    document.getElementById('changelogModal').classList.remove('flex');
}

// Close modals on escape
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        closeOfferModal();
        closeChangelogModal();
    }
});
document.getElementById('offerModal')?.addEventListener('click', function(e) { if (e.target === this) closeOfferModal(); });
document.getElementById('changelogModal')?.addEventListener('click', function(e) { if (e.target === this) closeChangelogModal(); });
</script>
