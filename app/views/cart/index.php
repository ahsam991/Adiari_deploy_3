<?php
/**
 * Shopping Cart Page
 */
$items = $items ?? [];
$totals = $totals ?? ['item_count' => 0, 'total_quantity' => 0, 'subtotal' => 0];
$validation = $validation ?? ['valid' => true, 'errors' => []];
$success = Session::getFlash('success');
$error = Session::getFlash('error');

// Free Shipping Logic
$freeShippingThreshold = 5000;
$subtotal = $totals['subtotal'];
$progress = min(100, ($subtotal / $freeShippingThreshold) * 100);
$remaining = max(0, $freeShippingThreshold - $subtotal);
?>

<div class="bg-gray-50 dark:bg-black py-8">
    <div class="max-w-[1280px] mx-auto px-4 sm:px-10">
        <h1 class="text-3xl font-bold text-[#111712] dark:text-white mb-8">Shopping Cart</h1>

        <?php if ($success): ?>
            <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                <p class="text-green-700 dark:text-green-300"><?= htmlspecialchars($success) ?></p>
            </div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                <p class="text-red-700 dark:text-red-300"><?= htmlspecialchars($error) ?></p>
            </div>
        <?php endif; ?>

        <?php if (!empty($validation['errors'])): ?>
            <div class="mb-4 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg">
                <p class="font-medium text-amber-800 dark:text-amber-200 mb-2">Please fix the following:</p>
                <ul class="list-disc list-inside text-amber-700 dark:text-amber-300 text-sm">
                    <?php foreach ($validation['errors'] as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (empty($items)): ?>
            <div class="bg-white dark:bg-[#111712] border border-gray-100 dark:border-gray-800 rounded-xl p-12 text-center shadow-sm">
                <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-gray-600 mb-4">shopping_cart</span>
                <p class="text-xl text-[#111712] dark:text-white font-medium mb-2">Your cart is empty</p>
                <p class="text-gray-500 dark:text-gray-400 mb-8">Looks like you haven't added anything to your cart yet.</p>
                <a href="<?= $this->url('/products') ?>" class="inline-flex items-center gap-2 px-8 py-3 bg-primary hover:bg-primary/90 text-[#111712] font-bold rounded-lg transition-colors">
                    Start Shopping
                </a>
            </div>
        <?php else: ?>
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Cart Items -->
                <div class="flex-1 space-y-6">
                    <!-- Free Shipping Progress -->
                    <div class="bg-white dark:bg-[#111712] p-5 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm">
                        <?php if ($remaining > 0): ?>
                            <p class="text-sm text-[#111712] dark:text-white font-medium mb-2">
                                Add <span class="text-primary font-bold">¥<?= number_format($remaining) ?></span> more for <span class="font-bold">Free Shipping</span>
                            </p>
                        <?php else: ?>
                            <p class="text-sm text-primary font-bold mb-2">
                                You've unlocked <span class="font-bold">Free Shipping</span>!
                            </p>
                        <?php endif; ?>
                        <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-primary h-2.5 rounded-full transition-all duration-500" style="width: <?= $progress ?>%"></div>
                        </div>
                    </div>

                    <!-- Items List -->
                    <div class="bg-white dark:bg-[#111712] rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm divide-y divide-gray-100 dark:divide-gray-800">
                        <?php foreach ($items as $item): ?>
                            <div class="p-5 flex gap-4 sm:gap-6">
                                <!-- Image -->
                                <a href="<?= $this->url('/product/' . (int)$item['product_id']) ?>" class="shrink-0 w-24 h-24 sm:w-32 sm:h-32 bg-gray-100 dark:bg-gray-800 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                                        <img src="<?= htmlspecialchars($this->productImage($item['primary_image'] ?? null)) ?>" 
                                             alt="<?= htmlspecialchars($item['name']) ?>" 
                                             class="w-full h-full object-cover"
                                             onerror="this.onerror=null;this.src='https://placehold.co/400x400?text=Product';">
                                </a>
                                
                                <!-- Details -->
                                <div class="flex-1 flex flex-col justify-between">
                                    <div class="flex justify-between items-start gap-4">
                                        <div>
                                            <a href="<?= $this->url('/product/' . (int)$item['product_id']) ?>" class="text-lg font-bold text-[#111712] dark:text-white hover:text-primary transition-colors line-clamp-2">
                                                <?= htmlspecialchars($item['name']) ?>
                                            </a>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"><?= htmlspecialchars($item['category_name'] ?? 'Groceries') ?></p>
                                        </div>
                                        <button type="button" onclick="showDeleteConfirm(<?= (int)$item['product_id'] ?>, '<?= htmlspecialchars(addslashes($item['name'])) ?>')" class="text-gray-400 hover:text-red-500 transition-colors bg-transparent border-none p-0 cursor-pointer">
                                            <span class="material-symbols-outlined">delete</span>
                                        </button>
                                    </div>
                                    
                                    <div class="flex justify-between items-center mt-4">
                                        <!-- Quantity Control -->
                                        <div class="flex items-center border border-gray-300 dark:border-gray-700 rounded-lg overflow-hidden">
                                            <form method="post" action="<?= $this->url('/cart/update') ?>" class="contents">
                                                <?= $this->csrfField() ?>
                                                <input type="hidden" name="product_id" value="<?= (int)$item['product_id'] ?>">
                                                <input type="hidden" name="quantity" value="<?= max(1, (int)$item['quantity'] - 1) ?>">
                                                <button type="submit" class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors" <?= $item['quantity'] <= 1 ? 'disabled' : '' ?>>
                                                    <span class="material-symbols-outlined text-sm">remove</span>
                                                </button>
                                            </form>
                                            
                                            <span class="w-10 h-8 flex items-center justify-center text-sm font-medium text-[#111712] dark:text-white">
                                                <?= (int)$item['quantity'] ?>
                                            </span>
                                            
                                            <form method="post" action="<?= $this->url('/cart/update') ?>" class="contents">
                                                <?= $this->csrfField() ?>
                                                <input type="hidden" name="product_id" value="<?= (int)$item['product_id'] ?>">
                                                <input type="hidden" name="quantity" value="<?= (int)$item['quantity'] + 1 ?>">
                                                <button type="submit" class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors" <?= (int)$item['quantity'] >= ($item['stock_quantity'] ?? 999) ? 'disabled' : '' ?>>
                                                    <span class="material-symbols-outlined text-sm">add</span>
                                                </button>
                                            </form>
                                        </div>
                                        
                                        <!-- Price -->
                                        <div class="text-right">
                                            <span class="block font-bold text-lg text-[#111712] dark:text-white">¥<?= number_format($item['price'] * $item['quantity']) ?></span>
                                            <?php if ($item['quantity'] > 1): ?>
                                                <span class="text-xs text-gray-500">¥<?= number_format($item['price']) ?> each</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="w-full lg:w-[380px] shrink-0">
                    <div class="bg-white dark:bg-[#111712] rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm p-6 sticky top-24">
                        <h2 class="text-xl font-bold text-[#111712] dark:text-white mb-6">Order Summary</h2>
                        
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between text-gray-600 dark:text-gray-400">
                                <span>Subtotal</span>
                                <span class="font-medium text-[#111712] dark:text-white">¥<?= number_format($totals['subtotal']) ?></span>
                            </div>
                            <div class="flex justify-between text-gray-600 dark:text-gray-400">
                                <span>Shipping</span>
                                <?php if ($subtotal >= $freeShippingThreshold): ?>
                                    <span class="font-bold text-primary">Free</span>
                                <?php else: ?>
                                    <span class="font-medium text-[#111712] dark:text-white">Calculated at checkout</span>
                                <?php endif; ?>
                            </div>
                            <div class="flex justify-between text-gray-600 dark:text-gray-400">
                                <span><?= htmlspecialchars($taxLabel ?? 'Tax') ?> (<?= $taxRate ?? 10 ?>%)</span>
                                <?php if ($taxEnabled ?? true): ?>
                                    <span class="font-medium text-[#111712] dark:text-white">+ ¥<?= number_format($taxAmount ?? 0) ?></span>
                                <?php else: ?>
                                    <span class="font-medium text-green-600">No Tax</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Promo Code (Visual Only) -->
                        <div class="mb-6">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Promo Code</label>
                            <div class="flex gap-2">
                                <input type="text" placeholder="Enter code" class="flex-1 bg-gray-50 dark:bg-gray-800 border-none rounded-lg px-4 py-2.5 text-sm focus:ring-1 focus:ring-primary">
                                <button class="px-4 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold text-sm rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">Apply</button>
                            </div>
                        </div>

                        <div class="border-t border-gray-100 dark:border-gray-800 pt-4 mb-6">
                            <div class="flex justify-between items-end">
                                <span class="font-bold text-lg text-[#111712] dark:text-white">Total</span>
                                <span class="font-bold text-2xl text-[#111712] dark:text-white">¥<?= number_format($totalWithTax ?? $totals['subtotal']) ?></span>
                            </div>
                            <?php if ($taxEnabled ?? true): ?>
                                <p class="text-xs text-gray-400 mt-1">Tax added to subtotal (<?= htmlspecialchars($taxLabel ?? 'Tax') ?> <?= $taxRate ?? 10 ?>%)</p>
                            <?php endif; ?>
                        </div>

                        <a href="<?= $this->url('/checkout') ?>" class="w-full flex items-center justify-center gap-2 py-4 bg-primary hover:bg-primary/90 text-[#111712] font-bold rounded-xl transition-all shadow-lg shadow-primary/20 hover:shadow-primary/30 <?= !$validation['valid'] ? 'opacity-50 pointer-events-none' : '' ?>">
                            <span>Checkout</span>
                            <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </a>

                        <div class="mt-6 flex items-center justify-center gap-2 text-xs text-gray-400">
                            <span class="material-symbols-outlined text-sm">lock</span>
                            <span>Secure Checkout</span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Custom Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white dark:bg-[#1a2e1b] rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all scale-95 opacity-0" id="deleteModalContent">
        <div class="p-6">
            <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-red-100 dark:bg-red-900/20 rounded-full">
                <span class="material-symbols-outlined text-3xl text-red-600 dark:text-red-400">warning</span>
            </div>
            <h3 class="text-xl font-bold text-center text-gray-900 dark:text-white mb-2">Remove Item?</h3>
            <p class="text-center text-gray-600 dark:text-gray-400 mb-1">Are you sure you want to remove</p>
            <p class="text-center font-bold text-gray-900 dark:text-white mb-6" id="deleteItemName"></p>
            
            <div class="flex gap-3">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 px-6 py-3 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold rounded-lg transition-colors">
                    Cancel
                </button>
                <button type="button" id="confirmDeleteBtn" disabled class="flex-1 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                    <span id="confirmBtnText">Wait (<span id="countdown">2</span>s)</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Hidden form for actual deletion -->
<form id="deleteForm" method="post" action="<?= $this->url('/cart/remove') ?>" style="display: none;">
    <?= $this->csrfField() ?>
    <input type="hidden" name="product_id" id="deleteProductId" value="">
</form>

<script>
let deleteTimer = null;
let deleteProductIdToRemove = null;

function showDeleteConfirm(productId, productName) {
    deleteProductIdToRemove = productId;
    document.getElementById('deleteItemName').textContent = productName;
    document.getElementById('deleteProductId').value = productId;
    
    const modal = document.getElementById('deleteModal');
    const modalContent = document.getElementById('deleteModalContent');
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    const countdownEl = document.getElementById('countdown');
    const confirmText = document.getElementById('confirmBtnText');
    
    // Show modal with animation
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
    
    // Reset button state
    confirmBtn.disabled = true;
    let timeLeft = 2;
    countdownEl.textContent = timeLeft;
    confirmText.innerHTML = 'Wait (<span id="countdown">' + timeLeft + '</span>s)';
    
    // Start countdown
    deleteTimer = setInterval(() => {
        timeLeft--;
        if (timeLeft > 0) {
            countdownEl.textContent = timeLeft;
        } else {
            clearInterval(deleteTimer);
            confirmBtn.disabled = false;
            confirmText.textContent = 'Remove Item';
            confirmBtn.classList.add('animate-pulse');
        }
    }, 1000);
    
    // Add click handler to confirm button
    confirmBtn.onclick = function() {
        if (!this.disabled) {
            document.getElementById('deleteForm').submit();
        }
    };
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    const modalContent = document.getElementById('deleteModalContent');
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    
    // Clear timer
    if (deleteTimer) {
        clearInterval(deleteTimer);
        deleteTimer = null;
    }
    
    // Hide with animation
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    confirmBtn.classList.remove('animate-pulse');
    
    setTimeout(() => {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }, 200);
}

// Close modal when clicking outside
document.getElementById('deleteModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>
