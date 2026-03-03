<?php
/**
 * Order Creation Diagnostic Tool
 * This will show exactly why order creation is failing
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load database config
require_once __DIR__ . '/../config/database.php';

echo "<h1>Order Creation Diagnostic</h1>";
echo "<style>body{font-family:monospace;padding:20px;background:#f5f5f5;}table{border-collapse:collapse;background:white;margin:10px 0;}th,td{border:1px solid #ddd;padding:8px;text-align:left;}th{background:#333;color:white;}pre{background:#f0f0f0;padding:10px;border-radius:4px;overflow:auto;}</style>";

// Connect to database
try {
    $host = $config['grocery']['host'] ?? 'localhost';
    $dbname = $config['grocery']['database'] ?? '';
    $username = $config['grocery']['username'] ?? '';
    $password = $config['grocery']['password'] ?? '';
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<div style='background:#d4edda;padding:10px;border-radius:4px;color:#155724;margin-bottom:20px;'>";
    echo "✅ <strong>Database Connected:</strong> $dbname on $host";
    echo "</div>";
    
} catch (PDOException $e) {
    echo "<div style='background:#f8d7da;padding:10px;border-radius:4px;color:#721c24;'>";
    echo "❌ <strong>Database Connection Failed:</strong> " . $e->getMessage();
    echo "</div>";
    exit;
}

// 1. Check orders table structure
echo "<h2>1. Orders Table Structure</h2>";
try {
    $stmt = $pdo->query("DESCRIBE orders");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table>";
    echo "<tr><th>Column</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td><strong>" . htmlspecialchars($col['Field']) . "</strong></td>";
        echo "<td>" . htmlspecialchars($col['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($col['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($col['Key']) . "</td>";
        echo "<td>" . htmlspecialchars($col['Default'] ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Check for required checkout fields
    $requiredFields = [
        'user_id', 'order_number', 'subtotal', 'total_amount',
        'shipping_first_name', 'shipping_last_name', 'shipping_email',
        'shipping_phone', 'shipping_address_line1', 'shipping_city',
        'payment_method', 'status'
    ];
    
    $existingColumns = array_column($columns, 'Field');
    $missingColumns = array_diff($requiredFields, $existingColumns);
    
    if (empty($missingColumns)) {
        echo "<div style='background:#d4edda;padding:10px;border-radius:4px;color:#155724;margin:10px 0;'>";
        echo "✅ All required columns exist";
        echo "</div>";
    } else {
        echo "<div style='background:#f8d7da;padding:10px;border-radius:4px;color:#721c24;margin:10px 0;'>";
        echo "❌ <strong>Missing columns:</strong> " . implode(', ', $missingColumns);
        echo "</div>";
    }
    
} catch (PDOException $e) {
    echo "<div style='background:#f8d7da;padding:10px;border-radius:4px;color:#721c24;'>";
    echo "❌ Error checking table: " . $e->getMessage();
    echo "</div>";
}

// 2. Check error log
echo "<h2>2. Recent Error Log (Last 50 lines)</h2>";
$logPath = __DIR__ . '/../logs/error.log';

if (file_exists($logPath)) {
    $lines = file($logPath);
    $recentLines = array_slice($lines, -50);
    
    echo "<pre style='max-height:400px;overflow:auto;'>";
    foreach ($recentLines as $line) {
        // Highlight order-related errors
        if (stripos($line, 'order') !== false || stripos($line, 'Model Create') !== false) {
            echo "<span style='background:#fff3cd;color:#856404;'>" . htmlspecialchars($line) . "</span>";
        } else {
            echo htmlspecialchars($line);
        }
    }
    echo "</pre>";
} else {
    echo "<div style='background:#fff3cd;padding:10px;border-radius:4px;color:#856404;'>";
    echo "⚠️ Error log not found at: $logPath";
    echo "</div>";
}

// 3. Test order creation with sample data
echo "<h2>3. Test Order Insert</h2>";
try {
    $testData = [
        'user_id' => 1,
        'order_number' => 'TEST-' . time(),
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
    
    echo "<p><strong>Attempting to insert test order with data:</strong></p>";
    echo "<pre>" . print_r($testData, true) . "</pre>";
    
    $fields = array_keys($testData);
    $placeholders = array_map(function($field) { return ":$field"; }, $fields);
    
    $query = "INSERT INTO orders (" . implode(', ', $fields) . ") 
              VALUES (" . implode(', ', $placeholders) . ")";
    
    echo "<p><strong>SQL Query:</strong></p>";
    echo "<pre>" . htmlspecialchars($query) . "</pre>";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($testData);
    $testOrderId = $pdo->lastInsertId();
    
    echo "<div style='background:#d4edda;padding:10px;border-radius:4px;color:#155724;margin:10px 0;'>";
    echo "✅ <strong>SUCCESS!</strong> Test order created with ID: $testOrderId";
    echo "</div>";
    
    // Clean up test order
    $pdo->exec("DELETE FROM orders WHERE id = $testOrderId");
    echo "<p style='color:#666;'><em>Test order cleaned up</em></p>";
    
} catch (PDOException $e) {
    echo "<div style='background:#f8d7da;padding:10px;border-radius:4px;color:#721c24;margin:10px 0;'>";
    echo "❌ <strong>Insert Failed:</strong><br>";
    echo "Error: " . htmlspecialchars($e->getMessage()) . "<br>";
    echo "Code: " . $e->getCode();
    echo "</div>";
    
    // Show which field caused the issue
    if (preg_match("/Column '([^']+)'/", $e->getMessage(), $matches)) {
        echo "<div style='background:#fff3cd;padding:10px;border-radius:4px;color:#856404;margin:10px 0;'>";
        echo "💡 <strong>The problem is with column:</strong> " . htmlspecialchars($matches[1]);
        echo "</div>";
    }
}

// 4. Check PHP error log
echo "<h2>4. PHP Error Log Check</h2>";
$phpErrorLog = ini_get('error_log');
if ($phpErrorLog && file_exists($phpErrorLog)) {
    echo "<p><strong>PHP Error Log:</strong> $phpErrorLog</p>";
    $lines = file($phpErrorLog);
    $recentLines = array_slice($lines, -30);
    echo "<pre style='max-height:300px;overflow:auto;'>";
    echo htmlspecialchars(implode('', $recentLines));
    echo "</pre>";
} else {
    echo "<p>PHP error log location: " . ($phpErrorLog ?: 'default') . "</p>";
}

echo "<hr>";
echo "<p style='color:#666;'><em>Diagnostic completed at " . date('Y-m-d H:i:s') . "</em></p>";
