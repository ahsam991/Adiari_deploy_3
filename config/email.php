<?php
/**
 * Email Configuration
 * Configure email settings for order notifications
 */

// Detect environment
$isProduction = (
    isset($_SERVER['HTTP_HOST']) && 
    strpos($_SERVER['HTTP_HOST'], 'localhost') === false &&
    strpos($_SERVER['HTTP_HOST'], '127.0.0.1') === false
);

return [
    // Email sending method: 'smtp' or 'mail' (PHP mail function)
    'method' => 'mail', // Change to 'smtp' when you have SMTP credentials
    
    // SMTP Configuration (for method = 'smtp')
    'smtp' => [
        'host' => 'smtp.gmail.com', // e.g., smtp.gmail.com, smtp.sendgrid.net
        'port' => 587, // 587 for TLS, 465 for SSL
        'username' => '', // Your SMTP username/email
        'password' => '', // Your SMTP password or app password
        'encryption' => 'tls', // 'tls' or 'ssl'
    ],
    
    // Sender Information
    'from' => [
        'email' => 'noreply@adiari.shop',
        'name' => 'ADI ARI Fresh Vegetables & Halal Food',
    ],
    
    // Reply-To (customer support email)
    'reply_to' => [
        'email' => 'info@adiari.shop',
        'name' => 'ADI ARI Fresh Support',
    ],
    
    // BCC for all order emails (optional - for record keeping)
    'bcc' => $isProduction ? 'orders@adiari.shop' : '',
    
    // Email templates directory
    'templates_path' => __DIR__ . '/../app/views/emails/',
    
    // Business Information for emails
    'business' => [
        'name' => 'ADI ARI FRESH VEGETABLES AND HALAL FOOD',
        'address' => '114-0031 Higashi Tabata 2-3-1 Otsu building 101',
        'phone' => '080-3408-8044',
        'email' => 'info@adiari.shop',
        'website' => $isProduction ? 'https://adiari.shop' : 'http://localhost:8000',
    ],
];
