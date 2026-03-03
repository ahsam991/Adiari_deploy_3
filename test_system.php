<?php
/**
 * Database Connection & Feature Test Script
 * Run this to verify all systems are working
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "\n";
echo "========================================\n";
echo "  ADI ARI FRESH - SYSTEM TEST\n";
echo "========================================\n\n";

// Load configuration
require_once __DIR__ . '/app/core/Database.php';
require_once __DIR__ . '/app/helpers/Email.php';
require_once __DIR__ . '/app/models/Product.php';
require_once __DIR__ . '/app/models/Order.php';

$results = [];
$errors = [];

// TEST 1: Database Connection
echo "TEST 1: Database Connection...\n";
try {
    $db = Database::getConnection('grocery');
    $stmt = $db->query("SELECT DATABASE() as db_name, VERSION() as version");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "  ✓ Connected to: " . $result['db_name'] . "\n";
    echo "  ✓ MySQL Version: " . $result['version'] . "\n";
    $results['database'] = 'PASS';
} catch (Exception $e) {
    echo "  ✗ ERROR: " . $e->getMessage() . "\n";
    $results['database'] = 'FAIL';
    $errors[] = "Database: " . $e->getMessage();
}

// TEST 2: Required Tables Exist
echo "\nTEST 2: Required Tables...\n";
$requiredTables = [
    'users', 'products', 'categories', 'cart', 'orders', 'order_items',
    'invoices', 'invoice_items', 'inventory_items', 'shop_users',
    'product_images', 'customers'
];

try {
    $db = Database::getConnection('grocery');
    $stmt = $db->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $missing = [];
    foreach ($requiredTables as $table) {
        if (in_array($table, $tables)) {
            echo "  ✓ {$table}\n";
        } else {
            echo "  ✗ {$table} - MISSING\n";
            $missing[] = $table;
        }
    }
    
    if (empty($missing)) {
        $results['tables'] = 'PASS';
    } else {
        $results['tables'] = 'FAIL';
        $errors[] = "Missing tables: " . implode(', ', $missing);
    }
} catch (Exception $e) {
    echo "  ✗ ERROR: " . $e->getMessage() . "\n";
    $results['tables'] = 'FAIL';
    $errors[] = "Tables check: " . $e->getMessage();
}

// TEST 3: Product Model
echo "\nTEST 3: Product Model...\n";
try {
    $productModel = new Product();
    $products = $productModel->getActiveProducts(5);
    
    echo "  ✓ Retrieved " . count($products) . " products\n";
    
    if (!empty($products)) {
        $firstProduct = $products[0];
        echo "  ✓ Sample product: " . $firstProduct['name'] . "\n";
        
        // Test image methods
        $images = $productModel->getProductImages($firstProduct['id']);
        echo "  ✓ Product has " . count($images) . " image(s)\n";
    }
    
    $results['product_model'] = 'PASS';
} catch (Exception $e) {
    echo "  ✗ ERROR: " . $e->getMessage() . "\n";
    $results['product_model'] = 'FAIL';
    $errors[] = "Product Model: " . $e->getMessage();
}

// TEST 4: Order Model
echo "\nTEST 4: Order Model...\n";
try {
    $orderModel = new Order();
    $recentOrders = $orderModel->getRecentOrders(5);
    
    echo "  ✓ Retrieved " . count($recentOrders) . " orders\n";
    $results['order_model'] = 'PASS';
} catch (Exception $e) {
    echo "  ✗ ERROR: " . $e->getMessage() . "\n";
    $results['order_model'] = 'FAIL';
    $errors[] = "Order Model: " . $e->getMessage();
}

// TEST 5: Email Configuration
echo "\nTEST 5: Email Configuration...\n";
try {
    $emailConfig = require __DIR__ . '/config/email.php';
    
    echo "  ✓ Email method: " . $emailConfig['method'] . "\n";
    echo "  ✓ From email: " . $emailConfig['from']['email'] . "\n";
    echo "  ✓ From name: " . $emailConfig['from']['name'] . "\n";
    
    $results['email_config'] = 'PASS';
} catch (Exception $e) {
    echo "  ✗ ERROR: " . $e->getMessage() . "\n";
    $results['email_config'] = 'FAIL';
    $errors[] = "Email Config: " . $e->getMessage();
}

// TEST 6: POS Database Connection
echo "\nTEST 6: POS Database (ShopDatabase)...\n";
try {
    require_once __DIR__ . '/app/core/ShopDatabase.php';
    $shopDb = ShopDatabase::getInstance();
    $connection = $shopDb->getConnection();
    
    // Test query
    $stmt = $connection->query("SELECT COUNT(*) as count FROM inventory_items");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "  ✓ POS Database connected\n";
    echo "  ✓ Inventory items: " . $result['count'] . "\n";
    
    $results['pos_database'] = 'PASS';
}catch (Exception $e) {
    echo "  ✗ ERROR: " . $e->getMessage() . "\n";
    $results['pos_database'] = 'FAIL';
    $errors[] = "POS Database: " . $e->getMessage();
}

// TEST 7: Stock Sync Triggers
echo "\nTEST 7: Stock Sync Triggers...\n";
try {
    $db = Database::getConnection('grocery');
    $stmt = $db->query("SHOW TRIGGERS LIKE 'after_product_update'");
    $trigger = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($trigger) {
        echo "  ✓ Stock sync trigger exists\n";
        $results['triggers'] = 'PASS';
    } else {
        echo "  ⚠ Stock sync trigger not found (optional)\n";
        $results['triggers'] = 'WARN';
    }
} catch (Exception $e) {
    echo "  ⚠ Could not check triggers: " . $e->getMessage() . "\n";
    $results['triggers'] = 'WARN';
}

// SUMMARY
echo "\n========================================\n";
echo "  TEST SUMMARY\n";
echo "========================================\n\n";

$passed = 0;
$failed = 0;
$warned = 0;

foreach ($results as $test => $result) {
    $icon = $result === 'PASS' ? '✓' : ($result === 'WARN' ? '⚠' : '✗');
    $color = $result === 'PASS' ? 'green' : ($result === 'WARN' ? 'yellow' : 'red');
    echo sprintf("  %s %-20s %s\n", $icon, ucwords(str_replace('_', ' ', $test)), $result);
    
    if ($result === 'PASS') $passed++;
    elseif ($result === 'FAIL') $failed++;
    elseif ($result === 'WARN') $warned++;
}

echo "\n";
echo "  Total: " . count($results) . " tests\n";
echo "  Passed: {$passed}\n";
echo "  Failed: {$failed}\n";
echo "  Warnings: {$warned}\n";

if (!empty($errors)) {
    echo "\n========================================\n";
    echo "  ERRORS\n";
    echo "========================================\n\n";
    foreach ($errors as $error) {
        echo "  • " . $error . "\n";
    }
}

echo "\n========================================\n";

if ($failed === 0) {
    echo "  STATUS: ALL CRITICAL TESTS PASSED ✓\n";
    echo "========================================\n\n";
    exit(0);
} else {
    echo "  STATUS: SOME TESTS FAILED ✗\n";
    echo "========================================\n\n";
    exit(1);
}
