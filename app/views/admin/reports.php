<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$nav = function (string $path) use ($currentPath) {
    return $currentPath === $path
        ? 'flex items-center px-4 py-3 bg-gray-800 text-white rounded-lg'
        : 'flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 rounded-lg transition';
};
?>
<div class="bg-gray-100 min-h-screen">
    <div class="flex h-screen overflow-hidden">
        <aside class="w-64 bg-gray-900 text-white flex-shrink-0">
            <div class="p-6"><h2 class="text-2xl font-bold">Admin</h2><p class="text-xs text-gray-400 mt-1">ADI ARI Fresh</p></div>
            <nav class="mt-6 px-4 space-y-2">
                <a href="/admin" class="<?= $nav('/admin') ?>"><span class="material-symbols-outlined mr-3">dashboard</span>Dashboard</a>
                <a href="/manager/products" class="<?= $nav('/manager/products') ?>"><span class="material-symbols-outlined mr-3">inventory_2</span>Products</a>
                <a href="/manager/categories" class="<?= $nav('/manager/categories') ?>"><span class="material-symbols-outlined mr-3">category</span>Categories</a>
                <a href="/admin/offers" class="<?= $nav('/admin/offers') ?>"><span class="material-symbols-outlined mr-3">sell</span>Weekly Deals</a>
                <a href="/admin/users" class="<?= $nav('/admin/users') ?>"><span class="material-symbols-outlined mr-3">people</span>Users</a>
                <a href="/admin/settings" class="<?= $nav('/admin/settings') ?>"><span class="material-symbols-outlined mr-3">settings</span>Settings</a>
                <a href="/admin/analytics" class="<?= $nav('/admin/analytics') ?>"><span class="material-symbols-outlined mr-3">analytics</span>Analytics</a>
                <a href="/admin/reports" class="<?= $nav('/admin/reports') ?>"><span class="material-symbols-outlined mr-3">summarize</span>Reports</a>
                <a href="/admin/coupons" class="<?= $nav('/admin/coupons') ?>"><span class="material-symbols-outlined mr-3">local_offer</span>Coupons</a>
                <a href="/admin/logs" class="<?= $nav('/admin/logs') ?>"><span class="material-symbols-outlined mr-3">list_alt</span>Logs</a>
                <a href="/logout" class="flex items-center px-4 py-3 text-red-400 hover:bg-red-900/30 rounded-lg mt-8 transition"><span class="material-symbols-outlined mr-3">logout</span>Logout</a>
            </nav>
        </aside>
        <div class="flex-1 overflow-auto">
            <header class="bg-white shadow-sm p-6"><h1 class="text-2xl font-bold text-gray-900">Reports</h1></header>
            <main class="p-8">
                <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100 text-center text-gray-500">Sales and custom reports can be added here.</div>
            </main>
        </div>
    </div>
</div>
