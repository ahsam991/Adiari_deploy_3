<?php
$orders = $orders ?? [];
$filterStatus = $filterStatus ?? null;
$success = Session::getFlash('success');
$error = Session::getFlash('error');
?>
<div class="bg-gray-100 min-h-screen">
    <div class="flex h-screen overflow-hidden">
        <aside class="w-64 bg-gray-800 text-white flex-shrink-0">
            <div class="p-6">
                <h2 class="text-2xl font-bold tracking-tight">Manager Panel</h2>
                <p class="text-xs text-gray-400 mt-1">ADI ARI Fresh</p>
            </div>
            <nav class="mt-6 px-4 space-y-2">
                <a href="/manager" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition"><span class="material-symbols-outlined mr-3">dashboard</span>Dashboard</a>
                <a href="/manager/products" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition"><span class="material-symbols-outlined mr-3">inventory_2</span>Products</a>
                <a href="/manager/categories" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition"><span class="material-symbols-outlined mr-3">category</span>Categories</a>
                <a href="/manager/orders" class="flex items-center px-4 py-3 bg-gray-900 text-white rounded-lg"><span class="material-symbols-outlined mr-3">shopping_bag</span>Orders</a>
                <a href="/manager/inventory" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition"><span class="material-symbols-outlined mr-3">inventory</span>Inventory</a>
                <a href="/logout" class="flex items-center px-4 py-3 text-red-400 hover:bg-red-900/30 rounded-lg mt-8 transition"><span class="material-symbols-outlined mr-3">logout</span>Logout</a>
            </nav>
        </aside>
        <div class="flex-1 overflow-auto">
            <header class="bg-white shadow-sm p-6 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-900">Order Management</h1>
            </header>
            <main class="p-8">
                <?php if ($success): ?>
                    <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg"><p class="text-green-700"><?= htmlspecialchars($success) ?></p></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg"><p class="text-red-700"><?= htmlspecialchars($error) ?></p></div>
                <?php endif; ?>

                <div class="mb-4 flex gap-2 flex-wrap">
                    <a href="/manager/orders" class="px-3 py-1.5 rounded-lg text-sm font-medium <?= !$filterStatus ? 'bg-gray-800 text-white' : 'bg-white text-gray-700 border hover:bg-gray-50' ?>">All</a>
                    <a href="/manager/orders?status=pending" class="px-3 py-1.5 rounded-lg text-sm font-medium <?= $filterStatus === 'pending' ? 'bg-gray-800 text-white' : 'bg-white text-gray-700 border hover:bg-gray-50' ?>">Pending</a>
                    <a href="/manager/orders?status=confirmed" class="px-3 py-1.5 rounded-lg text-sm font-medium <?= $filterStatus === 'confirmed' ? 'bg-gray-800 text-white' : 'bg-white text-gray-700 border hover:bg-gray-50' ?>">Confirmed</a>
                    <a href="/manager/orders?status=shipped" class="px-3 py-1.5 rounded-lg text-sm font-medium <?= $filterStatus === 'shipped' ? 'bg-gray-800 text-white' : 'bg-white text-gray-700 border hover:bg-gray-50' ?>">Shipped</a>
                    <a href="/manager/orders?status=delivered" class="px-3 py-1.5 rounded-lg text-sm font-medium <?= $filterStatus === 'delivered' ? 'bg-gray-800 text-white' : 'bg-white text-gray-700 border hover:bg-gray-50' ?>">Delivered</a>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left px-4 py-3 text-sm font-semibold text-gray-700">Order</th>
                                <th class="text-left px-4 py-3 text-sm font-semibold text-gray-700">Customer</th>
                                <th class="text-left px-4 py-3 text-sm font-semibold text-gray-700">Date</th>
                                <th class="text-left px-4 py-3 text-sm font-semibold text-gray-700">Status</th>
                                <th class="text-right px-4 py-3 text-sm font-semibold text-gray-700">Total</th>
                                <th class="text-right px-4 py-3 text-sm font-semibold text-gray-700">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php foreach ($orders as $o): ?>
                                <tr>
                                    <td class="px-4 py-3 font-medium"><?= htmlspecialchars($o['order_number']) ?></td>
                                    <td class="px-4 py-3 text-sm"><?= htmlspecialchars(($o['shipping_first_name'] ?? $o['first_name'] ?? '') . ' ' . ($o['shipping_last_name'] ?? $o['last_name'] ?? '')) ?></td>
                                    <td class="px-4 py-3 text-sm text-gray-600"><?= date('M j, Y H:i', strtotime($o['created_at'])) ?></td>
                                    <td class="px-4 py-3"><span class="px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800"><?= ucfirst($o['status']) ?></span></td>
                                    <td class="px-4 py-3 text-right font-medium">¥<?= number_format($o['total_amount']) ?></td>
                                    <td class="px-4 py-3 text-right"><a href="/manager/order/<?= (int)$o['id'] ?>" class="text-green-600 hover:underline text-sm">View</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php if (empty($orders)): ?>
                        <p class="p-8 text-center text-gray-500">No orders found.</p>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>
</div>
