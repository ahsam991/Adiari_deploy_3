<?php
/**
 * 404 Error Page View
 */
?>

<!-- 404 Error Section -->
<section class="min-h-screen flex items-center bg-gradient-to-br from-emerald-50 to-green-50">
    <div class="container mx-auto px-4 sm:px-6 py-12">
        <div class="max-w-3xl mx-auto text-center">
            <!-- Error Icon Animation -->
            <div class="mb-8 animate-bounce">
                <svg class="w-32 h-32 mx-auto text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

            <!-- Error Code -->
            <h1 class="text-8xl sm:text-9xl font-black text-emerald-600 mb-4">
                404
            </h1>

            <!-- Error Message -->
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-4">
                Oops! Page Not Found
            </h2>

            <p class="text-lg text-gray-600 mb-8 max-w-xl mx-auto">
                The page you're looking for seems to have gone shopping. 
                Let's help you find your way back to fresh products!
            </p>

            <!-- Debug Info (only in debug mode) -->
            <?php if (defined('DEBUG') && DEBUG && isset($_SERVER['REQUEST_URI'])): ?>
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-8 text-left max-w-2xl mx-auto">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Debug Information</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p><strong>Requested URL:</strong> <?= htmlspecialchars($_SERVER['REQUEST_URI']) ?></p>
                            <p><strong>Script Name:</strong> <?= htmlspecialchars($_SERVER['SCRIPT_NAME'] ?? 'N/A') ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="<?= $this->url('/') ?>" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-8 py-4 rounded-xl font-bold hover:bg-emerald-700 transition-all shadow-lg hover:shadow-xl hover:-translate-y-1 transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Go to Homepage</span>
                </a>

                <a href="<?= $this->url('/products') ?>" class="inline-flex items-center gap-2 bg-white text-emerald-600 px-8 py-4 rounded-xl font-bold hover:bg-gray-50 transition-all shadow-lg hover:shadow-xl border-2 border-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span>Browse Products</span>
                </a>

                <a href="javascript:history.back()" class="inline-flex items-center gap-2 text-emerald-600 px-6 py-4 rounded-xl font-semibold hover:bg-emerald-50 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Go Back</span>
                </a>
            </div>

            <!-- Popular Links -->
            <div class="mt-12 pt-8 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Popular Pages</h3>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="<?= $this->url('/') ?>" class="text-emerald-600 hover:text-emerald-700 hover:underline">Home</a>
                    <span class="text-gray-400">•</span>
                    <a href="<?= $this->url('/products') ?>" class="text-emerald-600 hover:text-emerald-700 hover:underline">Products</a>
                    <span class="text-gray-400">•</span>
                    <a href="<?= $this->url('/about') ?>" class="text-emerald-600 hover:text-emerald-700 hover:underline">About Us</a>
                    <span class="text-gray-400">•</span>
                    <a href="<?= $this->url('/contact') ?>" class="text-emerald-600 hover:text-emerald-700 hover:underline">Contact</a>
                    <span class="text-gray-400">•</span>
                    <a href="<?= $this->url('/deals') ?>" class="text-emerald-600 hover:text-emerald-700 hover:underline">Special Deals</a>
                </div>
            </div>

            <!-- Search Box -->
            <div class="mt-8 max-w-md mx-auto">
                <form action="<?= $this->url('/products') ?>" method="GET" class="relative">
                    <input type="text" name="search" placeholder="Search for products..." 
                           class="w-full px-6 py-4 pr-12 rounded-xl border-2 border-gray-200 focus:border-emerald-500 focus:outline-none transition-colors">
                    <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-emerald-600 text-white p-2 rounded-lg hover:bg-emerald-700 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
