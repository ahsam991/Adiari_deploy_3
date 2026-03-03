<?php
/**
 * Forgot Password Page
 */
?>

<div class="min-h-screen flex items-center justify-center px-4 py-12 bg-gradient-to-br from-green-50 to-green-100">
    <div class="max-w-md w-full">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Forgot Password?</h1>
            <p class="text-gray-600">Enter your email to receive reset instructions</p>
        </div>

        <!--Forgot Password Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            
            <!-- Error/Success Messages -->
            <?php if (Session::hasFlash('error')): ?>
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
                    <p class="text-red-700 font-medium"><?= Session::getFlash('error') ?></p>
                </div>
            <?php endif; ?>

            <?php if (Session::hasFlash('success')): ?>
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg">
                    <p class="text-green-700 font-medium"><?= Session::getFlash('success') ?></p>
                    
                    <!-- Show reset token for demo (remove in production) -->
                    <?php if (Session::hasFlash('reset_token')): ?>
                        <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded">
                            <p class="text-xs font-semibold text-yellow-800 mb-1">Demo Reset Link:</p>
                            <a href="/reset-password?token=<?= Session::getFlash('reset_token') ?>" 
                               class="text-xs text-blue-600 hover:underline break-all">
                                /reset-password?token=<?= Session::getFlash('reset_token') ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Forgot Password Form -->
            <form action="<?= $this->url("/forgot-password") ?>" method="POST" class="space-y-6">
                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?= Security::generateCsrfToken() ?>">

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                        required
                        autofocus
                        placeholder="your@email.com"
                    >
                    <p class="mt-2 text-xs text-gray-500">
                        We'll send you instructions to reset your password.
                    </p>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full bg-primary hover:bg-green-600 text-white font-semibold py-3 px-6 rounded-lg transition transform hover:scale-105 shadow-lg"
                >
                    Send Reset Instructions
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
