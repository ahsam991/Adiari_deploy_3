<?php
/**
 * Change Password Page
 */

$success = Session::getFlash('success');
$error = Session::getFlash('error');
$errors = Session::getFlash('errors') ?? [];
?>

<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Change Password</h1>
        <p class="text-gray-600 mt-2">Update your account password</p>
    </div>

    <div class="max-w-2xl">
        <!-- Success/Error Messages -->
        <?php if ($success): ?>
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg">
                <p class="text-green-700 font-medium"><?= htmlspecialchars($success) ?></p>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
                <p class="text-red-700 font-medium"><?= htmlspecialchars($error) ?></p>
            </div>
        <?php endif; ?>

        <!-- Change Password Form -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <form action="/account/change-password" method="POST" class="space-y-6">
                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?= Security::generateCsrfToken() ?>">

                <!-- Current Password -->
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                        Current Password <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="password" 
                        id="current_password" 
                        name="current_password" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                        required
                        autofocus
                    >
                    <?php if (!empty($errors['current_password'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars(is_array($errors['current_password']) ? $errors['current_password'][0] : $errors['current_password']) ?></p>
                    <?php endif; ?>
                </div>

                <!-- New Password -->
                <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                        New Password <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="password" 
                        id="new_password" 
                        name="new_password" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                        required
                        minlength="6"
                    >
                    <p class="mt-1 text-xs text-gray-500">Minimum 6 characters</p>
                    <?php if (!empty($errors['new_password'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars(is_array($errors['new_password']) ? $errors['new_password'][0] : $errors['new_password']) ?></p>
                    <?php endif; ?>
                </div>

                <!-- Confirm New Password -->
                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirm New Password <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="password" 
                        id="confirm_password" 
                        name="confirm_password" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                        required
                        minlength="6"
                    >
                    <?php if (!empty($errors['confirm_password'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars(is_array($errors['confirm_password']) ? $errors['confirm_password'][0] : $errors['confirm_password']) ?></p>
                    <?php endif; ?>
                </div>

                <!-- Security Tips -->
                <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <h4 class="font-semibold text-blue-900 mb-2 text-sm">Password Security Tips:</h4>
                    <ul class="text-xs text-blue-800 space-y-1 list-disc list-inside">
                        <li>Use at least 6 characters</li>
                        <li>Include uppercase and lowercase letters</li>
                        <li>Add numbers and special characters</li>
                        <li>Avoid common words or personal information</li>
                    </ul>
                </div>

                <!-- Buttons -->
                <div class="flex items-center space-x-4">
                    <button 
                        type="submit" 
                        class="px-6 py-3 bg-primary hover:bg-green-600 text-white font-semibold rounded-lg transition transform hover:scale-105 shadow-lg"
                    >
                        Update Password
                    </button>
                    <a 
                        href="/account" 
                        class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition"
                    >
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Additional Links -->
        <div class="mt-6 flex justify-between text-sm">
            <a href="/forgot-password" class="text-primary hover:text-green-600 transition">
                Forgot your current password?
            </a>
            <a href="/account" class="text-gray-600 hover:text-gray-900 transition">
                ← Back to Account
            </a>
        </div>
    </div>
</div>
