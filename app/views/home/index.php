<?php
/**
 * Home Page View - Modern Premium Design
 */
?>

<!-- Hero Section - Ultra Modern -->
<section class="relative w-full min-h-[600px] sm:min-h-[700px] lg:min-h-[800px] flex items-center overflow-hidden bg-gray-900">
    <!-- Background Image with Dark Overlay -->
    <img src="<?= $this->url('/images/hero-bg.jpg') ?>" class="absolute inset-0 w-full h-full object-cover opacity-50 transition-transform duration-10000 hover:scale-105" alt="Fresh Groceries" onerror="this.src='https://images.unsplash.com/photo-1542838132-92c53300491e?q=80&w=1974&auto=format&fit=crop';">
    <div class="absolute inset-0 bg-gray-900/80 sm:bg-transparent sm:bg-gradient-to-r sm:from-gray-900/95 sm:via-gray-900/70 sm:to-transparent"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-24 relative z-10 w-full">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 sm:gap-12 items-center">
            <!-- Hero Content -->
            <div class="text-white space-y-8 animate-fade-in-up md:max-w-2xl">
                <!-- Premium Badge -->
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md px-5 py-2.5 rounded-full border border-white/20 shadow-lg">
                    <span class="material-symbols-outlined text-accent-400 text-[20px]">workspace_premium</span>
                    <span class="font-bold text-sm text-white tracking-wide uppercase">100% Halal Certified & Farm Fresh</span>
                </div>

                <!-- Main Heading -->
                <h1 class="text-5xl sm:text-6xl lg:text-7xl font-black leading-[1.1] tracking-tight text-white drop-shadow-md">
                    Farm Fresh.<br>
                    <span class="text-primary-400">Halal Certified.</span><br>
                    <span class="text-white">Delivered to You.</span>
                </h1>

                <!-- Subheading -->
                <p class="text-xl text-gray-200 leading-relaxed drop-shadow">
                    Premium organic vegetables, certified halal meats, and authentic groceries — sourced fresh daily and delivered across Tokyo.
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-2">
                    <a href="<?= $this->url('/products') ?>" class="px-8 py-4 bg-primary-600 hover:bg-primary-500 text-white font-bold rounded-xl shadow-lg hover:shadow-primary-500/30 transition-all flex items-center justify-center gap-2 group transform hover:-translate-y-1">
                        <span class="text-lg">Shop Now</span>
                        <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </a>
                    <a href="<?= $this->url('/about') ?>" class="px-8 py-4 bg-white/10 backdrop-blur-sm border-2 border-white/30 hover:bg-white/20 text-white font-bold rounded-xl transition-all flex items-center justify-center transform hover:-translate-y-1">
                        <span class="text-lg">Learn More</span>
                    </a>
                </div>

                <!-- Stats Bar -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-8">
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl p-4 flex items-center gap-4 hover:bg-white/20 transition-colors">
                        <div class="w-12 h-12 rounded-full bg-primary-500/20 flex items-center justify-center text-primary-400">
                            <span class="material-symbols-outlined text-2xl">groups</span>
                        </div>
                        <div>
                            <div class="text-2xl font-black text-white">5K+</div>
                            <div class="text-xs text-gray-300 font-medium uppercase tracking-wider">Happy Customers</div>
                        </div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl p-4 flex items-center gap-4 hover:bg-white/20 transition-colors">
                        <div class="w-12 h-12 rounded-full bg-accent-500/20 flex items-center justify-center text-accent-400">
                            <span class="material-symbols-outlined text-2xl">verified</span>
                        </div>
                        <div>
                            <div class="text-2xl font-black text-white">100%</div>
                            <div class="text-xs text-gray-300 font-medium uppercase tracking-wider">Halal Certified</div>
                        </div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl p-4 flex items-center gap-4 hover:bg-white/20 transition-colors">
                        <div class="w-12 h-12 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-400">
                            <span class="material-symbols-outlined text-2xl">support_agent</span>
                        </div>
                        <div>
                            <div class="text-2xl font-black text-white">24/7</div>
                            <div class="text-xs text-gray-300 font-medium uppercase tracking-wider">Support</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Empty column for layout balance on large screens -->
            <div class="hidden lg:block"></div>
        </div>
    </div>
</section>

<!-- Category Quick-Links -->
<section class="w-full py-10 bg-white dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <h2 class="text-2xl font-black text-gray-900 dark:text-white mb-6 text-center">Shop by Category</h2>
        <div class="grid grid-cols-3 sm:grid-cols-6 gap-4">
            <?php
            $quickCategories = [
                ['icon' => '🥦', 'name' => 'Vegetables',  'slug' => 'vegetables'],
                ['icon' => '🥩', 'name' => 'Halal Meat',  'slug' => 'halal-meat'],
                ['icon' => '🥛', 'name' => 'Dairy',       'slug' => 'dairy'],
                ['icon' => '🌶️', 'name' => 'Spices',      'slug' => 'spices'],
                ['icon' => '🧃', 'name' => 'Beverages',   'slug' => 'beverages'],
                ['icon' => '🍚', 'name' => 'Rice & Grains','slug' => 'rice-grains'],
            ];
            // Override with live DB categories if available
            if (!empty($data['categories'])) {
                $quickCategories = array_map(fn($c) => [
                    'icon'  => $c['icon'] ?? '🛒',
                    'name'  => $c['name'],
                    'slug'  => $c['slug'] ?? $c['id'],
                ], array_slice($data['categories'], 0, 6));
            }
            foreach ($quickCategories as $qc): ?>
            <a href="<?= $this->url('/products?category=' . $qc['slug']) ?>" class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 border-2 border-transparent hover:border-emerald-400 transition-all hover:-translate-y-1">
                <span class="text-4xl group-hover:scale-110 transition-transform"><?= $qc['icon'] ?></span>
                <span class="text-sm font-bold text-gray-800 dark:text-gray-200 text-center leading-tight"><?= htmlspecialchars($qc['name']) ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Weekly Deals Section -->
<?php if (!empty($weeklyDeals)): ?>
<section class="w-full py-12 bg-gradient-to-r from-orange-500 to-red-500 relative overflow-hidden">
    <!-- Background Patterns -->
    <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(circle, white 2px, transparent 2.5px); background-size: 20px 20px;"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col md:flex-row items-center justify-between mb-8 text-white">
            <div class="text-center md:text-left mb-6 md:mb-0">
                <div class="inline-block bg-white/20 backdrop-blur-sm px-4 py-1 rounded-full text-sm font-bold mb-2 border border-white/30 animate-pulse">
                    ⚡ LIMITED TIME OFFERS
                </div>
                <h2 class="text-3xl sm:text-4xl font-black drop-shadow-sm">Weekly Flash Deals</h2>
                <p class="text-orange-100 mt-2 font-medium">Grab these exclusive discounts before they're gone!</p>
            </div>
            <?php if (count($weeklyDeals) > 4): ?>
            <a href="<?= $this->url('/deals') ?>" class="group inline-flex items-center gap-2 bg-white text-orange-600 px-6 py-3 rounded-xl font-bold hover:bg-orange-50 transition shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                View All Deals 
                <span class="group-hover:translate-x-1 transition-transform">→</span>
            </a>
            <?php endif; ?>
        </div>

        <!-- Deals Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach (array_slice($weeklyDeals, 0, 4) as $deal): ?>
                <?php 
                    $savings = $deal['original_price'] > 0 ? round((($deal['original_price'] - $deal['discounted_price']) / $deal['original_price']) * 100) : 0;
                    $imageUrl = $this->productImage($deal['primary_image'], 'https://placehold.co/400x300?text=Deal');
                ?>
                <div class="bg-white rounded-2xl shadow-xl hover:shadow-2xl transition hover:-translate-y-2 group relative overflow-hidden flex flex-col h-full border border-orange-100">
                    <!-- Discount Badge -->
                    <?php if ($savings > 0): ?>
                    <div class="absolute top-0 right-0 bg-red-600 text-white text-xs font-black px-3 py-1.5 rounded-bl-xl z-20 shadow-md">
                        SAVE <?= $savings ?>%
                    </div>
                    <?php endif; ?>

                    <!-- Deal Image -->
                    <a href="<?= $this->url('/product/' . $deal['product_id']) ?>" class="relative aspect-[4/3] overflow-hidden bg-gray-100 block">
                        <img src="<?= htmlspecialchars($imageUrl) ?>" 
                             alt="<?= htmlspecialchars($deal['product_name']) ?>"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                             onerror="this.src='https://placehold.co/400x300?text=Deal';">
                        
                        <!-- Quick View Overlay -->
                        <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <span class="bg-white/90 text-gray-900 px-4 py-2 rounded-lg font-bold text-sm shadow-sm backdrop-blur-sm">View Deal</span>
                        </div>
                    </a>

                    <!-- Deal Info -->
                    <div class="p-5 flex-1 flex flex-col">
                        <div class="mb-2">
                            <h3 class="font-bold text-lg text-gray-900 leading-tight line-clamp-2 group-hover:text-orange-600 transition">
                                <?= htmlspecialchars($deal['product_name']) ?>
                            </h3>
                        </div>
                        
                        <div class="mt-auto pt-3 border-t border-gray-100 flex items-center justify-between">
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-400 line-through">¥<?= number_format($deal['original_price']) ?></span>
                                <span class="text-2xl font-black text-red-600 leading-none">¥<?= number_format($deal['discounted_price']) ?></span>
                            </div>
                            <a href="<?= $this->url('/product/' . $deal['product_id']) ?>" class="bg-orange-500 text-white p-2.5 rounded-xl hover:bg-orange-600 transition shadow-md group-hover:scale-105 active:scale-95">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Featured Products Section -->
<?php if (!empty($featuredProducts)): ?>
<section class="w-full py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <!-- Section Header -->
        <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-black text-gray-900 mb-4">
                ⭐ Featured Products
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Handpicked premium selection of our best products
            </p>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            <?php foreach ($featuredProducts as $product): ?>
                <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100 hover:-translate-y-2">
                    <!-- Product Image -->
                    <div class="relative overflow-hidden bg-gray-100 aspect-square">
                            <img src="<?= htmlspecialchars($this->productImage($product['primary_image'] ?? null)) ?>" 
                                 alt="<?= htmlspecialchars($product['name']) ?>"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                 onerror="this.src='https://placehold.co/400x400?text=Product';">
                        
                        <!-- Badges -->
                        <div class="absolute top-3 left-3 flex flex-col gap-2">
                            <?php if ($product['is_featured']): ?>
                                <span class="bg-yellow-400 text-yellow-900 text-xs font-black px-3 py-1 rounded-full">⭐ FEATURED</span>
                            <?php endif; ?>
                            <?php if ($product['is_halal']): ?>
                                <span class="bg-green-500 text-white text-xs font-black px-3 py-1 rounded-full">✓ HALAL</span>
                            <?php endif; ?>
                        </div>

                        <!-- Quick View Overlay -->
                        <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <a href="<?= $this->url('/product/' . $product['id']) ?>" class="bg-white text-gray-900 px-4 py-2 rounded-lg font-semibold hover:bg-yellow-300 transition text-sm">
                                Quick View
                            </a>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="p-4">
                        <h3 class="font-bold text-base mb-1 text-gray-900 line-clamp-2 group-hover:text-emerald-600 transition">
                            <?= htmlspecialchars($product['name']) ?>
                        </h3>
                        
                        <div class="flex items-center justify-between mt-2">
                            <div class="flex flex-col">
                                <?php if (!empty($product['sale_price']) && $product['sale_price'] < $product['price']): ?>
                                    <span class="text-lg sm:text-xl font-black text-emerald-600">¥<?= number_format($product['sale_price']) ?></span>
                                    <span class="text-[10px] sm:text-xs text-gray-400 line-through">¥<?= number_format($product['price']) ?></span>
                                <?php else: ?>
                                    <span class="text-lg sm:text-xl font-black text-emerald-600">¥<?= number_format($product['price']) ?></span>
                                <?php endif; ?>
                            </div>
                            <?php if ($product['stock_quantity'] > 0): ?>
                            <form action="<?= $this->url('/cart/add') ?>" method="POST">
                                <?= Security::getCsrfField() ?>
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" title="Add to Cart" class="w-10 h-10 flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl transition shadow-md hover:shadow-emerald-300 active:scale-95">
                                    <span class="material-symbols-outlined text-[20px]">add_shopping_cart</span>
                                </button>
                            </form>
                            <?php else: ?>
                            <span class="text-xs text-red-500 font-semibold">Out of Stock</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- View All Button -->
        <div class="text-center mt-12">
            <a href="<?= $this->url('/products') ?>" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-8 py-4 rounded-xl font-bold hover:bg-emerald-700 transition-all shadow-lg hover:shadow-xl">
                <span>View All Products</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Why Choose Us Section -->
<section class="w-full py-16 bg-gray-50 dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-black text-gray-900 dark:text-white mb-4">
                Why Choose ADI ARI Fresh?
            </h2>
            <p class="text-lg text-gray-600 dark:text-gray-400">We're committed to delivering the best quality products</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Feature 1 -->
            <div class="group flex gap-5 p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border-l-4 border-emerald-500 hover:shadow-xl hover:-translate-y-1 transition-all relative overflow-hidden">
                <div class="absolute right-0 top-0 opacity-5 group-hover:opacity-10 transition-opacity pointer-events-none">
                    <span class="material-symbols-outlined text-9xl -mt-4 -mr-4 text-emerald-500">verified</span>
                </div>
                <div class="shrink-0 w-14 h-14 bg-emerald-100 dark:bg-emerald-900/40 rounded-xl flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white text-emerald-600 transition-colors z-10">
                    <span class="material-symbols-outlined text-[32px]">verified</span>
                </div>
                <div class="z-10">
                    <h3 class="text-lg font-bold mb-1 text-gray-900 dark:text-white">100% Halal Certified</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">All our meat products are certified halal by registered authorities, ensuring quality and authenticity.</p>
                </div>
            </div>

            <!-- Feature 2 -->
            <div class="group flex gap-5 p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border-l-4 border-emerald-500 hover:shadow-xl hover:-translate-y-1 transition-all relative overflow-hidden">
                <div class="absolute right-0 top-0 opacity-5 group-hover:opacity-10 transition-opacity pointer-events-none">
                    <span class="material-symbols-outlined text-9xl -mt-4 -mr-4 text-emerald-500">agriculture</span>
                </div>
                <div class="shrink-0 w-14 h-14 bg-emerald-100 dark:bg-emerald-900/40 rounded-xl flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white text-emerald-600 transition-colors z-10">
                    <span class="material-symbols-outlined text-[32px]">agriculture</span>
                </div>
                <div class="z-10">
                    <h3 class="text-lg font-bold mb-1 text-gray-900 dark:text-white">Farm Fresh Daily</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">Fresh vegetables sourced from local farms every morning and delivered directly to you.</p>
                </div>
            </div>

            <!-- Feature 3 -->
            <div class="group flex gap-5 p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border-l-4 border-emerald-500 hover:shadow-xl hover:-translate-y-1 transition-all relative overflow-hidden">
                <div class="absolute right-0 top-0 opacity-5 group-hover:opacity-10 transition-opacity pointer-events-none">
                    <span class="material-symbols-outlined text-9xl -mt-4 -mr-4 text-emerald-500">local_shipping</span>
                </div>
                <div class="shrink-0 w-14 h-14 bg-emerald-100 dark:bg-emerald-900/40 rounded-xl flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white text-emerald-600 transition-colors z-10">
                    <span class="material-symbols-outlined text-[32px]">local_shipping</span>
                </div>
                <div class="z-10">
                    <h3 class="text-lg font-bold mb-1 text-gray-900 dark:text-white">Fast Delivery</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">Swift and reliable delivery service across Tokyo. Order early for same-day delivery.</p>
                </div>
            </div>
        </div>
    </div>
</section>



<!-- CTA Section -->
<section class="w-full py-16 sm:py-20 bg-gradient-to-br from-emerald-600 to-teal-700 text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-3xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 bg-white/20 px-4 sm:px-5 py-2 rounded-full text-xs sm:text-sm font-bold mb-6">
                <span>🚚</span>
                <span>Free delivery on orders over ¥5,000</span>
            </div>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black mb-4">Order fresh groceries today</h2>
            <p class="text-xl mb-8 text-emerald-100">Farm-fresh vegetables and halal meats delivered straight to your door in Tokyo</p>
            <a href="<?= $this->url('/products') ?>" class="inline-flex items-center gap-3 bg-white text-emerald-700 px-10 py-4 rounded-xl font-black hover:bg-amber-300 hover:text-emerald-900 transition-all transform hover:scale-105 shadow-2xl text-lg">
                <span class="material-symbols-outlined">shopping_cart</span>
                Start Shopping
            </a>
            <p class="mt-4 text-emerald-200 text-sm">No subscription required &nbsp;·&nbsp; Cancel anytime &nbsp;·&nbsp; 100% Halal</p>
        </div>
    </div>
</section>

<!-- Contact Info Section -->
<section class="w-full py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="max-w-5xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Visit Our Store -->
                <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                        <span class="text-3xl">🏪</span>
                        Visit Our Store
                    </h3>
                    <div class="space-y-4 text-gray-700">
                        <p class="flex items-start gap-3">
                            <span class="text-emerald-600 text-xl">📍</span>
                            <span class="leading-relaxed">114-0031 Higashi Tabata 2-3-1<br>Otsu building 101, Tokyo, Japan</span>
                        </p>
                        <p class="flex items-center gap-3">
                            <span class="text-emerald-600 text-xl">📞</span>
                            <span>080-3408-8044</span>
                        </p>
                        <p class="flex items-center gap-3">
                            <span class="text-emerald-600 text-xl">🕐</span>
                            <span>Open Daily: 8:00 AM - 10:00 PM</span>
                        </p>
                        <p class="flex items-center gap-3">
                            <span class="text-emerald-600 text-xl">✉️</span>
                            <span>info@adiari.shop</span>
                        </p>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                        <span class="text-3xl">🔗</span>
                        Quick Links
                    </h3>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="<?= $this->url('/products') ?>" class="flex items-center gap-2 text-gray-700 hover:text-emerald-600 transition-colors p-3 rounded-lg hover:bg-emerald-50 font-medium">
                            <span>→</span> Shop Products
                        </a>
                        <a href="<?= $this->url('/about') ?>" class="flex items-center gap-2 text-gray-700 hover:text-emerald-600 transition-colors p-3 rounded-lg hover:bg-emerald-50 font-medium">
                            <span>→</span> About Us
                        </a>
                        <a href="<?= $this->url('/contact') ?>" class="flex items-center gap-2 text-gray-700 hover:text-emerald-600 transition-colors p-3 rounded-lg hover:bg-emerald-50 font-medium">
                            <span>→</span> Contact Us
                        </a>
                        <?php if (!Session::get('logged_in')): ?>
                            <a href="<?= $this->url('/register') ?>" class="flex items-center gap-2 text-gray-700 hover:text-emerald-600 transition-colors p-3 rounded-lg hover:bg-emerald-50 font-medium">
                                <span>→</span> Sign Up
                            </a>
                        <?php else: ?>
                            <a href="<?= $this->url('/account') ?>" class="flex items-center gap-2 text-gray-700 hover:text-emerald-600 transition-colors p-3 rounded-lg hover:bg-emerald-50 font-medium">
                                <span>→</span> My Account
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
