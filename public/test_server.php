<?php
/**
 * Server Diagnostic Script
 * Upload this to public_html and visit: https://adiari.shop/test_server.php
 * This will help identify what's causing the 500 error
 */

// Enable all error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo "<!DOCTYPE html><html><head><title>Server Diagnostic</title>";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;background:#f5f5f5;}";
echo ".section{background:white;padding:20px;margin:10px 0;border-radius:5px;box-shadow:0 2px 4px rgba(0,0,0,0.1);}";
echo ".success{color:#28a745;} .error{color:#dc3545;} .warning{color:#ffc107;}";
echo "h2{border-bottom:2px solid #007bff;padding-bottom:10px;} pre{background:#f8f9fa;padding:10px;border-radius:3px;overflow-x:auto;}";
echo "</style></head><body>";

echo "<h1>🔍 ADI ARI Server Diagnostic Report</h1>";
echo "<p>Generated: " . date('Y-m-d H:i:s') . "</p>";

// ===================================
// 1. PHP CONFIGURATION
// ===================================
echo "<div class='section'>";
echo "<h2>1. PHP Configuration</h2>";
echo "<table border='1' cellpadding='8' style='width:100%;border-collapse:collapse;'>";
echo "<tr><th>Setting</th><th>Value</th></tr>";
echo "<tr><td>PHP Version</td><td>" . phpversion() . "</td></tr>";
echo "<tr><td>Server Software</td><td>" . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "</td></tr>";
echo "<tr><td>Document Root</td><td>" . $_SERVER['DOCUMENT_ROOT'] . "</td></tr>";
echo "<tr><td>Current Script</td><td>" . __FILE__ . "</td></tr>";
echo "<tr><td>Error Reporting</td><td>" . error_reporting() . "</td></tr>";
echo "<tr><td>Display Errors</td><td>" . ini_get('display_errors') . "</td></tr>";
echo "<tr><td>Max Execution Time</td><td>" . ini_get('max_execution_time') . "s</td></tr>";
echo "<tr><td>Memory Limit</td><td>" . ini_get('memory_limit') . "</td></tr>";
echo "<tr><td>Upload Max Filesize</td><td>" . ini_get('upload_max_filesize') . "</td></tr>";
echo "<tr><td>Post Max Size</td><td>" . ini_get('post_max_size') . "</td></tr>";
echo "</table>";
echo "</div>";

// ===================================
// 2. FILE PATHS
// ===================================
echo "<div class='section'>";
echo "<h2>2. Critical Files Check</h2>";

$rootPath = dirname(__DIR__);
echo "<p><strong>Root Path:</strong> $rootPath</p>";

$criticalFiles = [
    'index.php' => __DIR__ . '/index.php',
    'config/database.php' => $rootPath . '/config/database.php',
    'app/core/Database.php' => $rootPath . '/app/core/Database.php',
    'app/core/Router.php' => $rootPath . '/app/core/Router.php',
    'app/core/Application.php' => $rootPath . '/app/core/Application.php',
];

echo "<table border='1' cellpadding='8' style='width:100%;border-collapse:collapse;'>";
echo "<tr><th>File</th><th>Status</th><th>Readable</th><th>Size</th></tr>";
foreach ($criticalFiles as $name => $path) {
    $exists = file_exists($path);
    $readable = is_readable($path);
    $size = $exists ? filesize($path) : 0;
    
    $statusClass = $exists ? 'success' : 'error';
    $statusText = $exists ? '✅ Exists' : '❌ Missing';
    
    echo "<tr>";
    echo "<td>$name</td>";
    echo "<td class='$statusClass'>$statusText</td>";
    echo "<td>" . ($readable ? '✅ Yes' : '❌ No') . "</td>";
    echo "<td>" . ($exists ? number_format($size) . ' bytes' : 'N/A') . "</td>";
    echo "</tr>";
}
echo "</table>";
echo "</div>";

// ===================================
// 3. DATABASE CONNECTION TEST
// ===================================
echo "<div class='section'>";
echo "<h2>3. Database Connection Test</h2>";

// Production database credentials
$dbHost = 'localhost';
$dbName = 'u314077991_adiari_shop';
$dbUser = 'u314077991_adiari_shop';
$dbPass = 'Bangladesh12*#';

try {
    echo "<p>Attempting to connect to database...</p>";
    echo "<ul>";
    echo "<li>Host: <code>$dbHost</code></li>";
    echo "<li>Database: <code>$dbName</code></li>";
    echo "<li>Username: <code>$dbUser</code></li>";
    echo "</ul>";
    
    $pdo = new PDO(
        "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4",
        $dbUser,
        $dbPass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
    
    echo "<p class='success'><strong>✅ DATABASE CONNECTION SUCCESSFUL!</strong></p>";
    
    // Get table count
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll();
    echo "<p>Found " . count($tables) . " tables in database</p>";
    
    // Test a simple query
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $result = $stmt->fetch();
    echo "<p>Users table has " . $result['count'] . " records</p>";
    
} catch (PDOException $e) {
    echo "<p class='error'><strong>❌ DATABASE CONNECTION FAILED</strong></p>";
    echo "<pre class='error'>" . htmlspecialchars($e->getMessage()) . "</pre>";
}
echo "</div>";

// ===================================
// 4. PHP EXTENSIONS
// ===================================
echo "<div class='section'>";
echo "<h2>4. Required PHP Extensions</h2>";

$requiredExtensions = ['pdo', 'pdo_mysql', 'mbstring', 'json', 'session', 'fileinfo'];
echo "<table border='1' cellpadding='8' style='width:100%;border-collapse:collapse;'>";
echo "<tr><th>Extension</th><th>Status</th></tr>";
foreach ($requiredExtensions as $ext) {
    $loaded = extension_loaded($ext);
    $statusClass = $loaded ? 'success' : 'error';
    $statusText = $loaded ? '✅ Loaded' : '❌ Missing';
    echo "<tr><td>$ext</td><td class='$statusClass'>$statusText</td></tr>";
}
echo "</table>";
echo "</div>";

// ===================================
// 5. PERMISSIONS CHECK
// ===================================
echo "<div class='section'>";
echo "<h2>5. Directory Permissions</h2>";

$dirsToCheck = [
    'uploads' => __DIR__ . '/uploads',
    'logs (if exists)' => $rootPath . '/logs',
];

echo "<table border='1' cellpadding='8' style='width:100%;border-collapse:collapse;'>";
echo "<tr><th>Directory</th><th>Exists</th><th>Writable</th><th>Permissions</th></tr>";
foreach ($dirsToCheck as $name => $path) {
    $exists = file_exists($path);
    $writable = is_writable($path);
    $perms = $exists ? substr(sprintf('%o', fileperms($path)), -4) : 'N/A';
    
    echo "<tr>";
    echo "<td>$name</td>";
    echo '<td>' . ($exists ? '✅ Yes' : '❌ No') . '</td>';
    echo '<td>' . ($writable ? '✅ Yes' : '❌ No') . '</td>';
    echo "<td>$perms</td>";
    echo "</tr>";
}
echo "</table>";
echo "</div>";

// ===================================
// 6. TEST MAIN APPLICATION
// ===================================
echo "<div class='section'>";
echo "<h2>6. Application Bootstrap Test</h2>";

try {
    echo "<p>Attempting to load application core files...</p>";
    
    // Try to require the core files
    require_once $rootPath . '/app/core/Database.php';
    echo "<p class='success'>✅ Database.php loaded</p>";
    
    require_once $rootPath . '/app/core/Router.php';
    echo "<p class='success'>✅ Router.php loaded</p>";
    
    require_once $rootPath . '/app/core/Application.php';
    echo "<p class='success'>✅ Application.php loaded</p>";
    
    echo "<p class='success'><strong>✅ All core files loaded successfully!</strong></p>";
    
} catch (Exception $e) {
    echo "<p class='error'><strong>❌ Error loading core files:</strong></p>";
    echo "<pre class='error'>" . htmlspecialchars($e->getMessage()) . "</pre>";
    echo "<pre class='error'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
} catch (Error $e) {
    echo "<p class='error'><strong>❌ Fatal error loading core files:</strong></p>";
    echo "<pre class='error'>" . htmlspecialchars($e->getMessage()) . "</pre>";
    echo "<pre class='error'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
echo "</div>";

// ===================================
// 7. SERVER ENVIRONMENT
// ===================================
echo "<div class='section'>";
echo "<h2>7. Server Environment Variables</h2>";
echo "<pre style='max-height:300px;overflow-y:auto;'>";
echo "HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'Not set') . "\n";
echo "SERVER_NAME: " . ($_SERVER['SERVER_NAME'] ?? 'Not set') . "\n";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'Not set') . "\n";
echo "DOCUMENT_ROOT: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Not set') . "\n";
echo "SCRIPT_FILENAME: " . ($_SERVER['SCRIPT_FILENAME'] ?? 'Not set') . "\n";
echo "</pre>";
echo "</div>";

echo "<div class='section'>";
echo "<h2>✅ Diagnostic Complete</h2>";
echo "<p>If you see this page, PHP is working. Check the sections above for any errors.</p>";
echo "<p><strong>Next Steps:</strong></p>";
echo "<ul>";
echo "<li>If database connection failed, check credentials in config/database.php</li>";
echo "<li>If files are missing, re-upload the complete project</li>";
echo "<li>If permissions are wrong, contact Hostinger support</li>";
echo "<li>Visit <a href='/'>https://adiari.shop/</a> to test the main application</li>";
echo "</ul>";
echo "</div>";

echo "</body></html>";
