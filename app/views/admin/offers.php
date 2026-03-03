<?php
/**
 * Admin - Weekly Deals/Offers Management
 */
$offers = $data['offers'] ?? [];
$products = $data['products'] ?? [];
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$nav = function (string $path) use ($currentPath) {
    return $currentPath === $path
        ? 'flex items-center px-4 py-3 bg-[#20df29] text-[#111712] rounded-lg shadow-lg shadow-[#20df29]/20 font-medium'
        : 'flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg transition-all group';
};
?>

<div class="bg-gray-100 min-h-screen font-sans">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 bg-gray-900 text-white flex-shrink-0 flex flex-col transition-transform duration-300 fixed md:relative z-50 h-full -translate-x-full md:translate-x-0">
            <div class="p-6 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">Admin</h2>
                    <p class="text-xs text-gray-400 mt-1 uppercase tracking-wider">ADI ARI Fresh</p>
                </div>
                <!-- Mobile Close Button -->
                <button onclick="toggleSidebar()" class="md:hidden text-gray-400 hover:text-white">
                     <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <nav class="mt-2 px-4 space-y-1 flex-1 overflow-y-auto">
                <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 mt-4">Main</p>
                <a href="/admin" class="<?= $nav('/admin') ?>">
                    <span class="material-symbols-outlined mr-3 group-hover:text-[#20df29] transition-colors">dashboard</span>
                    Dashboard
                </a>
                <a href="/manager/products" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg transition-all group">
                    <span class="material-symbols-outlined mr-3 group-hover:text-[#20df29] transition-colors">inventory_2</span>
                    Products
                </a>
                <a href="/manager/categories" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg transition-all group">
                    <span class="material-symbols-outlined mr-3 group-hover:text-[#20df29] transition-colors">category</span>
                    Categories
                </a>
                <a href="/admin/offers" class="<?= $nav('/admin/offers') ?>">
                    <span class="material-symbols-outlined mr-3">sell</span>
                    Weekly Deals
                </a>
                <a href="/admin/users" class="<?= $nav('/admin/users') ?>">
                    <span class="material-symbols-outlined mr-3 group-hover:text-[#20df29] transition-colors">people</span>
                    Users
                </a>
                
                <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 mt-6">Analytics</p>
                <a href="/admin/analytics" class="<?= $nav('/admin/analytics') ?>">
                    <span class="material-symbols-outlined mr-3 group-hover:text-[#20df29] transition-colors">analytics</span>
                    Analytics
                </a>
                <a href="/admin/reports" class="<?= $nav('/admin/reports') ?>">
                    <span class="material-symbols-outlined mr-3 group-hover:text-[#20df29] transition-colors">summarize</span>
                    Reports
                </a>
                
                <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 mt-6">System</p>
                <a href="/admin/settings" class="<?= $nav('/admin/settings') ?>">
                    <span class="material-symbols-outlined mr-3 group-hover:text-[#20df29] transition-colors">settings</span>
                    Settings
                </a>
                <a href="/admin/logs" class="<?= $nav('/admin/logs') ?>">
                    <span class="material-symbols-outlined mr-3 group-hover:text-[#20df29] transition-colors">list_alt</span>
                    Logs
                </a>
            </nav>
            
            <div class="p-4 border-t border-gray-800">
                <a href="/logout" class="flex items-center px-4 py-3 text-red-400 hover:bg-red-900/20 hover:text-red-300 rounded-lg transition-colors w-full">
                    <span class="material-symbols-outlined mr-3">logout</span>
                    Logout
                </a>
            </div>
        </aside>

        <!-- Overlay -->
        <div id="sidebarOverlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden glass transition-opacity"></div>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <header class="bg-white shadow-sm sticky top-0 z-30">
                <div class="flex items-center justify-between p-6">
                    <button onclick="toggleSidebar()" class="md:hidden text-gray-500 hover:text-gray-900">
                        <span class="material-symbols-outlined">menu</span>
                    </button>
                    <h1 class="text-2xl font-bold text-gray-900">Weekly Deals Management</h1>
                    <button onclick="openCreateModal()" class="px-6 py-3 bg-[#20df29] text-white font-bold rounded-lg hover:bg-green-600 transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined">add</span>
                        <span class="hidden sm:inline">Create Offer</span>
                    </button>
                </div>
            </header>

<div class="p-6">
    <!-- Success/Error Messages -->
    <?php if (Session::hasFlash('success')): ?>
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl flex items-center gap-3">
            <span class="material-symbols-outlined text-green-600">check_circle</span>
            <p class="text-green-700 font-medium"><?= Session::getFlash('success') ?></p>
        </div>
    <?php endif; ?>

    <?php if (Session::hasFlash('error')): ?>
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-center gap-3">
            <span class="material-symbols-outlined text-red-600">error</span>
            <p class="text-red-700 font-medium"><?= Session::getFlash('error') ?></p>
        </div>
    <?php endif; ?>

    <!-- Offers Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Discount</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Start Date</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">End Date</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if (empty($offers)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <span class="material-symbols-outlined text-6xl text-gray-300 mb-3 block">local_offer</span>
                            <p>No offers created yet. Create your first offer!</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($offers as $offer): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <?php if ($offer['primary_image']): ?>
                                        <img src="<?= htmlspecialchars($this->productImage($offer['primary_image'])) ?>" alt="<?= htmlspecialchars($offer['product_name']) ?>" class="w-12 h-12 object-cover rounded-lg">
                                    <?php else: ?>
                                        <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <span class="material-symbols-outlined text-gray-400">image</span>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <p class="font-semibold text-gray-900"><?= htmlspecialchars($offer['product_name']) ?></p>
                                        <p class="text-sm text-gray-500">¥<?= number_format($offer['original_price'], 2) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-[#20df29] font-bold">
                                    <?php if ($offer['discount_type'] === 'percentage'): ?>
                                        <?= $offer['discount_value'] ?>% OFF
                                    <?php else: ?>
                                        ¥<?= number_format($offer['discount_value'], 2) ?> OFF
                                    <?php endif; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600"><?= date('M d, Y', strtotime($offer['start_date'])) ?></td>
                            <td class="px-6 py-4 text-sm text-gray-600"><?= date('M d, Y', strtotime($offer['end_date'])) ?></td>
                            <td class="px-6 py-4">
                                <?php
                                $statusColors = [
                                    'active' => 'bg-green-100 text-green-700',
                                    'inactive' => 'bg-gray-100 text-gray-700',
                                    'expired' => 'bg-red-100 text-red-700'
                                ];
                                $colorClass = $statusColors[$offer['status']] ?? 'bg-gray-100 text-gray-700';
                                ?>
                                <span class="px-3 py-1 <?= $colorClass ?> text-xs font-bold rounded-full uppercase"><?= $offer['status'] ?></span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button onclick='openEditModal(<?= json_encode($offer) ?>)' class="text-blue-600 hover:text-blue-800 mr-3">
                                    <span class="material-symbols-outlined">edit</span>
                                </button>
                                <button onclick="deleteOffer(<?= $offer['id'] ?>)" class="text-red-600 hover:text-red-800">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Create Offer Modal -->
<div id="createModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-4">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-900">Create New Offer</h2>
            <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="/admin/offer/create" method="POST" class="p-6 space-y-4">
            <?= Security::getCsrfField() ?>
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Product</label>
                <select name="product_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#20df29] focus:border-transparent">
                    <option value="">Select a product</option>
                    <?php foreach ($products as $product): ?>
                        <option value="<?= $product['id'] ?>"><?= htmlspecialchars($product['name']) ?> (¥<?= number_format($product['price'], 2) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Discount Type</label>
                    <select name="discount_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#20df29] focus:border-transparent">
                        <option value="percentage">Percentage (%)</option>
                        <option value="fixed">Fixed Amount (¥)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Discount Value</label>
                    <input type="number" step="0.01" name="discount_value" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#20df29] focus:border-transparent">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Start Date</label>
                    <input type="date" name="start_date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#20df29] focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">End Date</label>
                    <input type="date" name="end_date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#20df29] focus:border-transparent">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#20df29] focus:border-transparent">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeCreateModal()" class="px-6 py-2 border border-gray-300 text-gray-700 font-bold rounded-lg hover:bg-gray-50">Cancel</button>
                <button type="submit" class="px-6 py-2 bg-[#20df29] text-white font-bold rounded-lg hover:bg-green-600">Create Offer</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Offer Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-4">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-900">Edit Offer</h2>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="editForm" method="POST" class="p-6 space-y-4">
            <?= Security::getCsrfField() ?>
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Product</label>
                <select name="product_id" id="edit_product_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#20df29] focus:border-transparent">
                    <?php foreach ($products as $product): ?>
                        <option value="<?= $product['id'] ?>"><?= htmlspecialchars($product['name']) ?> (¥<?= number_format($product['price'], 2) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Discount Type</label>
                    <select name="discount_type" id="edit_discount_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#20df29] focus:border-transparent">
                        <option value="percentage">Percentage (%)</option>
                        <option value="fixed">Fixed Amount (¥)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Discount Value</label>
                    <input type="number" step="0.01" name="discount_value" id="edit_discount_value" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#20df29] focus:border-transparent">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Start Date</label>
                    <input type="date" name="start_date" id="edit_start_date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#20df29] focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">End Date</label>
                    <input type="date" name="end_date" id="edit_end_date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#20df29] focus:border-transparent">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Status</label>
                <select name="status" id="edit_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#20df29] focus:border-transparent">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="expired">Expired</option>
                </select>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeEditModal()" class="px-6 py-2 border border-gray-300 text-gray-700 font-bold rounded-lg hover:bg-gray-50">Cancel</button>
                <button type="submit" class="px-6 py-2 bg-[#20df29] text-white font-bold rounded-lg hover:bg-green-600">Update Offer</button>
            </div>
        </form>
    </div>
</div>

<script>
function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
    document.getElementById('createModal').classList.add('flex');
}

function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
    document.getElementById('createModal').classList.remove('flex');
}

function openEditModal(offer) {
    document.getElementById('editForm').action = '/admin/offer/' + offer.id + '/update';
    document.getElementById('edit_product_id').value = offer.product_id;
    document.getElementById('edit_discount_type').value = offer.discount_type;
    document.getElementById('edit_discount_value').value = offer.discount_value;
    document.getElementById('edit_start_date').value = offer.start_date;
    document.getElementById('edit_end_date').value = offer.end_date;
    document.getElementById('edit_status').value = offer.status;
    
    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editModal').classList.add('flex');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editModal').classList.remove('flex');
}

function deleteOffer(id) {
    if (confirm('Are you sure you want to delete this offer?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/offer/' + id + '/delete';
        form.innerHTML = '<?= Security::getCsrfField() ?>';
        document.body.appendChild(form);
        form.submit();
    }
}

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    if (sidebar.classList.contains('-translate-x-full')) {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
    } else {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    }
}
</script>
        </div>
    </div>
</div>
