<?php
$order = $order ?? [];
$items = $order['items'] ?? [];
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
                <h1 class="text-2xl font-bold text-gray-900">Order <?= htmlspecialchars($order['order_number']) ?></h1>
                <a href="/manager/orders" class="text-gray-600 hover:text-gray-900 text-sm">← Back to Orders</a>
            </header>
            <main class="p-8">
                <?php if ($success): ?>
                    <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg"><p class="text-green-700"><?= htmlspecialchars($success) ?></p></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg"><p class="text-red-700"><?= htmlspecialchars($error) ?></p></div>
                <?php endif; ?>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                            <h2 class="text-lg font-bold text-gray-900 mb-4">Update Status</h2>
                            <form method="post" action="/manager/order/<?= (int)$order['id'] ?>/status" class="flex gap-2 items-center">
                                <?= $this->csrfField() ?>
                                <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg">
                                    <?php foreach (['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'] as $s): ?>
                                        <option value="<?= $s ?>" <?= ($order['status'] ?? '') === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Update</button>
                            </form>
                        </div>
                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                            <h2 class="text-lg font-bold text-gray-900 mb-4">Shipping Address</h2>
                            <p class="text-gray-700 text-sm">
                                <?= htmlspecialchars($order['shipping_first_name'] . ' ' . $order['shipping_last_name']) ?><br>
                                <?= htmlspecialchars($order['shipping_email']) ?><br>
                                <?= htmlspecialchars($order['shipping_phone']) ?><br>
                                <?= htmlspecialchars($order['shipping_address_line1']) ?><?= !empty($order['shipping_address_line2']) ? ', ' . htmlspecialchars($order['shipping_address_line2']) : '' ?><br>
                                <?= htmlspecialchars($order['shipping_city']) ?>, <?= htmlspecialchars($order['shipping_state'] ?? '') ?> <?= htmlspecialchars($order['shipping_postal_code'] ?? '') ?><br>
                                <?= htmlspecialchars($order['shipping_country']) ?>
                            </p>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Items</h2>
                        <ul class="space-y-3">
                            <?php foreach ($items as $item): ?>
                                <li class="flex justify-between text-sm py-2 border-b border-gray-100">
                                    <span><?= htmlspecialchars($item['product_name']) ?> × <?= (int)$item['quantity'] ?></span>
                                    <span>¥<?= number_format($item['total_price']) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="mt-4 pt-4 border-t font-bold flex justify-between">
                            <span>Total</span>
                            <span>¥<?= number_format($order['total_amount']) ?></span>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>
