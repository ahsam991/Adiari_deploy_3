<?php
/**
 * Registration Page
 */

$old = Session::getFlash('old') ?? [];
$errors = Session::getFlash('errors') ?? [];
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
            <h1 class="text-3xl font-black text-gray-900 mb-1">Create Free Account</h1>
            <p class="text-gray-500">Join ADI ARI Fresh for healthy shopping</p>
        </div>

        <!-- Registration Card -->
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

            <!-- Registration Form -->
            <form action="<?= $this->url("/register") ?>" method="POST" class="space-y-6">
                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?= Security::generateCsrfToken() ?>">

                <!-- Name Fields (Side by Side) -->
                <div class="grid grid-cols-2 gap-4">
                    <!-- First Name -->
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                            First Name <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="first_name" 
                            name="first_name" 
                            value="<?= htmlspecialchars($old['first_name'] ?? '') ?>"
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
                            value="<?= htmlspecialchars($old['last_name'] ?? '') ?>"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                            required
                        >
                        <?php if (!empty($errors['last_name'])): ?>
                            <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars(is_array($errors['last_name']) ? $errors['last_name'][0] : $errors['last_name']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                        required
                    >
                    <?php if (!empty($errors['email'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars(is_array($errors['email']) ? $errors['email'][0] : $errors['email']) ?></p>
                    <?php endif; ?>
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
                        value="<?= htmlspecialchars($old['phone'] ?? '') ?>"
                        placeholder="080-1234-5678"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                    >
                    <?php if (!empty($errors['phone'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars(is_array($errors['phone']) ? $errors['phone'][0] : $errors['phone']) ?></p>
                    <?php endif; ?>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                        required
                        minlength="6"
                    >
                    <p class="mt-1 text-xs text-gray-500">Minimum 6 characters</p>
                    <?php if (!empty($errors['password'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars(is_array($errors['password']) ? $errors['password'][0] : $errors['password']) ?></p>
                    <?php endif; ?>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirm" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirm Password <span class="text-red-500">*</span>
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
                    Create Account
                </button>
            </form>

            <!-- Divider -->
            <div class="mt-6 text-center">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Already have an account?</span>
                    </div>
                </div>
            </div>

            <!-- Login Link -->
            <div class="mt-4 text-center">
                <a href="/login" class="text-primary hover:text-green-600 font-medium transition">
                    Sign in here
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
