<?php
/**
 * User Wishlist
 */
$items = $items ?? [];
?>

<div class="max-w-[1280px] mx-auto px-4 sm:px-10 py-8">
    <h1 class="text-2xl font-bold text-text-main-light dark:text-text-main-dark mb-6">My Wishlist</h1>

    <?php if (empty($items)): ?>
        <div class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-xl p-12 text-center">
            <span class="material-symbols-outlined text-6xl text-text-sub-light dark:text-text-sub-dark mb-4">favorite</span>
            <p class="text-lg text-text-main-light dark:text-text-main-dark mb-6">Your wishlist is empty.</p>
            <a href="<?= $this->url('/products') ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-primary hover:bg-primary-hover text-white font-semibold rounded-lg transition">Browse Products</a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
            <?php foreach ($items as $item): ?>
                <a href="<?= $this->url('/product/' . $item['product_id']) ?>" class="group bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-xl overflow-hidden hover:shadow-lg transition">
                    <div class="aspect-square bg-background-light dark:bg-background-dark relative">
                            <img src="<?= htmlspecialchars($this->productImage($item['primary_image'] ?? null)) ?>" alt="<?= htmlspecialchars($item['name'] ?? '') ?>" class="w-full h-full object-cover group-hover:scale-105 transition"
                                 onerror="this.onerror=null;this.src='https://placehold.co/400x400?text=Product';">
                    </div>
                    <div class="p-3">
                        <p class="font-medium text-text-main-light dark:text-text-main-dark truncate group-hover:text-primary"><?= htmlspecialchars($item['name']) ?></p>
                        <p class="text-sm font-semibold text-primary">
                            ¥<?= number_format(!empty($item['sale_price']) && $item['sale_price'] > 0 ? $item['sale_price'] : $item['price']) ?>
                        </p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
