<?php
/**
 * Login Page
 */

$old = Session::getFlash('old') ?? [];
$error = Session::getFlash('error');
$success = Session::getFlash('success');
?>

<div class="min-h-screen flex items-center justify-center px-4 py-12 bg-gradient-to-br from-emerald-50 via-white to-teal-50">
    <div class="max-w-md w-full">
        <!-- Social Proof -->
        <div class="flex items-center justify-center gap-3 mb-6">
            <div class="flex -space-x-2">
                <div class="w-8 h-8 rounded-full bg-emerald-400 border-2 border-white flex items-center justify-center text-white text-xs font-bold">A</div>
                <div class="w-8 h-8 rounded-full bg-orange-400 border-2 border-white flex items-center justify-center text-white text-xs font-bold">B</div>
                <div class="w-8 h-8 rounded-full bg-blue-400 border-2 border-white flex items-center justify-center text-white text-xs font-bold">C</div>
            </div>
            <p class="text-sm text-gray-600 font-medium">Join <span class="font-black text-emerald-700">5,000+</span> happy customers</p>
        </div>

        <!-- Header -->
        <div class="text-center mb-8">
            <a href="<?= $this->url('/') ?>">
                <img src="<?= $this->url('/images/logo.svg') ?>" alt="ADI ARI Fresh" class="h-14 w-auto mx-auto mb-4 object-contain" onerror="this.style.display='none'">
            </a>
            <h1 class="text-3xl font-black text-gray-900 mb-1">Welcome Back</h1>
            <p class="text-gray-500">Sign in to your ADI ARI Fresh account</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
            
            <!-- Error/Success Messages -->
            <?php if ($error): ?>
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
                    <p class="text-red-700 font-medium"><?= htmlspecialchars($error) ?></p>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg">
                    <p class="text-green-700 font-medium"><?= htmlspecialchars($success) ?></p>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form action="<?= $this->url("/login") ?>" method="POST" class="space-y-6">
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
                        value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                        required
                        autofocus
                    >
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                        required
                    >
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            id="remember" 
                            name="remember" 
                            value="1"
                            class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                        >
                        <label for="remember" class="ml-2 block text-sm text-gray-700">
                            Remember me
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="<?= $this->url("/forgot-password") ?>" class="text-primary hover:text-green-600 font-medium transition">
                            Forgot password?
                        </a>
                    </div>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 px-6 rounded-xl transition shadow-lg hover:shadow-emerald-200 text-base min-h-[52px]"
                >
                    Sign In
                </button>
            </form>

            <!-- Divider -->
            <div class="mt-6 text-center">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-3 bg-white text-gray-500">Don't have an account?</span>
                    </div>
                </div>
            </div>

            <!-- Register Link + Guest -->
            <div class="mt-4 flex flex-col gap-3 text-center">
                <a href="<?= $this->url('/register') ?>" class="text-emerald-700 hover:text-emerald-800 font-bold transition">
                    Create a free account →
                </a>
                <a href="<?= $this->url('/products') ?>" class="text-sm text-gray-400 hover:text-gray-600 transition">
                    Continue browsing as guest
                </a>
            </div>
        </div>

        <!-- Back to Home -->
        <div class="mt-6 text-center">
            <a href="<?= $this->url('/') ?>" class="text-gray-500 hover:text-gray-700 transition inline-flex items-center gap-1 text-sm">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                Back to Home
            </a>
        </div>
    </div>
</div>
