<?php
/**
 * My Orders List
 */
$orders = $orders ?? [];
?>

<div class="max-w-[1280px] mx-auto px-4 sm:px-10 py-8">
    <h1 class="text-2xl font-bold text-text-main-light dark:text-text-main-dark mb-6">My Orders</h1>

    <?php if (empty($orders)): ?>
        <div class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-xl p-12 text-center">
            <span class="material-symbols-outlined text-6xl text-text-sub-light dark:text-text-sub-dark mb-4">shopping_bag</span>
            <p class="text-lg text-text-main-light dark:text-text-main-dark mb-6">You have not placed any orders yet.</p>
            <a href="<?= $this->url('/products') ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-primary hover:bg-primary-hover text-white font-semibold rounded-lg transition">
                Start Shopping
            </a>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full border border-border-light dark:border-border-dark rounded-xl overflow-hidden">
                <thead class="bg-background-light dark:bg-background-dark">
                    <tr>
                        <th class="text-left px-4 py-3 text-sm font-semibold text-text-main-light dark:text-text-main-dark">Order</th>
                        <th class="text-left px-4 py-3 text-sm font-semibold text-text-main-light dark:text-text-main-dark">Date</th>
                        <th class="text-left px-4 py-3 text-sm font-semibold text-text-main-light dark:text-text-main-dark">Status</th>
                        <th class="text-right px-4 py-3 text-sm font-semibold text-text-main-light dark:text-text-main-dark">Total</th>
                        <th class="text-right px-4 py-3 text-sm font-semibold text-text-main-light dark:text-text-main-dark">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-surface-light dark:bg-surface-dark divide-y divide-border-light dark:divide-border-dark">
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td class="px-4 py-3 font-medium"><?= htmlspecialchars($order['order_number']) ?></td>
                            <td class="px-4 py-3 text-sm text-text-sub-light dark:text-text-sub-dark"><?= date('M j, Y', strtotime($order['created_at'])) ?></td>
                            <td class="px-4 py-3">
                                <span class="inline-block px-2 py-1 text-xs font-medium rounded <?=
                                    $order['status'] === 'delivered' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' :
                                    ($order['status'] === 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300')
                                ?>"><?= ucfirst($order['status']) ?></span>
                            </td>
                            <td class="px-4 py-3 text-right font-medium">¥<?= number_format($order['total_amount']) ?></td>
                            <td class="px-4 py-3 text-right">
                                <a href="<?= $this->url('/order/' . $order['id']) ?>" class="text-primary hover:underline text-sm font-medium">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
