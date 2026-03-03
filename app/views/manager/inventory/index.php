<?php
$products = $products ?? [];
$lowStock = $lowStock ?? [];
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
                <a href="/manager/orders" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition"><span class="material-symbols-outlined mr-3">shopping_bag</span>Orders</a>
                <a href="/manager/inventory" class="flex items-center px-4 py-3 bg-gray-900 text-white rounded-lg"><span class="material-symbols-outlined mr-3">inventory</span>Inventory</a>
                <a href="/logout" class="flex items-center px-4 py-3 text-red-400 hover:bg-red-900/30 rounded-lg mt-8 transition"><span class="material-symbols-outlined mr-3">logout</span>Logout</a>
            </nav>
        </aside>
        <div class="flex-1 overflow-auto">
            <header class="bg-white shadow-sm p-6">
                <h1 class="text-2xl font-bold text-gray-900">Inventory</h1>
            </header>
            <main class="p-8">
                <?php if ($success): ?>
                    <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg"><p class="text-green-700"><?= htmlspecialchars($success) ?></p></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg"><p class="text-red-700"><?= htmlspecialchars($error) ?></p></div>
                <?php endif; ?>

                <?php if (!empty($lowStock)): ?>
                    <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                        <h2 class="font-bold text-amber-800 mb-2">Low Stock (<?= count($lowStock) ?>)</h2>
                        <ul class="text-sm text-amber-700">
                            <?php foreach (array_slice($lowStock, 0, 5) as $p): ?>
                                <li><?= htmlspecialchars($p['name']) ?>: <?= (int)($p['stock_quantity'] ?? 0) ?> left</li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left px-4 py-3 text-sm font-semibold text-gray-700">Product</th>
                                <th class="text-left px-4 py-3 text-sm font-semibold text-gray-700">SKU</th>
                                <th class="text-left px-4 py-3 text-sm font-semibold text-gray-700">Stock</th>
                                <th class="text-left px-4 py-3 text-sm font-semibold text-gray-700">Min Level</th>
                                <th class="text-right px-4 py-3 text-sm font-semibold text-gray-700">Update</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php foreach ($products as $p): ?>
                                <tr>
                                    <td class="px-4 py-3 font-medium"><?= htmlspecialchars($p['name']) ?></td>
                                    <td class="px-4 py-3 text-sm text-gray-600"><?= htmlspecialchars($p['sku'] ?? '') ?></td>
                                    <td class="px-4 py-3">
                                        <span class="<?= (int)($p['stock_quantity'] ?? 0) <= (int)($p['min_stock_level'] ?? 0) ? 'text-red-600 font-medium' : 'text-gray-700' ?>"><?= (int)($p['stock_quantity'] ?? 0) ?></span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600"><?= (int)($p['min_stock_level'] ?? 0) ?></td>
                                    <td class="px-4 py-3 text-right">
                                        <form method="post" action="/manager/inventory/update" class="inline flex items-center justify-end gap-2">
                                            <?= $this->csrfField() ?>
                                            <input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>">
                                            <input type="number" name="quantity" value="<?= (int)($p['stock_quantity'] ?? 0) ?>" min="0" class="w-20 px-2 py-1 border border-gray-300 rounded text-sm">
                                            <button type="submit" class="px-2 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700">Save</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php if (empty($products)): ?>
                        <p class="p-8 text-center text-gray-500">No products.</p>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>
</div>
