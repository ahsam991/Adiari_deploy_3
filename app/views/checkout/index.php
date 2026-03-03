<?php
/**
 * Checkout Page - Shipping and order summary
 */
$items = $data['items'] ?? [];
$totals = $data['totals'] ?? ['subtotal' => 0];
$user = $data['user'] ?? [];
$subtotal = $data['subtotal'] ?? 0;
$shippingCost = $data['shippingCost'] ?? 0;
$freeShippingThreshold = $data['freeShippingThreshold'] ?? 5000;
$taxEnabled = $data['taxEnabled'] ?? false;
$taxLabel = $data['taxLabel'] ?? 'Tax';
$globalTaxRate = $data['globalTaxRate'] ?? 10;
$taxAmount = $data['taxAmount'] ?? 0;

$old = Session::getFlash('old') ?: [];
$error = Session::getFlash('error');
?>

<div class="max-w-[1280px] mx-auto px-4 sm:px-10 py-8">
    <h1 class="text-2xl font-bold text-text-main-light dark:text-text-main-dark mb-6">Checkout</h1>

    <?php if ($error): ?>
        <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
            <p class="text-red-700 dark:text-red-300"><?= htmlspecialchars($error) ?></p>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= $this->url('/checkout/process') ?>">
        <?= $this->csrfField() ?>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Shipping -->
            <div class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-xl p-6">
                <h2 class="text-lg font-bold text-text-main-light dark:text-text-main-dark mb-4">Shipping Details</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-text-main-light dark:text-text-main-dark mb-1">First Name *</label>
                        <input type="text" name="shipping_first_name" required
                            value="<?= htmlspecialchars($old['shipping_first_name'] ?? $user['first_name'] ?? '') ?>"
                            class="w-full px-3 py-2 border border-border-light dark:border-border-dark rounded-lg bg-background-light dark:bg-background-dark">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-main-light dark:text-text-main-dark mb-1">Last Name *</label>
                        <input type="text" name="shipping_last_name" required
                            value="<?= htmlspecialchars($old['shipping_last_name'] ?? $user['last_name'] ?? '') ?>"
                            class="w-full px-3 py-2 border border-border-light dark:border-border-dark rounded-lg bg-background-light dark:bg-background-dark">
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-text-main-light dark:text-text-main-dark mb-1">Email *</label>
                    <input type="email" name="shipping_email" required
                        value="<?= htmlspecialchars($old['shipping_email'] ?? $user['email'] ?? '') ?>"
                        class="w-full px-3 py-2 border border-border-light dark:border-border-dark rounded-lg bg-background-light dark:bg-background-dark">
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-text-main-light dark:text-text-main-dark mb-1">Phone *</label>
                    <input type="text" name="shipping_phone" required
                        value="<?= htmlspecialchars($old['shipping_phone'] ?? '') ?>"
                        class="w-full px-3 py-2 border border-border-light dark:border-border-dark rounded-lg bg-background-light dark:bg-background-dark">
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-text-main-light dark:text-text-main-dark mb-1">Address Line 1 *</label>
                    <input type="text" name="shipping_address_line1" required
                        value="<?= htmlspecialchars($old['shipping_address_line1'] ?? '') ?>"
                        class="w-full px-3 py-2 border border-border-light dark:border-border-dark rounded-lg bg-background-light dark:bg-background-dark">
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-text-main-light dark:text-text-main-dark mb-1">Address Line 2</label>
                    <input type="text" name="shipping_address_line2"
                        value="<?= htmlspecialchars($old['shipping_address_line2'] ?? '') ?>"
                        class="w-full px-3 py-2 border border-border-light dark:border-border-dark rounded-lg bg-background-light dark:bg-background-dark">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-text-main-light dark:text-text-main-dark mb-1">City *</label>
                        <input type="text" name="shipping_city" required
                            value="<?= htmlspecialchars($old['shipping_city'] ?? '') ?>"
                            class="w-full px-3 py-2 border border-border-light dark:border-border-dark rounded-lg bg-background-light dark:bg-background-dark">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-main-light dark:text-text-main-dark mb-1">State/Prefecture</label>
                        <input type="text" name="shipping_state"
                            value="<?= htmlspecialchars($old['shipping_state'] ?? '') ?>"
                            class="w-full px-3 py-2 border border-border-light dark:border-border-dark rounded-lg bg-background-light dark:bg-background-dark">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-main-light dark:text-text-main-dark mb-1">Postal Code</label>
                        <input type="text" name="shipping_postal_code"
                            value="<?= htmlspecialchars($old['shipping_postal_code'] ?? '') ?>"
                            class="w-full px-3 py-2 border border-border-light dark:border-border-dark rounded-lg bg-background-light dark:bg-background-dark">
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-text-main-light dark:text-text-main-dark mb-1">Country</label>
                    <input type="text" name="shipping_country" value="<?= htmlspecialchars($old['shipping_country'] ?? 'Japan') ?>"
                        class="w-full px-3 py-2 border border-border-light dark:border-border-dark rounded-lg bg-background-light dark:bg-background-dark">
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-text-main-light dark:text-text-main-dark mb-1">Order Notes</label>
                    <textarea name="customer_notes" rows="2" class="w-full px-3 py-2 border border-border-light dark:border-border-dark rounded-lg bg-background-light dark:bg-background-dark"><?= htmlspecialchars($old['customer_notes'] ?? '') ?></textarea>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-text-main-light dark:text-text-main-dark mb-1">Payment Method</label>
                    <select name="payment_method" class="w-full px-3 py-2 border border-border-light dark:border-border-dark rounded-lg bg-background-light dark:bg-background-dark">
                        <option value="cod">Cash on Delivery</option>
                        <option value="bank">Bank Transfer</option>
                    </select>
                </div>
            </div>

            <!-- Order summary -->
            <div>
                <div class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-xl p-6 sticky top-24">
                    <h2 class="text-lg font-bold text-text-main-light dark:text-text-main-dark mb-4">Order Summary</h2>
                    <ul class="space-y-3 mb-4 max-h-64 overflow-y-auto">
                        <?php foreach ($items as $item): ?>
                            <li class="flex justify-between text-sm">
                                <span class="text-text-main-light dark:text-text-main-dark"><?= htmlspecialchars($item['name']) ?> × <?= (int)$item['quantity'] ?></span>
                                <span>¥<?= number_format($item['item_total'] ?? ($item['price'] * $item['quantity'])) ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="border-t border-border-light dark:border-border-dark pt-4">
                        <div class="flex justify-between items-center text-sm mb-3">
                            <span class="text-text-main-light dark:text-text-main-dark">Subtotal</span>
                            <span class="font-medium text-text-main-light dark:text-text-main-dark">¥<?= number_format($subtotal) ?></span>
                        </div>

                        <div class="flex justify-between items-center text-sm mb-3">
                            <span class="text-text-main-light dark:text-text-main-dark">Shipping</span>
                            <?php if ($subtotal >= $freeShippingThreshold): ?>
                                <span class="font-bold text-green-600">Free</span>
                                <?php $shippingCost = 0; ?>
                            <?php else: ?>
                                <span class="font-medium text-text-main-light dark:text-text-main-dark">¥<?= number_format($shippingCost) ?></span>
                            <?php endif; ?>
                        </div>

                        <?php if ($taxEnabled): ?>
                            <div class="flex justify-between items-center text-sm mb-3 pb-3 border-b border-border-light dark:border-border-dark">
                                <span class="text-text-main-light dark:text-text-main-dark"><?= htmlspecialchars($taxLabel ?? 'Tax') ?> (<?= $globalTaxRate ?? 10 ?>%)</span>
                                <span class="font-medium text-text-main-light dark:text-text-main-dark">+ ¥<?= number_format($taxAmount) ?></span>
                            </div>
                        <?php else: ?>
                            <div class="border-b border-border-light dark:border-border-dark mb-3"></div>
                        <?php endif; ?>

                        <?php
                        // Final total calculation: Subtotal + Shipping + Tax
                        $finalTotal = $subtotal + $shippingCost + $taxAmount;
                        ?>
                        <div class="flex justify-between items-center mb-6">
                            <span class="font-bold text-text-main-light dark:text-text-main-dark text-lg">Total</span>
                            <span class="font-bold text-2xl text-text-main-light dark:text-text-main-dark">¥<?= number_format($finalTotal) ?></span>
                        </div>
                    </div>
                    <button type="submit" class="mt-6 w-full flex items-center justify-center gap-2 py-3 bg-primary hover:bg-primary-hover text-white font-semibold rounded-lg transition">
                        <span class="material-symbols-outlined">lock</span>
                        Place Order
                    </button>
                    <a href="<?= $this->url('/cart') ?>" class="mt-3 block text-center text-sm text-primary hover:underline">Back to Cart</a>
                </div>
            </div>
        </div>
    </form>
</div>
