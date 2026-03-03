<?php
/**
 * Reset Password Page
 */


$token = $data['token'] ?? '';
$error = Session::getFlash('error');
$errors = Session::getFlash('errors') ?? [];
?>

<div class="min-h-screen flex items-center justify-center px-4 py-12 bg-gradient-to-br from-green-50 to-green-100">
    <div class="max-w-md w-full">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Reset Password</h1>
            <p class="text-gray-600">Enter your new password below</p>
        </div>

        <!-- Reset Password Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            
            <!-- Error/Success Messages -->
            <?php if ($error): ?>
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
                    <p class="text-red-700 font-medium"><?= htmlspecialchars($error) ?></p>
                </div>
            <?php endif; ?>

            <!-- Reset Password Form -->
            <form action="/reset-password" method="POST" class="space-y-6">
                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?= Security::generateCsrfToken() ?>">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

                <!-- New Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        New Password <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                        required
                        minlength="6"
                        autofocus
                    >
                    <p class="mt-1 text-xs text-gray-500">Minimum 6 characters</p>
                    <?php if (!empty($errors['password'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars(is_array($errors['password']) ? $errors['password'][0] : $errors['password']) ?></p>
                    <?php endif; ?>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirm" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirm New Password <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="password" 
                        id="password_confirm" 
                        name="password_confirm" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                        required
                        minlength="6"
                    >
                    <?php if (!empty($errors['password_confirm'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars(is_array($errors['password_confirm']) ? $errors['password_confirm'][0] : $errors['password_confirm']) ?></p>
                    <?php endif; ?>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full bg-primary hover:bg-green-600 text-white font-semibold py-3 px-6 rounded-lg transition transform hover:scale-105 shadow-lg"
                >
                    Reset Password
                </button>
            </form>

            <!-- Divider -->
            <div class="mt-6 text-center">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Remember your password?</span>
                    </div>
                </div>
            </div>

            <!-- Back to Login -->
            <div class="mt-4 text-center">
                <a href="/login" class="text-primary hover:text-green-600 font-medium transition">
                    Back to Login
                </a>
            </div>
        </div>

        <!-- Back to Home -->
        <div class="mt-6 text-center">
            <a href="/" class="text-gray-600 hover:text-gray-900 transition inline-flex items-center">
                <span class="material-symbols-outlined text-sm mr-1">arrow_back</span>
                Back to Home
            </a>
        </div>
    </div>
</div>
