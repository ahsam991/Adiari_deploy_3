<?php
/**
 * Diagnostic script: Check orders table schema vs expected columns
 * Upload this to your production server and visit it once, then DELETE it.
 * URL: https://adiari.shop/diagnose_orders.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>ADI ARI - Orders Table Diagnostic</h2><pre>";

// Load database config
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/core/Database.php';

Database::init($dbConfig);

try {
    $pdo = Database::getConnection('grocery');
    echo "✅ Database connection successful\n\n";
    
    // Check actual table structure
    echo "=== ACTUAL 'orders' TABLE COLUMNS ===\n";
    $stmt = $pdo->query("DESCRIBE orders");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $actualColumns = [];
    foreach ($columns as $col) {
        $actualColumns[] = $col['Field'];
        echo sprintf("  %-30s %-20s %-5s %-10s %s\n", 
            $col['Field'], $col['Type'], $col['Null'], $col['Default'] ?? 'NULL', $col['Key']);
    }
    
    echo "\n=== EXPECTED COLUMNS (from code) ===\n";
    $expectedColumns = [
        'id', 'user_id', 'order_number', 'status', 'payment_method', 'payment_status',
        'subtotal', 'tax_amount', 'shipping_cost', 'discount_amount', 'total_amount',
        'coupon_code', 'shipping_first_name', 'shipping_last_name', 'shipping_email',
        'shipping_phone', 'shipping_address_line1', 'shipping_address_line2',
        'shipping_city', 'shipping_state', 'shipping_postal_code', 'shipping_country',
        'customer_notes', 'admin_notes', 'confirmed_at', 'shipped_at', 'delivered_at',
        'cancelled_at', 'payment_date', 'transaction_id', 'created_at', 'updated_at'
    ];
    
    echo implode(", ", $expectedColumns) . "\n\n";
    
    // Check mismatches
    $missing = array_diff($expectedColumns, $actualColumns);
    $extra = array_diff($actualColumns, $expectedColumns);
    
    if (!empty($missing)) {
        echo "❌ MISSING COLUMNS (needed by code, not in database):\n";
        foreach ($missing as $col) {
            echo "   - {$col}\n";
        }
    } else {
        echo "✅ All expected columns exist!\n";
    }
    
    if (!empty($extra)) {
        echo "\n⚠️  EXTRA COLUMNS (in database, not used by code):\n";
        foreach ($extra as $col) {
            echo "   - {$col}\n";
        }
    }
    
    // Check order_items table too
    echo "\n\n=== ACTUAL 'order_items' TABLE COLUMNS ===\n";
    $stmt = $pdo->query("DESCRIBE order_items");
    $oiColumns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $actualOiColumns = [];
    foreach ($oiColumns as $col) {
        $actualOiColumns[] = $col['Field'];
        echo sprintf("  %-30s %-20s %-5s %-10s %s\n", 
            $col['Field'], $col['Type'], $col['Null'], $col['Default'] ?? 'NULL', $col['Key']);
    }
    
    $expectedOiColumns = ['id', 'order_id', 'product_id', 'product_name', 'product_sku', 'quantity', 'unit_price', 'total_price', 'created_at'];
    $missingOi = array_diff($expectedOiColumns, $actualOiColumns);
    if (!empty($missingOi)) {
        echo "\n❌ MISSING ORDER_ITEMS COLUMNS:\n";
        foreach ($missingOi as $col) {
            echo "   - {$col}\n";
        }
    } else {
        echo "\n✅ All expected order_items columns exist!\n";
    }
    
    // Test a simple insert + rollback
    echo "\n\n=== TEST INSERT (will be rolled back) ===\n";
    $pdo->beginTransaction();
    try {
        $testQuery = "INSERT INTO orders (
            user_id, order_number, status, payment_method, payment_status,
            subtotal, tax_amount, shipping_cost, discount_amount, total_amount,
            coupon_code, shipping_first_name, shipping_last_name, shipping_email,
            shipping_phone, shipping_address_line1, shipping_address_line2,
            shipping_city, shipping_state, shipping_postal_code, shipping_country,
            customer_notes, admin_notes, created_at, updated_at
        ) VALUES (
            1, 'TEST-DIAG-0001', 'pending', 'cod', 'pending',
            100.00, 10.00, 0.00, 0.00, 110.00,
            NULL, 'Test', 'User', 'test@test.com',
            '1234567890', '123 Test St', NULL,
            'Tokyo', 'Tokyo', '100-0001', 'Japan',
            NULL, NULL, NOW(), NOW()
        )";
        
        $stmt = $pdo->prepare($testQuery);
        $result = $stmt->execute();
        $lastId = $pdo->lastInsertId();
        
        echo "Insert result: " . ($result ? 'true' : 'false') . "\n";
        echo "Last Insert ID: " . $lastId . "\n";
        
        if ($lastId) {
            echo "✅ Test insert SUCCEEDED (ID: {$lastId})\n";
        } else {
            echo "❌ Test insert returned no ID!\n";
        }
        
        $pdo->rollBack();
        echo "Test rolled back (no data was saved)\n";
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "❌ Test insert FAILED: " . $e->getMessage() . "\n";
        echo "\nThis is the same error causing checkout failure!\n";
    }

    // Check if there's a mismatch in the status ENUM
    echo "\n\n=== CHECK STATUS ENUM VALUES ===\n";
    $stmt = $pdo->query("SHOW COLUMNS FROM orders LIKE 'status'");
    $statusCol = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Status column type: " . $statusCol['Type'] . "\n";
    
    // Check if 'confirmed' is in the enum (code uses it but old migration doesn't have it)
    if (strpos($statusCol['Type'], 'confirmed') !== false) {
        echo "✅ 'confirmed' status exists in ENUM\n";
    } else {
        echo "❌ 'confirmed' status MISSING from ENUM (code expects it)\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "</pre>";
echo "<hr><p><strong>⚠️ DELETE THIS FILE AFTER RUNNING IT!</strong></p>";
