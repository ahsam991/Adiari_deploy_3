<?php
/**
 * Weekly Deals Page
 */
$offers = $data['offers'] ?? [];
?>

<div class="bg-gray-50 dark:bg-black pb-8">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-red-500 to-orange-500 text-white py-8 sm:py-12 md:py-16">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-10">
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4 mb-3 sm:mb-4">
                <span class="material-symbols-outlined text-5xl sm:text-6xl animate-pulse">local_offer</span>
                <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold text-center sm:text-left">Weekly Deals</h1>
            </div>
            <p class="text-base sm:text-lg md:text-xl text-center opacity-90">Amazing discounts on your favorite products!</p>
        </div>
    </div>

    <!-- Deals Content -->
    <div class="max-w-[1280px] mx-auto px-4 sm:px-10 py-8 sm:py-12">
        <?php if (empty($offers)): ?>
            <!-- No Deals Available -->
            <div class="bg-white dark:bg-[#111712] rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm p-8 sm:p-12 md:p-16 text-center">
                <span class="material-symbols-outlined text-6xl sm:text-8xl text-gray-300 mb-3 sm:mb-4 block">sentiment_dissatisfied</span>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-2">No Active Deals</h2>
                <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mb-4 sm:mb-6">Check back soon for amazing offers!</p>
                <a href="/products" class="inline-flex items-center gap-2 px-5 sm:px-6 py-2.5 sm:py-3 bg-[#20df29] text-white font-bold rounded-lg hover:bg-green-600 transition-colors text-sm sm:text-base">
                    <span class="material-symbols-outlined">shopping_cart</span>
                    Browse Products
                </a>
            </div>
        <?php else: ?>
            <!-- Deals Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                <?php foreach ($offers as $offer): ?>
                    <div class="bg-white dark:bg-[#111712] rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden hover:shadow-xl transition-all duration-300 group relative">
                        <!-- Sale Badge -->
                        <div class="absolute top-4 left-4 z-10">
                            <div class="bg-red-500 text-white px-4 py-2 rounded-lg font-bold shadow-lg">
                                <?php if ($offer['discount_type'] === 'percentage'): ?>
                                    <?= $offer['discount_value'] ?>% OFF
                                <?php else: ?>
                                    ¥<?= number_format($offer['discount_value'], 0) ?> OFF
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Product Image -->
                        <div class="relative h-48 sm:h-56 md:h-64 bg-gray-100 dark:bg-gray-800 overflow-hidden">
                            <img src="<?= htmlspecialchars($this->productImage($offer['primary_image'] ?? null, 'https://placehold.co/400x300?text=Deal')) ?>" 
                                     alt="<?= htmlspecialchars($offer['product_name']) ?>" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                     onerror="this.onerror=null;this.src='https://placehold.co/400x300?text=Deal';">
                        </div>

                        <!-- Product Details -->
                        <div class="p-4 sm:p-5 md:p-6">
                            <h3 class="text-base sm:text-lg md:text-xl font-bold text-[#111712] dark:text-white mb-2 sm:mb-3 line-clamp-2">
                                <?= htmlspecialchars($offer['product_name']) ?>
                            </h3>

                            <!-- Price -->
                            <div class="flex items-center gap-2 sm:gap-3 mb-3 sm:mb-4">
                                <span class="text-xl sm:text-2xl font-bold text-[#20df29]">
                                    ¥<?= number_format($offer['discounted_price'], 2) ?>
                                </span>
                                <span class="text-base sm:text-lg text-gray-400 line-through">
                                    ¥<?= number_format($offer['original_price'], 2) ?>
                                </span>
                            </div>

                            <!-- Savings -->
                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-2.5 sm:p-3 mb-3 sm:mb-4">
                                <div class="flex items-center gap-2 text-green-700 dark:text-green-300">
                                    <span class="material-symbols-outlined text-xl">savings</span>
                                    <span class="font-bold">
                                        You Save: ¥<?= number_format($offer['original_price'] - $offer['discounted_price'], 2) ?>
                                    </span>
                                </div>
                            </div>

                            <!-- Offer Validity -->
                            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-4">
                                <span class="material-symbols-outlined text-lg">schedule</span>
                                <span>Valid until <?= date('M d, Y', strtotime($offer['end_date'])) ?></span>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-2">
                                <a href="/product/<?= htmlspecialchars($offer['product_slug']) ?>" 
                                   class="flex-1 flex items-center justify-center gap-2 px-4 py-3 bg-[#20df29] text-white font-bold rounded-lg hover:bg-green-600 transition-colors">
                                    <span class="material-symbols-outlined">shopping_cart</span>
                                    <span>Add to Cart</span>
                                </a>
                                <button class="p-3 border-2 border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-400 hover:border-[#20df29] hover:text-[#20df29] rounded-lg transition-colors">
                                    <span class="material-symbols-outlined">favorite</span>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Info Banner -->
            <div class="mt-12 bg-gradient-to-r from-[#20df29] to-green-600 text-white rounded-xl p-8 text-center">
                <h2 class="text-2xl font-bold mb-2">Don't Miss Out!</h2>
                <p class="text-lg opacity-90">These amazing deals won't last forever. Grab them while you can!</p>
            </div>
        <?php endif; ?>
    </div>
</div>
