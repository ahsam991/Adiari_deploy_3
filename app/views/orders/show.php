<?php
/**
 * Order Detail Page
 */
$order = $order ?? [];
$items = $order['items'] ?? [];
$success = Session::getFlash('success');
?>

<div class="max-w-[1280px] mx-auto px-4 sm:px-10 py-8">
    <?php if ($success): ?>
        <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
            <p class="text-green-700 dark:text-green-300"><?= htmlspecialchars($success) ?></p>
        </div>
    <?php endif; ?>

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <h1 class="text-2xl font-bold text-text-main-light dark:text-text-main-dark">Order <?= htmlspecialchars($order['order_number']) ?></h1>
        <a href="<?= $this->url('/orders') ?>" class="text-primary hover:underline flex items-center gap-1">
            <span class="material-symbols-outlined text-lg">arrow_back</span> Back to Orders
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="space-y-6">
            <div class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-xl p-6">
                <h2 class="text-lg font-bold text-text-main-light dark:text-text-main-dark mb-4">Status</h2>
                <p class="flex items-center gap-2">
                    <span class="inline-block px-3 py-1 rounded text-sm font-medium <?=
                        $order['status'] === 'delivered' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' :
                        ($order['status'] === 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300')
                    ?>"><?= ucfirst($order['status']) ?></span>
                </p>
                <p class="text-sm text-text-sub-light dark:text-text-sub-dark mt-2">Placed on <?= date('F j, Y \a\t g:i A', strtotime($order['created_at'])) ?></p>
            </div>

            <div class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-xl p-6">
                <h2 class="text-lg font-bold text-text-main-light dark:text-text-main-dark mb-4">Shipping Address</h2>
                <p class="text-text-main-light dark:text-text-main-dark">
                    <?= htmlspecialchars($order['shipping_first_name'] . ' ' . $order['shipping_last_name']) ?><br>
                    <?= htmlspecialchars($order['shipping_email']) ?><br>
                    <?= htmlspecialchars($order['shipping_phone']) ?><br>
                    <?= htmlspecialchars($order['shipping_address_line1']) ?>
                    <?php if (!empty($order['shipping_address_line2'])): ?><br><?= htmlspecialchars($order['shipping_address_line2']) ?><?php endif; ?><br>
                    <?= htmlspecialchars($order['shipping_city']) ?>
                    <?php if (!empty($order['shipping_state'])): ?>, <?= htmlspecialchars($order['shipping_state']) ?><?php endif; ?>
                    <?php if (!empty($order['shipping_postal_code'])): ?> <?= htmlspecialchars($order['shipping_postal_code']) ?><?php endif; ?><br>
                    <?= htmlspecialchars($order['shipping_country']) ?>
                </p>
            </div>
        </div>

        <div>
            <div class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-xl p-6">
                <h2 class="text-lg font-bold text-text-main-light dark:text-text-main-dark mb-4">Order Items</h2>
                <ul class="space-y-4">
                    <?php foreach ($items as $item): ?>
                        <li class="flex justify-between items-start gap-4 py-3 border-b border-border-light dark:border-border-dark last:border-0">
                            <div>
                                <p class="font-medium text-text-main-light dark:text-text-main-dark"><?= htmlspecialchars($item['product_name']) ?></p>
                                <p class="text-sm text-text-sub-light dark:text-text-sub-dark">Qty: <?= (int)$item['quantity'] ?> × ¥<?= number_format($item['unit_price']) ?></p>
                            </div>
                            <span class="font-medium shrink-0">¥<?= number_format($item['total_price']) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="mt-4 pt-4 border-t border-border-light dark:border-border-dark space-y-1 text-sm">
                    <?php if (!empty($order['shipping_cost']) && $order['shipping_cost'] > 0): ?>
                        <div class="flex justify-between"><span class="text-text-sub-light dark:text-text-sub-dark">Shipping</span><span>¥<?= number_format($order['shipping_cost']) ?></span></div>
                    <?php endif; ?>
                    <?php if (!empty($order['discount_amount']) && $order['discount_amount'] > 0): ?>
                        <div class="flex justify-between"><span class="text-text-sub-light dark:text-text-sub-dark">Discount</span><span>-¥<?= number_format($order['discount_amount']) ?></span></div>
                    <?php endif; ?>
                    <?php if (isset($order['tax_amount']) && $order['tax_amount'] > 0): ?>
                        <div class="flex justify-between"><span class="text-text-sub-light dark:text-text-sub-dark">Tax</span><span>¥<?= number_format($order['tax_amount']) ?></span></div>
                    <?php endif; ?>
                    <div class="flex justify-between text-lg font-bold mt-2">
                        <span>Total</span>
                        <span>¥<?= number_format($order['total_amount']) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
