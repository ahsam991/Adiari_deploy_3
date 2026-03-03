<?php
/**
 * DIRECT ORDER INSERT TEST
 * This will show the EXACT error preventing order creation
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Order Insert Test</h1>";
echo "<style>body{font-family:monospace;padding:20px;background:#f5f5f5;}pre{background:white;padding:15px;border:2px solid #333;}</style>";

require_once __DIR__ . '/../config/database.php';

try {
    $pdo = new PDO(
        "mysql:host={$config['grocery']['host']};dbname={$config['grocery']['database']};charset=utf8mb4",
        $config['grocery']['username'],
        $config['grocery']['password']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color:green;'>✅ Connected to database: {$config['grocery']['database']}</p>";
    
    // Exact data structure from CheckoutController
    $testOrder = [
        'user_id' => 1,
        'order_number' => 'TEST_' . time(),
        'status' => 'pending',
        'payment_method' => 'cod',
        'payment_status' => 'pending',
        'subtotal' => 1000.00,
        'tax_amount' => 100.00,
        'shipping_cost' => 0.00,
        'discount_amount' => 0.00,
        'total_amount' => 1100.00,
        'coupon_code' => null,
        'shipping_first_name' => 'Test',
        'shipping_last_name' => 'User',
        'shipping_email' => 'test@example.com',
        'shipping_phone' => '080-0000-0000',
        'shipping_address_line1' => 'Test Address 123',
        'shipping_address_line2' => null,
        'shipping_city' => 'Tokyo',
        'shipping_state' => null,
        'shipping_postal_code' => '100-0001',
        'shipping_country' => 'Japan',
        'customer_notes' => null,
        'admin_notes' => null
    ];
    
    echo "<h2>Testing Order Insert with This Data:</h2>";
    echo "<pre>" . print_r($testOrder, true) . "</pre>";
    
    $fields = array_keys($testOrder);
    $placeholders = array_map(function($f) { return ":$f"; }, $fields);
    
    $sql = "INSERT INTO orders (" . implode(', ', $fields) . ") 
            VALUES (" . implode(', ', $placeholders) . ")";
    
    echo "<h2>SQL Query:</h2>";
    echo "<pre>" . htmlspecialchars($sql) . "</pre>";
    
    $stmt = $pdo->prepare($sql);
    
    // Execute with exact values
    $success = $stmt->execute($testOrder);
    
    if ($success) {
        $orderId = $pdo->lastInsertId();
        echo "<h2 style='color:green;'>✅ SUCCESS!</h2>";
        echo "<p>Order inserted successfully with ID: <strong>$orderId</strong></p>";
        
        // Clean up
        $pdo->exec("DELETE FROM orders WHERE id = $orderId");
        echo "<p style='color:gray;'><em>Test order deleted</em></p>";
        
        echo "<hr>";
        echo "<h2 style='color:blue;'>🎉 YOUR CHECKOUT SHOULD WORK NOW!</h2>";
        echo "<p>The database can accept orders. The issue must be in the PHP code.</p>";
        echo "<p><strong>Try placing an order again.</strong></p>";
    }
    
} catch (PDOException $e) {
    echo "<h2 style='color:red;'>❌ INSERT FAILED!</h2>";
    echo "<div style='background:#fee;padding:20px;border:2px solid red;'>";
    echo "<h3>Error Details:</h3>";
    echo "<p><strong>Error Message:</strong><br>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>Error Code:</strong> " . $e->getCode() . "</p>";
    
    // Parse out the specific issue
    $msg = $e->getMessage();
    
    if (stripos($msg, "Column") !== false && stripos($msg, "cannot be null") !== false) {
        preg_match("/Column '([^']+)'/", $msg, $matches);
        if (isset($matches[1])) {
            echo "<hr>";
            echo "<h3 style='color:orange;'>🔍 ROOT CAUSE:</h3>";
            echo "<p>Column '<strong>{$matches[1]}</strong>' has a NOT NULL constraint but we're trying to insert NULL.</p>";
            echo "<p><strong>FIX:</strong> Run this SQL:</p>";
            echo "<pre>ALTER TABLE orders MODIFY COLUMN {$matches[1]} VARCHAR(255) NULL;</pre>";
        }
    }
    
    if (stripos($msg, "Data too long") !== false) {
        preg_match("/column '([^']+)'/", $msg, $matches);
        if (isset($matches[1])) {
            echo "<hr>";
            echo "<h3 style='color:orange;'>🔍 ROOT CAUSE:</h3>";
            echo "<p>Column '<strong>{$matches[1]}</strong>' is too small for the data.</p>";
        }
    }
    
    if (stripos($msg, "foreign key constraint") !== false) {
        echo "<hr>";
        echo "<h3 style='color:orange;'>🔍 ROOT CAUSE:</h3>";
        echo "<p>Foreign key constraint issue. Check that user_id=1 exists in users table.</p>";
        echo "<pre>SELECT id FROM users WHERE id = 1;</pre>";
    }
    
    echo "</div>";
}
