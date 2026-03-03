<?php
require_once __DIR__ . '/app/core/Database.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/models/ShopUser.php';

// Setup DB connection (assuming config is correct)
$db = Database::getConnection('grocery');

echo "Checking Shop User 'admin'...\n";
$stmt = $db->prepare("SELECT * FROM shop_users WHERE username = 'admin'");
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo "User found: " . $user['username'] . "\n";
    echo "Hash: " . $user['password'] . "\n";
    
    $inputPwd = 'admin123';
    if (password_verify($inputPwd, $user['password'])) {
        echo "[SUCCESS] Password '$inputPwd' matches the hash.\n";
    } else {
        echo "[FAILURE] Password '$inputPwd' does NOT match the hash.\n";
        echo "Generating new hash for '$inputPwd'...\n";
        $newHash = password_hash($inputPwd, PASSWORD_DEFAULT);
        echo "New Hash: $newHash\n";
        
        // Update DB
        $update = $db->prepare("UPDATE shop_users SET password = ? WHERE id = ?");
        $update->execute([$newHash, $user['id']]);
        echo "Database updated with new hash.\n";
    }
} else {
    echo "User 'admin' not found.\n";
}
