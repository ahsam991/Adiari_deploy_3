<?php
/**
 * Product Listing Page
 */
$products = $data['products'];
$categories = $data['categories'];
$currentCategory = $data['currentCategory'] ?? null;
$currentPage = $data['currentPage'];
$totalPages = $data['totalPages'];
$sort = $data['sort'];
$search = $data['search'] ?? '';
$totalProducts = $data['totalProducts'];

// Helper to build URL with query params
function buildUrl($params) {
    $currentParams = $_GET;
    $newParams = array_merge($currentParams, $params);
    return '?' . http_build_query($newParams);
}
?>

<div class="bg-gray-50 dark:bg-gray-900 min-h-screen pb-12">
    <!-- Page Header - Modern -->
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-100 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center gap-3 text-primary-600 dark:text-primary-400 text-sm font-semibold mb-3">
                <span class="material-symbols-outlined text-[20px]">verified</span>
                <span>100% Halal Certified Products</span>
            </div>
            <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tight mb-2">
                <?= $currentCategory ? htmlspecialchars($currentCategory['name']) : 'All Products' ?>
            </h1>
            <p class="text-lg text-gray-600 dark:text-gray-400">
                <?= $search ? 'Search results for: ' . htmlspecialchars($search) : 'Locally sourced organic produce delivered daily' ?>
            </p>
        </div>
    </div>

    <!-- Categories - Modern Pills -->
    <div class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex gap-3 overflow-x-auto no-scrollbar pb-2">
                <a href="<?= $this->url('/products') ?>" 
                   class="badge-modern <?= (!$currentCategory && !$search) ? 'badge-primary' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-primary-50' ?> transition-all hover:scale-105">
                    All Products
                </a>
                <?php foreach ($categories as $category): ?>
                    <a href="<?= $this->url('/products?category=' . $category['id']) ?>" 
                       class="badge-modern <?= ($currentCategory && $currentCategory['id'] == $category['id']) ? 'badge-primary' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-primary-50' ?> transition-all hover:scale-105">
                        <?= htmlspecialchars($category['name']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Sort + Filter Toolbar -->
    <div class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 mb-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex flex-wrap items-center justify-between gap-3">
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">
                <span class="font-bold text-gray-900 dark:text-white"><?= $totalProducts ?></span> products found
            </p>
            <div class="flex items-center gap-2 flex-wrap">
                <!-- Mobile Filter Toggle -->
                <button onclick="document.getElementById('filterModal').classList.remove('hidden')" class="lg:hidden flex items-center gap-2 px-3 py-1.5 rounded-lg bg-gray-100 dark:bg-gray-700 text-sm font-semibold">
                    <span class="material-symbols-outlined text-[18px]">tune</span>
                    Filter
                </button>
                <div class="hidden sm:flex items-center gap-2">
                    <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Sort:</span>
                    <?php
                    $sortOptions = [
                        'newest'     => 'Newest',
                        'price_asc'  => 'Price ↑',
                        'price_desc' => 'Price ↓',
                        'featured'   => 'Featured',
                    ];
                    foreach ($sortOptions as $val => $label): ?>
                    <a href="<?= buildUrl(['sort' => $val]) ?>"
                       class="px-3 py-1.5 rounded-lg text-sm font-semibold transition-colors <?= $sort === $val ? 'bg-emerald-600 text-white shadow' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' ?>">
                        <?= $label ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area with Sidebar -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col lg:flex-row gap-8">
        
        <!-- Desktop Sidebar -->
        <aside class="hidden lg:block w-64 shrink-0">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 sticky top-24">
                <h3 class="font-bold text-lg mb-4 text-gray-900 dark:text-white pb-2 border-b border-gray-100 dark:border-gray-700">Categories</h3>
                <ul class="space-y-3">
                    <li>
                        <a href="<?= $this->url('/products') ?>" class="flex items-center gap-3 text-sm font-medium transition-colors <?= (!$currentCategory && !$search) ? 'text-emerald-600 font-bold' : 'text-gray-600 dark:text-gray-400 hover:text-emerald-600' ?>">
                            <span class="material-symbols-outlined text-[18px]">grid_view</span>
                            All Products
                        </a>
                    </li>
                    <?php foreach ($categories as $category): ?>
                        <li>
                            <a href="<?= $this->url('/products?category=' . $category['id']) ?>" class="flex items-center gap-3 text-sm font-medium transition-colors <?= ($currentCategory && $currentCategory['id'] == $category['id']) ? 'text-emerald-600 font-bold' : 'text-gray-600 dark:text-gray-400 hover:text-emerald-600' ?>">
                                <span class="material-symbols-outlined text-[18px]">lens</span>
                                <?= htmlspecialchars($category['name']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </aside>

        <!-- Product Grid Area -->
        <div class="flex-1">

    <!-- Product Grid -->
    <?php if (empty($products)): ?>
        <div class="px-4 sm:px-5 py-8 sm:py-12 text-center">
            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
                <span class="material-symbols-outlined text-3xl sm:text-4xl text-gray-400">inventory_2</span>
            </div>
            <h3 class="text-lg sm:text-xl font-medium text-gray-900 mb-2">No Products Found</h3>
            <p class="text-sm sm:text-base text-gray-500 mb-4 sm:mb-6">Try adjusting your search or filter to find what you're looking for.</p>
            <a href="<?= $this->url('/products') ?>" class="inline-block bg-primary text-white px-5 sm:px-6 py-2 rounded-lg hover:bg-green-700 transition text-sm sm:text-base">
                Clear Filters
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6 pb-8">
            <?php foreach ($products as $product): ?>
                <div class="group flex flex-col bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 dark:border-gray-700 hover:-translate-y-1">
                    <div class="relative w-full aspect-square bg-gray-50 dark:bg-gray-900 overflow-hidden">
                        <img src="<?= htmlspecialchars($this->productImage($product['primary_image'], 'https://placehold.co/400x400?text=Product')) ?>" 
                             alt="<?= htmlspecialchars($product['name']) ?>" 
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                             onerror="this.onerror=null;this.src='https://placehold.co/400x400?text=No+Image';">
                        
                        <!-- Badges -->
                        <div class="absolute top-3 left-3 flex flex-col gap-2">
                            <?php if (!empty($product['is_on_sale'])): ?>
                                <span class="bg-red-500 text-white text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wide shadow-sm">Sale</span>
                            <?php endif; ?>
                            <?php if (!empty($product['is_halal'])): ?>
                                <span class="bg-emerald-500 text-white text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wide shadow-sm">Halal</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="p-4 flex flex-col flex-1 gap-2">
                        <div class="flex-1">
                            <a href="<?= $this->url('/product/' . $product['id']) ?>" class="text-gray-900 dark:text-white text-sm sm:text-base font-bold leading-tight hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors line-clamp-2 mb-1">
                                <?= htmlspecialchars($product['name']) ?>
                            </a>
                            <?php if (!empty($product['weight'])): ?>
                                <p class="text-gray-500 dark:text-gray-400 text-xs font-medium"><?= htmlspecialchars($product['weight']) ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div class="flex flex-col gap-3 mt-2">
                            <div class="flex items-end gap-2">
                                <p class="text-emerald-600 dark:text-emerald-400 text-lg font-black leading-none">¥<?= number_format($product['is_on_sale'] ? $product['sale_price'] : $product['price']) ?></p>
                                <?php if (!empty($product['is_on_sale'])): ?>
                                    <p class="text-gray-400 text-xs font-medium line-through leading-none pb-0.5">¥<?= number_format($product['price']) ?></p>
                                <?php endif; ?>
                            </div>

                            <?php if ($product['stock_quantity'] > 0): ?>
                            <form action="<?= $this->url('/cart/add') ?>" method="POST" class="w-full">
                                 <?= Security::getCsrfField() ?>
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-full flex items-center justify-center gap-2 bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white dark:bg-emerald-900/30 dark:text-emerald-400 dark:hover:bg-emerald-600 dark:hover:text-white py-2.5 rounded-xl text-sm font-bold transition-colors active:scale-95">
                                    <span class="material-symbols-outlined text-[18px]">shopping_cart</span>
                                    <span>Add to Cart</span>
                                </button>
                            </form>
                            <?php else: ?>
                            <div class="w-full text-center py-2.5 rounded-xl border border-red-200 bg-red-50 text-red-600 text-sm font-bold">
                                Out of Stock
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <!-- End Main Content Area -->
    </div>
</div>

    <!-- Floating Filter Button (Mobile styling retained, functionality unchanged) -->
    <div class="lg:hidden fixed bottom-[84px] left-1/2 -translate-x-1/2 z-40">
        <button onclick="document.getElementById('filterModal').classList.remove('hidden')" class="flex items-center justify-center rounded-full h-12 px-6 bg-[#111712] dark:bg-primary text-white dark:text-[#111712] text-sm font-bold shadow-xl gap-2 hover:scale-105 transition-transform">
            <span class="material-symbols-outlined text-[20px]">tune</span>
            <span>Filter</span>
        </button>
    </div>
</div>

<!-- Simple Filter Modal Placeholder -->
<div id="filterModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex justify-center items-end sm:items-center">
    <div class="bg-white dark:bg-gray-800 w-full max-w-md p-6 rounded-t-2xl sm:rounded-2xl max-h-[80vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-black text-gray-900 dark:text-white">Filters</h3>
            <button onclick="document.getElementById('filterModal').classList.add('hidden')" class="p-2 text-gray-500 hover:text-gray-900 bg-gray-100 rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        
        <!-- Categories (Mobile Only context) -->
        <div class="space-y-4 mb-8">
            <h4 class="font-bold text-gray-900 dark:text-white border-b border-gray-100 pb-2">Categories</h4>
            <div class="flex flex-col gap-2">
                <a href="<?= $this->url('/products') ?>" class="px-4 py-3 rounded-xl <?= (!$currentCategory && !$search) ? 'bg-emerald-50 text-emerald-700 font-bold' : 'bg-gray-50 text-gray-700' ?> text-sm transition-colors">All Products</a>
                <?php foreach ($categories as $category): ?>
                    <a href="<?= $this->url('/products?category=' . $category['id']) ?>" class="px-4 py-3 rounded-xl <?= ($currentCategory && $currentCategory['id'] == $category['id']) ? 'bg-emerald-50 text-emerald-700 font-bold' : 'bg-gray-50 text-gray-700' ?> text-sm transition-colors"><?= htmlspecialchars($category['name']) ?></a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Sort Options -->
        <div class="space-y-4 mb-8">
            <h4 class="font-bold text-gray-900 dark:text-white border-b border-gray-100 pb-2">Sort By</h4>
            <div class="flex flex-col gap-2">
                <a href="<?= buildUrl(['sort' => 'newest']) ?>" class="flex justify-between items-center px-4 py-3 rounded-xl <?= $sort === 'newest' ? 'bg-emerald-50 text-emerald-700 font-bold' : 'bg-gray-50 text-gray-700' ?> text-sm transition-colors">
                    <span>Newest Arrivals</span>
                    <?php if($sort === 'newest'): ?><span class="material-symbols-outlined text-[20px]">check</span><?php endif; ?>
                </a>
                <a href="<?= buildUrl(['sort' => 'price_asc']) ?>" class="flex justify-between items-center px-4 py-3 rounded-xl <?= $sort === 'price_asc' ? 'bg-emerald-50 text-emerald-700 font-bold' : 'bg-gray-50 text-gray-700' ?> text-sm transition-colors">
                    <span>Price: Low to High</span>
                    <?php if($sort === 'price_asc'): ?><span class="material-symbols-outlined text-[20px]">check</span><?php endif; ?>
                </a>
                <a href="<?= buildUrl(['sort' => 'price_desc']) ?>" class="flex justify-between items-center px-4 py-3 rounded-xl <?= $sort === 'price_desc' ? 'bg-emerald-50 text-emerald-700 font-bold' : 'bg-gray-50 text-gray-700' ?> text-sm transition-colors">
                    <span>Price: High to Low</span>
                    <?php if($sort === 'price_desc'): ?><span class="material-symbols-outlined text-[20px]">check</span><?php endif; ?>
                </a>
            </div>
        </div>

        <button onclick="document.getElementById('filterModal').classList.add('hidden')" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-4 rounded-xl font-bold transition-colors">Apply Filters</button>
    </div>
</div>
