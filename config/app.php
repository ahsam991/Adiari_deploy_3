<?php
/**
 * Application Configuration
 */

// Detect environment
$isProduction = (
    isset($_SERVER['HTTP_HOST']) && 
    strpos($_SERVER['HTTP_HOST'], 'localhost') === false &&
    strpos($_SERVER['HTTP_HOST'], '127.0.0.1') === false
);

return [
    // Application name
    'name' => 'ADI ARI FRESH VEGETABLES AND HALAL FOOD',

    // Application URL    
    'url' => $isProduction ? 'https://' . ($_SERVER['HTTP_HOST'] ?? 'adiari.shop') : 'http://localhost:8000',

    // Debug mode (automatically enabled in development, disabled in production)
    'debug' => !$isProduction,

    // Environment
    'env' => $isProduction ? 'production' : 'development',

    // Timezone
    'timezone' => 'Asia/Tokyo',

    // Default language
    'language' => 'en',

    // Session configuration
    'session' => [
        'lifetime' => 7200, // 2 hours in seconds
        'cookie_name' => 'adiari_session',
    ],

    // File upload configuration
    'upload' => [
        'max_size' => 5242880, // 5MB in bytes
        'allowed_images' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
        'allowed_documents' => ['application/pdf'],
        'upload_path' => 'public/uploads/',
    ],

    // Pagination
    'pagination' => [
        'per_page' => 20,
    ],

    // Business information
    'business' => [
        'name' => 'ADI ARI FRESH VEGETABLES AND HALAL FOOD',
        'address' => '114-0031 Higashi Tabata 2-3-1 Otsu building 101',
        'phone' => '080-3408-8044',
        'email' => 'info@adiari.shop',
    ],

    // Security
    'security' => [
        'password_min_length' => 8,
        'max_login_attempts' => 5,
        'lockout_time' => 900, // 15 minutes in seconds
    ],
];
