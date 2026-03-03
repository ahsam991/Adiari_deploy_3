<?php
/**
 * Database Configuration - FIXED VERSION
 * Unified database for ADI ARI Fresh - Works on Both Localhost & Hostinger
 * 
 * HOSTINGER DATABASE INFO:
 * - Database: u314077991_adiari_shop
 * - Username: u314077991_adiari_shop
 * - Password: Bangladesh12*#
 * - Host (on Hostinger server): localhost or 127.0.0.1
 * - Port: 3306
 * 
 * LOCALHOST DATABASE INFO:
 * - Database: adiari_grocery (main database)
 * - Username: root
 * - Password: (empty)
 * - Host: localhost or 127.0.0.1
 * - Port: 3306
 */

// =============================================
// ENVIRONMENT DETECTION - IMPROVED
// =============================================
function detectEnvironment() {
    // Method 1: Check HTTP_HOST
    if (isset($_SERVER['HTTP_HOST'])) {
        $host = strtolower($_SERVER['HTTP_HOST']);
        
        // Localhost patterns
        if (strpos($host, 'localhost') !== false || 
            strpos($host, '127.0.0.1') !== false || 
            strpos($host, '::1') !== false) {
            return 'local';
        }
        
        // Hostinger patterns
        if (strpos($host, 'adiari.shop') !== false || 
            strpos($host, 'hstgr.io') !== false ||
            strpos($host, '.hostinger.') !== false) {
            return 'production';
        }
    }
    
    // Method 2: Check SERVER_NAME
    if (isset($_SERVER['SERVER_NAME'])) {
        $server = strtolower($_SERVER['SERVER_NAME']);
        if (strpos($server, 'localhost') !== false || 
            strpos($server, '127.0.0.1') !== false) {
            return 'local';
        }
    }
    
    // Method 3: Check if we're in Hostinger's file structure
    if (file_exists('/home/u314077991') || 
        strpos(getcwd(), '/home/u314077991') !== false) {
        return 'production';
    }
    
    // Method 4: Check for XAMPP indicators
    if (file_exists('/Applications/XAMPP') || 
        file_exists('C:/xampp') || 
        getenv('XAMPP_ROOT')) {
        return 'local';
    }
    
    // Default to local for safety (won't expose production credentials)
    return 'local';
}

$environment = detectEnvironment();
$isProduction = ($environment === 'production');

// =============================================
// DATABASE CONFIGURATION
// =============================================
if ($isProduction) {
    // =============================================
    // HOSTINGER PRODUCTION ENVIRONMENT
    // Single unified database: u314077991_adiari_shop
    // =============================================
    $dbConfig = [
        'grocery' => [
            'host' => 'localhost',
            'port' => 3306,
            'database' => 'u314077991_adiari_shop',
            'username' => 'u314077991_adiari_shop',
            'password' => 'Bangladesh12*#',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'persistent' => false,
            'options' => [
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
                PDO::ATTR_TIMEOUT => 10,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        ],
        'inventory' => [
            'host' => 'localhost',
            'port' => 3306,
            'database' => 'u314077991_adiari_shop',
            'username' => 'u314077991_adiari_shop',
            'password' => 'Bangladesh12*#',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'persistent' => false,
            'options' => [
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
                PDO::ATTR_TIMEOUT => 10,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        ],
        'analytics' => [
            'host' => 'localhost',
            'port' => 3306,
            'database' => 'u314077991_adiari_shop',
            'username' => 'u314077991_adiari_shop',
            'password' => 'Bangladesh12*#',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'persistent' => false,
            'options' => [
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
                PDO::ATTR_TIMEOUT => 10,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        ],
        'shop' => [
            'host' => 'localhost',
            'port' => 3306,
            'database' => 'u314077991_adiari_shop',
            'username' => 'u314077991_adiari_shop',
            'password' => 'Bangladesh12*#',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'persistent' => false,
            'options' => [
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
                PDO::ATTR_TIMEOUT => 10,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        ],
    ];
} else {
    // =============================================
    // LOCAL DEVELOPMENT ENVIRONMENT (XAMPP/MAMP)
    // Separate databases for different modules
    // =============================================
    
    // Try to detect XAMPP socket path
    $possibleSockets = [
        '/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock',  // macOS XAMPP
        '/opt/lampp/var/mysql/mysql.sock',                      // Linux XAMPP
        '/var/run/mysqld/mysqld.sock',                          // Linux default
        '/tmp/mysql.sock',                                       // macOS alternative
    ];
    
    $unixSocket = null;
    foreach ($possibleSockets as $socket) {
        if (file_exists($socket)) {
            $unixSocket = $socket;
            break;
        }
    }
    
    $dbConfig = [
        'grocery' => [
            'host' => '127.0.0.1',
            'port' => 3306,
            'database' => 'adiari_grocery',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'persistent' => false,
            'unix_socket' => $unixSocket,
            'options' => [
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
                PDO::ATTR_TIMEOUT => 10,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        ],
        'inventory' => [
            'host' => 'localhost',
            'port' => 3306,
            'database' => 'adiari_inventory',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'persistent' => false,
            'unix_socket' => $unixSocket,
            'options' => [
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
                PDO::ATTR_TIMEOUT => 10,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        ],
        'analytics' => [
            'host' => 'localhost',
            'port' => 3306,
            'database' => 'adiari_analytics',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'persistent' => false,
            'unix_socket' => $unixSocket,
            'options' => [
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
                PDO::ATTR_TIMEOUT => 10,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        ],
        'shop' => [
            'host' => 'localhost',
            'port' => 3306,
            'database' => 'adiari_grocery',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'persistent' => false,
            'unix_socket' => $unixSocket,
            'options' => [
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
                PDO::ATTR_TIMEOUT => 10,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        ],
    ];
}

// Add environment info to config
$dbConfig['_environment'] = $environment;
$dbConfig['_detected_at'] = date('Y-m-d H:i:s');

return $dbConfig;
