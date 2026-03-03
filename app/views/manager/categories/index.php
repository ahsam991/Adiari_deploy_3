<?php
$categories = $categories ?? [];
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
                <a href="/manager/categories" class="flex items-center px-4 py-3 bg-gray-900 text-white rounded-lg"><span class="material-symbols-outlined mr-3">category</span>Categories</a>
                <a href="/manager/orders" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition"><span class="material-symbols-outlined mr-3">shopping_bag</span>Orders</a>
                <a href="/manager/inventory" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition"><span class="material-symbols-outlined mr-3">inventory</span>Inventory</a>
                <a href="/logout" class="flex items-center px-4 py-3 text-red-400 hover:bg-red-900/30 rounded-lg mt-8 transition"><span class="material-symbols-outlined mr-3">logout</span>Logout</a>
            </nav>
        </aside>
        <div class="flex-1 overflow-auto">
            <header class="bg-white shadow-sm p-6 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-900">Category Management</h1>
            </header>
            <main class="p-8">
                <?php if ($success): ?>
                    <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg"><p class="text-green-700"><?= htmlspecialchars($success) ?></p></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg"><p class="text-red-700"><?= htmlspecialchars($error) ?></p></div>
                <?php endif; ?>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Add Category</h2>
                        <form method="post" action="/manager/category/create" class="space-y-4">
                            <?= $this->csrfField() ?>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                                <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="e.g. Vegetables">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea name="description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg"></textarea>
                            </div>
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Create Category</button>
                        </form>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Categories (<?= count($categories) ?>)</h2>
                        <ul class="space-y-3">
                            <?php foreach ($categories as $cat): ?>
                                <li class="flex items-center justify-between py-2 border-b border-gray-100">
                                    <div>
                                        <span class="font-medium"><?= htmlspecialchars($cat['name']) ?></span>
                                        <span class="text-sm text-gray-500 ml-2">(<?= (int)($cat['product_count'] ?? 0) ?> products)</span>
                                    </div>
                                    <form method="post" action="/manager/category/<?= (int)$cat['id'] ?>/update" class="inline">
                                        <?= $this->csrfField() ?>
                                        <input type="hidden" name="name" value="<?= htmlspecialchars($cat['name']) ?>">
                                        <input type="hidden" name="description" value="<?= htmlspecialchars($cat['description'] ?? '') ?>">
                                        <select name="status" class="text-sm border rounded px-2 py-1">
                                            <option value="active" <?= ($cat['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                                            <option value="inactive" <?= ($cat['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                        </select>
                                        <button type="submit" class="ml-2 text-sm text-green-600 hover:underline">Update</button>
                                    </form>
                                </li>
                            <?php endforeach; ?>
                            <?php if (empty($categories)): ?>
                                <li class="text-gray-500">No categories yet.</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>
