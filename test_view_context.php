<?php
/**
 * Test if $this context works in views
 */

require_once __DIR__ . '/app/core/View.php';
require_once __DIR__ . '/app/core/Router.php';

echo "=== VIEW CONTEXT TEST ===\n\n";

// Create View instance
$view = new View();

echo "1. Testing View instance:\n";
echo "   - View class exists: " . (class_exists('View') ? 'YES' : 'NO') . "\n";
echo "   - productImage method exists: " . (method_exists($view, 'productImage') ? 'YES' : 'NO') . "\n\n";

echo "2. Testing productImage() with various inputs:\n";
$testPaths = [
    '/uploads/products/test.jpg',
    'uploads/products/test.jpg',
    '7f0e0600d6f699503450fe5d3a454197.png',
    null,
    ''
];

foreach ($testPaths as $path) {
    $pathDisplay = $path ?? 'NULL';
    if ($path === '') $pathDisplay = 'EMPTY';
    
    $result = $view->productImage($path);
    echo "   Input: '$pathDisplay'\n";
    echo "   Output: '$result'\n\n";
}

echo "3. Testing Router::url():\n";
try {
    $result = Router::url('/uploads/products/test.jpg');
    echo "   Input: '/uploads/products/test.jpg'\n";
    echo "   Output: '$result'\n\n";
} catch (Exception $e) {
    echo "   ERROR: " . $e->getMessage() . "\n\n";
}

echo "4. Checking actual image files:\n";
$uploadDir = __DIR__ . '/public/uploads/products';
if (is_dir($uploadDir)) {
    $files = glob($uploadDir . '/*');
    echo "   Found " . count($files) . " files\n";
    foreach (array_slice($files, 0, 3) as $file) {
        $basename = basename($file);
        echo "   - $basename\n";
        
        // Test what productImage returns for this
        $result = $view->productImage($basename);
        echo "     productImage('$basename') = '$result'\n";
        
        // Check if result file would exist
        $fullPath = __DIR__ . '/public' . str_replace(dirname($_SERVER['SCRIPT_NAME']), '', $result);
        echo "     File exists at public path: " . (file_exists($fullPath) ? 'YES' : 'NO') . "\n";
    }
}

echo "\n=== TEST COMPLETE ===\n";
