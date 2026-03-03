<?php
/**
 * Edit Profile Page
 */


$user = $data['user'];
$old = Session::getFlash('old') ?? [];
$errors = Session::getFlash('errors') ?? [];
$success = Session::getFlash('success');
$error = Session::getFlash('error');
?>

<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Edit Profile</h1>
        <p class="text-gray-600 mt-2">Update your personal information</p>
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

        <!-- Profile Form -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <form action="/account/profile/update" method="POST" class="space-y-6">
                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?= Security::generateCsrfToken() ?>">

                <!-- First Name -->
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                        First Name <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="first_name" 
                        name="first_name" 
                        value="<?= htmlspecialchars($old['first_name'] ?? $user['first_name']) ?>"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                        required
                    >
                    <?php if (!empty($errors['first_name'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars(is_array($errors['first_name']) ? $errors['first_name'][0] : $errors['first_name']) ?></p>
                    <?php endif; ?>
                </div>

                <!-- Last Name -->
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Last Name <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="last_name" 
                        name="last_name" 
                        value="<?= htmlspecialchars($old['last_name'] ?? $user['last_name']) ?>"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                        required
                    >
                    <?php if (!empty($errors['last_name'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars(is_array($errors['last_name']) ? $errors['last_name'][0] : $errors['last_name']) ?></p>
                    <?php endif; ?>
                </div>

                <!-- Email (Read-only) -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        value="<?= htmlspecialchars($user['email']) ?>"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed"
                        readonly
                        disabled
                    >
                    <p class="mt-1 text-xs text-gray-500">Email cannot be changed</p>
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Phone Number
                    </label>
                    <input 
                        type="tel" 
                        id="phone" 
                        name="phone" 
                        value="<?= htmlspecialchars($old['phone'] ?? $user['phone'] ?? '') ?>"
                        placeholder="080-1234-5678"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                    >
                    <?php if (!empty($errors['phone'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars(is_array($errors['phone']) ? $errors['phone'][0] : $errors['phone']) ?></p>
                    <?php endif; ?>
                </div>

                <!-- Buttons -->
                <div class="flex items-center space-x-4">
                    <button 
                        type="submit" 
                        class="px-6 py-3 bg-primary hover:bg-green-600 text-white font-semibold rounded-lg transition transform hover:scale-105 shadow-lg"
                    >
                        Save Changes
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
            <a href="/account/change-password" class="text-primary hover:text-green-600 transition">
                Change Password →
            </a>
            <a href="/account" class="text-gray-600 hover:text-gray-900 transition">
                ← Back to Account
            </a>
        </div>
    </div>
</div>
