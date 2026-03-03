<?php
/**
 * Check Database Image Paths
 */

require_once __DIR__ . '/app/core/Database.php';
require_once __DIR__ . '/config/database.php';

echo "=== DATABASE IMAGE PATH CHECK ===\n\n";

try {
    // Get sample products with images
    $products = Database::fetchAll(
        "SELECT id, name, primary_image FROM products WHERE primary_image IS NOT NULL AND primary_image != '' LIMIT 10",
        [],
        'grocery'
    );
    
    if (empty($products)) {
        echo "No products with images found in database.\n";
        exit;
    }
    
    echo "Found " . count($products) . " products with images:\n\n";
    
    $pathPatterns = [];
    
    foreach ($products as $p) {
        echo "Product ID: {$p['id']}\n";
        echo "Name: " . substr($p['name'], 0, 40) . "\n";
        echo "primary_image: '{$p['primary_image']}'\n";
        
        // Analyze path pattern
        if (strpos($p['primary_image'], '/public/') === 0) {
            $pattern = 'WRONG: /public/uploads/...';
        } elseif (strpos($p['primary_image'], 'public/') === 0) {
            $pattern = 'WRONG: public/uploads/...';
        } elseif (strpos($p['primary_image'], '/uploads/') === 0) {
            $pattern = 'CORRECT: /uploads/...';
        } elseif (strpos($p['primary_image'], 'uploads/') === 0) {
            $pattern = 'ACCEPTABLE: uploads/...';
        } else {
            $pattern = 'UNKNOWN: ' . substr($p['primary_image'], 0, 20);
        }
        
        echo "Pattern: $pattern\n";
        
        // Check if file exists
        $possiblePaths = [
            __DIR__ . '/public' . $p['primary_image'],
            __DIR__ . '/public/' . ltrim($p['primary_image'], '/'),
            __DIR__ . '/' . ltrim($p['primary_image'], '/'),
        ];
        
        $found = false;
        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                echo "✓ File EXISTS at: " . str_replace(__DIR__, '.', $path) . "\n";
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            echo "✗ File NOT FOUND\n";
        }
        
        echo "\n";
        
        // Track patterns
        if (!isset($pathPatterns[$pattern])) {
            $pathPatterns[$pattern] = 0;
        }
        $pathPatterns[$pattern]++;
    }
    
    echo "=== SUMMARY ===\n";
    foreach ($pathPatterns as $pattern => $count) {
        echo "$pattern: $count products\n";
    }
    
    // Check what format ManagerController saves
    echo "\n=== CHECKING UPLOAD CODE ===\n";
    $managerFile = __DIR__ . '/app/controllers/ManagerController.php';
    if (file_exists($managerFile)) {
        $content = file_get_contents($managerFile);
        if (preg_match("/\\\$data\['primary_image'\]\s*=\s*'([^']+)'/", $content, $matches)) {
            echo "ManagerController saves primary_image as: '{$matches[1]}'\n";
        }
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
