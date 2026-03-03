<?php
/**
 * EMERGENCY DIAGNOSTIC - Shows why order creation is failing
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Emergency Order Diagnostic</h1>";
echo "<style>body{font-family:monospace;background:#f5f5f5;padding:20px;}pre{background:white;padding:15px;border:1px solid #ddd;overflow:auto;}</style>";

// Load config
require_once __DIR__ . '/../config/database.php';

try {
    // Connect
    $host = $config['grocery']['host'];
    $db = $config['grocery']['database'];
    $user = $config['grocery']['username'];
    $pass = $config['grocery']['password'];
    
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color:green;'>✅ <strong>Connected to: $db</strong></p>";
    
    // Get ACTUAL table structure
    echo "<h2>1. Actual 'orders' Table Structure in Production</h2>";
    $stmt = $pdo->query("SHOW CREATE TABLE orders");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<pre>" . htmlspecialchars($result['Create Table']) . "</pre>";
    
    // Attempt minimal insert to see exact error
    echo "<h2>2. Testing Minimal Order Insert</h2>";
    
    $testData = [
        'user_id' => 1,
        'order_number' => 'DIAG_' . time(),
        'status' => 'pending',
        'subtotal' => 100.00,
        'total_amount' => 100.00
    ];
    
    echo "<p><strong>Inserting minimal data:</strong></p>";
    echo "<pre>" . print_r($testData, true) . "</pre>";
    
    try {
        $sql = "INSERT INTO orders (user_id, order_number, status, subtotal, total_amount) 
                VALUES (:user_id, :order_number, :status, :subtotal, :total_amount)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($testData);
        $id = $pdo->lastInsertId();
        
        echo "<p style='color:green;'>✅ <strong>SUCCESS! Created order ID: $id</strong></p>";
        
        // Clean up
        $pdo->exec("DELETE FROM orders WHERE id = $id");
        echo "<p style='color:gray;'><em>Test order deleted</em></p>";
        
        // Now test with FULL checkout data
        echo "<h2>3. Testing Full Checkout Data</h2>";
        
        $fullData = [
            'user_id' => 1,
            'order_number' => 'FULL_' . time(),
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
            'shipping_email' => 'test@test.com',
            'shipping_phone' => '080-0000-0000',
            'shipping_address_line1' => 'Test Address',
            'shipping_address_line2' => null,
            'shipping_city' => 'Tokyo',
            'shipping_state' => null,
            'shipping_postal_code' => '100-0001',
            'shipping_country' => 'Japan',
            'customer_notes' => null,
            'admin_notes' => null
        ];
        
        $fields = array_keys($fullData);
        $placeholders = array_map(function($f) { return ":$f"; }, $fields);
        
        $sql = "INSERT INTO orders (" . implode(', ', $fields) . ") 
                VALUES (" . implode(', ', $placeholders) . ")";
        
        echo "<p><strong>SQL:</strong></p>";
        echo "<pre>" . htmlspecialchars($sql) . "</pre>";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($fullData);
        $id = $pdo->lastInsertId();
        
        echo "<p style='color:green;'>✅ <strong>FULL DATA SUCCESS! Created order ID: $id</strong></p>";
        echo "<p style='color:blue;'><strong>This means the database is fine. The issue is in the PHP code.</strong></p>";
        
        $pdo->exec("DELETE FROM orders WHERE id = $id");
        
    } catch (PDOException $e) {
        echo "<p style='color:red;'>❌ <strong>INSERT FAILED!</strong></p>";
        echo "<pre style='background:#fee;color:#c00;padding:15px;'>";
        echo "<strong>Error Message:</strong>\n" . $e->getMessage() . "\n\n";
        echo "<strong>Error Code:</strong>\n" . $e->getCode() . "\n\n";
        echo "<strong>SQL Query:</strong>\n" . $sql;
        echo "</pre>";
        
        // Check which field is the problem
        if (preg_match("/Column '([^']+)'/", $e->getMessage(), $matches)) {
            echo "<p style='background:#ffc;padding:10px;'><strong>Problem Column:</strong> {$matches[1]}</p>";
        }
    }
    
    // Check error log
    echo "<h2>4. Recent Application Error Logs</h2>";
    $logFile = __DIR__ . '/../logs/error.log';
    if (file_exists($logFile)) {
        $lines = file($logFile);
        $recent = array_slice($lines, -30);
        echo "<pre style='max-height:400px;overflow:auto;'>";
        foreach ($recent as $line) {
            if (stripos($line, 'order') !== false || stripos($line, 'Model Create') !== false) {
                echo "<span style='background:#ff0;'>" . htmlspecialchars($line) . "</span>";
            } else {
                echo htmlspecialchars($line);
            }
        }
        echo "</pre>";
    } else {
        echo "<p style='color:orange;'>No error log found at: $logFile</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color:red;'>❌ <strong>Database Connection Failed:</strong></p>";
    echo "<pre style='background:#fee;color:#c00;padding:15px;'>" . $e->getMessage() . "</pre>";
}

echo "<hr><p style='color:gray;'><em>Diagnostic completed: " . date('Y-m-d H:i:s') . "</em></p>";
