<?php
/**
 * User Addresses
 */
$addresses = $addresses ?? [];
$success = Session::getFlash('success');
$error = Session::getFlash('error');
?>

<div class="max-w-[1280px] mx-auto px-4 sm:px-10 py-8">
    <h1 class="text-2xl font-bold text-text-main-light dark:text-text-main-dark mb-6">My Addresses</h1>
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

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div>
            <h2 class="text-lg font-semibold text-text-main-light dark:text-text-main-dark mb-4">Add New Address</h2>
            <form method="post" action="<?= $this->url('/account/address/add') ?>" class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-xl p-6 space-y-4">
                <?= $this->csrfField() ?>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">First Name *</label>
                        <input type="text" name="first_name" required class="w-full px-3 py-2 border border-border-light dark:border-border-dark rounded-lg bg-background-light dark:bg-background-dark">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Last Name *</label>
                        <input type="text" name="last_name" required class="w-full px-3 py-2 border border-border-light dark:border-border-dark rounded-lg bg-background-light dark:bg-background-dark">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Phone *</label>
                    <input type="text" name="phone" required class="w-full px-3 py-2 border border-border-light dark:border-border-dark rounded-lg bg-background-light dark:bg-background-dark">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Address Line 1 *</label>
                    <input type="text" name="address_line1" required class="w-full px-3 py-2 border border-border-light dark:border-border-dark rounded-lg bg-background-light dark:bg-background-dark">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Address Line 2</label>
                    <input type="text" name="address_line2" class="w-full px-3 py-2 border border-border-light dark:border-border-dark rounded-lg bg-background-light dark:bg-background-dark">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">City *</label>
                        <input type="text" name="city" required class="w-full px-3 py-2 border border-border-light dark:border-border-dark rounded-lg bg-background-light dark:bg-background-dark">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Postal Code *</label>
                        <input type="text" name="postal_code" required class="w-full px-3 py-2 border border-border-light dark:border-border-dark rounded-lg bg-background-light dark:bg-background-dark">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">State/Prefecture</label>
                    <input type="text" name="state" class="w-full px-3 py-2 border border-border-light dark:border-border-dark rounded-lg bg-background-light dark:bg-background-dark">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Country</label>
                    <input type="text" name="country" value="Japan" class="w-full px-3 py-2 border border-border-light dark:border-border-dark rounded-lg bg-background-light dark:bg-background-dark">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Type</label>
                    <select name="address_type" class="w-full px-3 py-2 border border-border-light dark:border-border-dark rounded-lg bg-background-light dark:bg-background-dark">
                        <option value="home">Home</option>
                        <option value="work">Work</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="is_default" value="1">
                        <span class="text-sm">Set as default address</span>
                    </label>
                </div>
                <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary-hover text-white font-medium rounded-lg">Add Address</button>
            </form>
        </div>
        <div>
            <h2 class="text-lg font-semibold text-text-main-light dark:text-text-main-dark mb-4">Saved Addresses</h2>
            <?php if (empty($addresses)): ?>
                <p class="text-text-sub-light dark:text-text-sub-dark">No saved addresses yet.</p>
            <?php else: ?>
                <ul class="space-y-4">
                    <?php foreach ($addresses as $addr): ?>
                        <li class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-xl p-4">
                            <p class="font-medium"><?= htmlspecialchars($addr['first_name'] . ' ' . $addr['last_name']) ?></p>
                            <p class="text-sm text-text-sub-light dark:text-text-sub-dark"><?= htmlspecialchars($addr['address_line1']) ?>, <?= htmlspecialchars($addr['city']) ?> <?= htmlspecialchars($addr['postal_code']) ?></p>
                            <p class="text-sm"><?= htmlspecialchars($addr['phone']) ?></p>
                            <span class="text-xs px-2 py-0.5 rounded bg-primary/10 text-primary"><?= ucfirst($addr['address_type']) ?></span>
                            <?php if ($addr['is_default']): ?><span class="text-xs text-text-sub-light dark:text-text-sub-dark">(Default)</span><?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>
