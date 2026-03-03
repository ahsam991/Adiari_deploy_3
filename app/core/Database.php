<?php
/**
 * Database Connection Handler - FIXED VERSION
 * Supports multiple database connections with robust error handling
 * Works on both Localhost (XAMPP) and Hostinger Production
 */

class Database {
    private static $connections = [];
    private static $config = null;
    private static $connectionAttempts = [];
    private static $maxRetries = 3;
    private static $retryDelay = 1; // seconds
    private static $checkedConnections = []; // Track checked connections per request

    /**
     * Initialize database configuration
     */
    public static function init($config) {
        self::$config = $config;
        
        // Log environment detection
        if (isset($config['_environment'])) {
            error_log("Database: Running in {$config['_environment']} environment");
        }
    }

    /**
     * Get database connection by name with retry logic
     * @param string $dbName Database name (grocery, inventory, analytics, shop)
     * @return PDO
     */
    public static function getConnection($dbName = 'grocery') {
        // Return existing connection if available
        if (isset(self::$connections[$dbName])) {
            // Only check connection health once per request or if forced
            if (!isset(self::$checkedConnections[$dbName])) {
                try {
                    // Test if connection is still alive
                    self::$connections[$dbName]->query('SELECT 1');
                    self::$checkedConnections[$dbName] = true;
                    return self::$connections[$dbName];
                } catch (PDOException $e) {
                    // Connection is dead, remove it
                    unset(self::$connections[$dbName]);
                    unset(self::$checkedConnections[$dbName]);
                    error_log("Database: Stale connection detected for '{$dbName}', reconnecting...");
                }
            } else {
                return self::$connections[$dbName]; // Return without checking again
            }
        }

        // Load configuration if not already loaded
        if (!self::$config) {
            self::$config = require __DIR__ . '/../../config/database.php';
        }

        // Validate database exists in config
        if (!isset(self::$config[$dbName])) {
            throw new Exception("Database configuration for '{$dbName}' not found");
        }

        $db = self::$config[$dbName];
        $attempt = 0;
        $lastError = null;

        // Try to connect with retries
        while ($attempt < self::$maxRetries) {
            $attempt++;
            
            try {
                $pdo = self::createConnection($db, $dbName, $attempt);
                
                // Store connection for reuse
                self::$connections[$dbName] = $pdo;
                self::$connectionAttempts[$dbName] = $attempt;
                
                // Log successful connection
                error_log("Database: Successfully connected to '{$dbName}' on attempt {$attempt}");
                
                return $pdo;

            } catch (PDOException $e) {
                $lastError = $e;
                error_log("Database: Connection attempt {$attempt} failed for '{$dbName}': " . $e->getMessage());
                
                // Wait before retry (except on last attempt)
                if ($attempt < self::$maxRetries) {
                    sleep(self::$retryDelay);
                }
            }
        }

        // All retries failed
        $errorMessage = self::buildDetailedErrorMessage($dbName, $db, $lastError);
        error_log("Database: All connection attempts failed for '{$dbName}'");
        throw new Exception($errorMessage);
    }

    /**
     * Create a PDO connection
     */
    private static function createConnection($db, $dbName, $attempt) {
        // Build DSN
        $dsn = self::buildDSN($db);
        
        // Prepare options
        $options = $db['options'] ?? [];
        
        // Add default options if not set
        if (!isset($options[PDO::ATTR_ERRMODE])) {
            $options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        }
        if (!isset($options[PDO::ATTR_DEFAULT_FETCH_MODE])) {
            $options[PDO::ATTR_DEFAULT_FETCH_MODE] = PDO::FETCH_ASSOC;
        }
        if (!isset($options[PDO::ATTR_EMULATE_PREPARES])) {
            $options[PDO::ATTR_EMULATE_PREPARES] = false;
        }
        if (!isset($options[PDO::ATTR_PERSISTENT])) {
            $options[PDO::ATTR_PERSISTENT] = $db['persistent'] ?? false;
        }
        if (!isset($options[PDO::ATTR_TIMEOUT])) {
            $options[PDO::ATTR_TIMEOUT] = 10;
        }

        // Create PDO connection
        $pdo = new PDO($dsn, $db['username'], $db['password'], $options);
        
        // Set charset if specified
        if (isset($db['charset'])) {
            $charset = $db['charset'];
            $collation = $db['collation'] ?? 'utf8mb4_unicode_ci';
            $pdo->exec("SET NAMES '{$charset}' COLLATE '{$collation}'");
        }
        
        return $pdo;
    }

    /**
     * Build DSN string based on configuration
     */
    private static function buildDSN($db) {
        // Use unix socket if available and exists (for localhost)
        if (isset($db['unix_socket']) && !empty($db['unix_socket']) && file_exists($db['unix_socket'])) {
            return "mysql:unix_socket={$db['unix_socket']};dbname={$db['database']};charset={$db['charset']}";
        }
        
        // Otherwise use host and port
        $host = $db['host'] ?? 'localhost';
        $port = $db['port'] ?? 3306;
        $database = $db['database'];
        $charset = $db['charset'] ?? 'utf8mb4';
        
        return "mysql:host={$host};port={$port};dbname={$database};charset={$charset}";
    }

    /**
     * Build detailed error message for debugging
     */
    private static function buildDetailedErrorMessage($dbName, $db, $exception) {
        $message = "Could not connect to database '{$dbName}'.\n\n";
        
        // Add environment info
        if (isset(self::$config['_environment'])) {
            $message .= "Environment: " . self::$config['_environment'] . "\n";
        }
        
        // Add connection details (hide password)
        $message .= "Host: " . ($db['host'] ?? 'not set') . "\n";
        $message .= "Port: " . ($db['port'] ?? 'not set') . "\n";
        $message .= "Database: " . ($db['database'] ?? 'not set') . "\n";
        $message .= "Username: " . ($db['username'] ?? 'not set') . "\n";
        
        if (isset($db['unix_socket'])) {
            $socketExists = file_exists($db['unix_socket']) ? 'YES' : 'NO';
            $message .= "Unix Socket: {$db['unix_socket']} (exists: {$socketExists})\n";
        }
        
        // Add error details
        if ($exception) {
            $message .= "\nError: " . $exception->getMessage() . "\n";
            $message .= "Error Code: " . $exception->getCode() . "\n";
        }
        
        // Add troubleshooting tips
        $message .= "\n--- Troubleshooting Tips ---\n";
        
        if (isset(self::$config['_environment']) && self::$config['_environment'] === 'local') {
            $message .= "- Make sure XAMPP/MAMP MySQL is running\n";
            $message .= "- Check if database '{$db['database']}' exists in phpMyAdmin\n";
            $message .= "- Verify MySQL is running on port {$db['port']}\n";
            $message .= "- Try importing database/unified_hostinger_setup.sql\n";
        } else {
            $message .= "- Verify database credentials in Hostinger cPanel\n";
            $message .= "- Check if database '{$db['database']}' exists in phpMyAdmin\n";
            $message .= "- Ensure MySQL is running on Hostinger server\n";
            $message .= "- Contact Hostinger support if issue persists\n";
        }
        
        return $message;
    }

    /**
     * Test database connection
     * @param string $dbName Database name
     * @return array Status and message
     */
    public static function testConnection($dbName = 'grocery') {
        try {
            $pdo = self::getConnection($dbName);
            $version = $pdo->query('SELECT VERSION()')->fetchColumn();
            
            return [
                'success' => true,
                'message' => "Connected successfully to '{$dbName}'",
                'mysql_version' => $version,
                'attempts' => self::$connectionAttempts[$dbName] ?? 1
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'attempts' => self::$maxRetries
            ];
        }
    }

    /**
     * Execute a prepared statement
     * @param string $query SQL query
     * @param array $params Parameters for prepared statement
     * @param string $dbName Database name
     * @return PDOStatement
     */
    public static function query($query, $params = [], $dbName = 'grocery') {
        try {
            $pdo = self::getConnection($dbName);
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Query Error [{$dbName}]: " . $e->getMessage() . " | Query: " . $query);
            throw new Exception("Database query failed: " . $e->getMessage());
        }
    }

    /**
     * Fetch all results
     */
    public static function fetchAll($query, $params = [], $dbName = 'grocery') {
        $stmt = self::query($query, $params, $dbName);
        return $stmt->fetchAll();
    }

    /**
     * Fetch single row
     */
    public static function fetchOne($query, $params = [], $dbName = 'grocery') {
        $stmt = self::query($query, $params, $dbName);
        return $stmt->fetch();
    }

    /**
     * Get last inserted ID
     */
    public static function lastInsertId($dbName = 'grocery') {
        return self::getConnection($dbName)->lastInsertId();
    }

    /**
     * Begin transaction
     */
    public static function beginTransaction($dbName = 'grocery') {
        return self::getConnection($dbName)->beginTransaction();
    }

    /**
     * Commit transaction
     */
    public static function commit($dbName = 'grocery') {
        return self::getConnection($dbName)->commit();
    }

    /**
     * Rollback transaction
     */
    public static function rollback($dbName = 'grocery') {
        return self::getConnection($dbName)->rollBack();
    }

    /**
     * Close all connections
     */
    public static function closeAll() {
        self::$connections = [];
    }

    /**
     * Get connection statistics
     */
    public static function getStats() {
        return [
            'active_connections' => count(self::$connections),
            'databases' => array_keys(self::$connections),
            'attempts' => self::$connectionAttempts,
            'environment' => self::$config['_environment'] ?? 'unknown'
        ];
    }
}
