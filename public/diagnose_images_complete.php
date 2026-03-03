<?php
/**
 * Image Display Diagnostic Tool
 * This script helps diagnose why images are not displaying
 */

// Set up basic environment
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "========================================\n";
echo "IMAGE DISPLAY DIAGNOSTIC TOOL\n";
echo "========================================\n\n";

// 1. Check if public/uploads/products directory exists
$uploadsDir = __DIR__ . '/public/uploads/products';
echo "1. Upload Directory Check:\n";
echo "   Path: $uploadsDir\n";
echo "   Exists: " . (is_dir($uploadsDir) ? "YES" : "NO") . "\n";
echo "   Writable: " . (is_writable($uploadsDir) ? "YES" : "NO") . "\n";

if (is_dir($uploadsDir)) {
    $images = glob($uploadsDir . '/*');
    echo "   Files count: " . count($images) . "\n";
    if (count($images) > 0) {
        echo "   Sample files:\n";
        foreach (array_slice($images, 0, 5) as $img) {
            echo "      - " . basename($img) . " (" . filesize($img) . " bytes)\n";
        }
    }
}
echo "\n";

// 2. Check database configuration
echo "2. Database Connection Check:\n";
require_once __DIR__ . '/app/config/database.php';
require_once __DIR__ . '/app/core/Database.php';

try {
    $result = Database::fetchOne("SELECT COUNT(*) as count FROM products", [], 'grocery');
    echo "   Products in database: " . ($result['count'] ?? 0) . "\n";
    
    // Check products with images
    $withImages = Database::fetchOne(
        "SELECT COUNT(*) as count FROM products WHERE primary_image IS NOT NULL AND primary_image != ''", 
        [], 
        'grocery'
    );
    echo "   Products with primary_image: " . ($withImages['count'] ?? 0) . "\n";
    
    // Get sample products with images
    $samples = Database::fetchAll(
        "SELECT id, name, primary_image FROM products WHERE primary_image IS NOT NULL AND primary_image != '' LIMIT 5",
        [],
        'grocery'
    );
    
    if (count($samples) > 0) {
        echo "\n   Sample products with images:\n";
        foreach ($samples as $product) {
            echo "      ID: {$product['id']} - {$product['name']}\n";
            echo "         primary_image: '{$product['primary_image']}'\n";
            
            // Check if file exists
            $possiblePaths = [
                __DIR__ . '/public' . $product['primary_image'],
                __DIR__ . '/public/' . ltrim($product['primary_image'], '/'),
                __DIR__ . '/' . ltrim($product['primary_image'], '/'),
            ];
            
            $found = false;
            foreach ($possiblePaths as $path) {
                if (file_exists($path)) {
                    echo "         File EXISTS at: $path\n";
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                echo "         File NOT FOUND in any expected location\n";
                echo "         Checked paths:\n";
                foreach ($possiblePaths as $p) {
                    echo "            - $p\n";
                }
            }
        }
    }
} catch (Exception $e) {
    echo "   ERROR: " . $e->getMessage() . "\n";
}
echo "\n";

// 3. Test View::productImage() method
echo "3. Testing View::productImage() Method:\n";
require_once __DIR__ . '/app/core/View.php';
require_once __DIR__ . '/app/core/Router.php';

$view = new View();

$testPaths = [
    '/uploads/products/test.jpg',
    'uploads/products/test.jpg',
    'test.jpg',
    'public/uploads/products/test.jpg',
    null,
    ''
];

foreach ($testPaths as $path) {
    $pathDisplay = $path ?? 'NULL';
    if ($path === '') $pathDisplay = 'EMPTY STRING';
    
    $result = $view->productImage($path);
    echo "   Input: '$pathDisplay'\n";
    echo "      Output: '$result'\n";
}
echo "\n";

// 4. Check Router::url() method
echo "4. Testing Router::url() Method:\n";
$testUrls = [
    '/uploads/products/test.jpg',
    '/images/logo.png',
    '/products',
];

foreach ($testUrls as $url) {
    try {
        $result = Router::url($url);
        echo "   Input: '$url' → Output: '$result'\n";
    } catch (Exception $e) {
        echo "   Input: '$url' → ERROR: " . $e->getMessage() . "\n";
    }
}
echo "\n";

// 5. Test actual rendering context
echo "5. View Rendering Context Test:\n";
echo "   Testing if \$this is accessible in view context...\n";

// Create a test view file
$testViewContent = '<?php
$testResult = [];
$testResult["this_exists"] = isset($this);
$testResult["this_class"] = isset($this) ? get_class($this) : "N/A";
$testResult["method_exists"] = isset($this) && method_exists($this, "productImage");
return $testResult;
?>';

$testViewPath = __DIR__ . '/test_view_context.php';
file_put_contents($testViewPath, $testViewContent);

try {
    ob_start();
    $viewInstance = new View();
    $reflection = new ReflectionMethod($viewInstance, 'render');
    $reflection->setAccessible(true);
    
    // Can't easily test without modifying View, so we'll just report
    echo "   View class exists: YES\n";
    echo "   productImage method exists: " . (method_exists($viewInstance, 'productImage') ? "YES" : "NO") . "\n";
    ob_end_clean();
} catch (Exception $e) {
    ob_end_clean();
    echo "   ERROR: " . $e->getMessage() . "\n";
}

@unlink($testViewPath);
echo "\n";

echo "========================================\n";
echo "DIAGNOSTIC COMPLETE\n";
echo "========================================\n";
