<?php
/**
 * Application Core Class  
 * Bootstraps the MVC framework
 */

class Application {
    private $router;
    private $config = [];

    public function __construct() {
        $this->init();
    }

    /**
     * Initialize application
     */
    private function init() {
        // Start session
        $this->startSession();

        // Load configuration
        $this->loadConfig();

        // Set error handling
        $this->setErrorHandling();

        // Initialize database
        Database::init($this->config['database']);

        // Initialize router
        $this->router = new Router();

        // Load routes
        $this->loadRoutes();
    }

    /**
     * Start session securely
     */
    private function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            $sessionConfig = [
                'cookie_httponly' => true,
                'cookie_secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
                'cookie_samesite' => 'Lax',
                'use_strict_mode' => true
            ];

            session_start($sessionConfig);

            // Regenerate session ID periodically
            if (!isset($_SESSION['last_regeneration']) || !is_int($_SESSION['last_regeneration'])) {
                session_regenerate_id(true);
                $_SESSION['last_regeneration'] = time();
            } elseif (time() - $_SESSION['last_regeneration'] > 1800) { // 30 minutes
                session_regenerate_id(true);
                $_SESSION['last_regeneration'] = time();
            }
        }
    }

    /**
     * Load configuration files
     */
    private function loadConfig() {
        $configFiles = ['app', 'database'];
        
        foreach ($configFiles as $file) {
            $configPath = __DIR__ . '/../../config/' . $file . '.php';
            if (file_exists($configPath)) {
                $this->config[$file] = require $configPath;
            }
        }
    }

    /**
     * Set error handling
     */
    private function setErrorHandling() {
        $debug = $this->config['app']['debug'] ?? false;
        if (!defined('DEBUG')) {
            define('DEBUG', $debug);
        }

        if ($debug) {
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
        } else {
            error_reporting(E_ALL);
            ini_set('display_errors', 0);
            ini_set('log_errors', 1);
            ini_set('error_log', __DIR__ . '/../../logs/error.log');
        }

        // Set custom error handler
        set_error_handler(function($severity, $message, $file, $line) {
            // Respect error_reporting level and @ operator
            if (!(error_reporting() & $severity)) {
                return false; // Let PHP handle it (suppressed error)
            }
            throw new ErrorException($message, 0, $severity, $file, $line);
        });

        // Set exception handler
        set_exception_handler(function($exception) use ($debug) {
            Logger::error($exception->getMessage(), [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString()
            ]);

            http_response_code(500);
            
            if ($debug) {
                echo "<h1>Application Error</h1>";
                echo "<p><strong>Message:</strong> " . $exception->getMessage() . "</p>";
                echo "<p><strong>File:</strong> " . $exception->getFile() . ":" . $exception->getLine() . "</p>";
                echo "<pre>" . $exception->getTraceAsString() . "</pre>";
            } else {
                echo "An error occurred. Please try again later.";
            }
        });
    }

    /**
     * Load routes
     */
    private function loadRoutes() {
        $routesFile = __DIR__ . '/../../routes/web.php';
        if (file_exists($routesFile)) {
            // Make $router available in routes file scope
            $router = $this->router;
            require $routesFile;
        }
    }

    /**
     * Run the application
     */
    public function run() {
        $this->router->dispatch();
    }

    /**
     * Get router instance
     */
    public function getRouter() {
        return $this->router;
    }

    /**
     * Get config value
     */
    public function getConfig($key, $default = null) {
        $keys = explode('.', $key);
        $value = $this->config;

        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return $default;
            }
            $value = $value[$k];
        }

        return $value;
    }
}
