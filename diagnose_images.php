<?php
/**
 * Image Diagnostic Tool
 */
require_once __DIR__ . '/app/core/Database.php';

echo "<h1>Image Diagnostic Tool</h1>";

// Check directories
$dirs = [
    'public/uploads',
    'public/uploads/products',
    'uploads',
    'uploads/products'
];

foreach ($dirs as $dir) {
    if (is_dir($dir)) {
        echo "✅ Directory exists: $dir<br>";
        $files = scandir($dir);
        echo "Files in $dir: " . count($files) . "<br>";
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                echo " - $file<br>";
            }
        }
    } else {
        echo "❌ Directory missing: $dir<br>";
    }
}

// Check database
try {
    $db = Database::getConnection('grocery');
    $stmt = $db->query("SELECT id, name, primary_image FROM products LIMIT 20");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h2>Products in Database</h2>";
    echo "<table border='1'><tr><th>ID</th><th>Name</th><th>Primary Image</th><th>File Exists?</th></tr>";
    foreach ($products as $p) {
        $path = $p['primary_image'];
        $exists = 'Empty';
        if (!empty($path)) {
            $fullPath = __DIR__ . '/public' . (str_starts_with($path, '/') ? '' : '/uploads/products/') . $path;
            $exists = file_exists($fullPath) ? '✅ Yes' : '❌ No (' . $fullPath . ')';
        }
        echo "<tr><td>{$p['id']}</td><td>{$p['name']}</td><td>{$path}</td><td>{$exists}</td></tr>";
    }
    echo "</table>";

} catch (Exception $e) {
    echo "Database Error: " . $e->getMessage();
}
