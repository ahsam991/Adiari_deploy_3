<?php
/**
 * Quick Database Image Checker
 */

require_once __DIR__ . '/app/core/Database.php';
require_once __DIR__ . '/config/database.php';

echo "=== DATABASE IMAGE CHECK ===\n\n";

$products = Database::fetchAll(
    "SELECT id, name, primary_image FROM products WHERE primary_image IS NOT NULL AND primary_image != '' LIMIT 15",
    [],
    'grocery'
);

foreach ($products as $p) {
    echo sprintf("ID: %d | Name: %s\n", $p['id'], substr($p['name'], 0, 30));
    echo sprintf("  primary_image: '%s'\n", $p['primary_image']);
    
    // Check if file exists
    $possiblePaths = [
        __DIR__ . '/public' . $p['primary_image'],
        __DIR__ . '/public/' . ltrim($p['primary_image'], '/'),
        __DIR__ . '/' . ltrim($p['primary_image'], '/'),
    ];
    
    $found = false;
    foreach ($possiblePaths as $path) {
        if (file_exists($path)) {
            echo sprintf("  ✓ File EXISTS at: %s\n", str_replace(__DIR__, '.', $path));
            $found = true;
            break;
        }
    }
    
    if (!$found) {
        echo "  ✗ File NOT FOUND\n";
    }
    echo "\n";
}

echo "\n=== ACTUAL FILES IN public/uploads/products ===\n";
$files = glob(__DIR__ . '/public/uploads/products/*');
echo "Total files: " . count($files) . "\n";
foreach (array_slice($files, 0, 5) as $f) {
    echo "  - " . basename($f) . "\n";
}
