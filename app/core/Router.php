<?php
/**
 * Router Class
 * Handles URL routing with SEO-friendly URLs and middleware support
 */

class Router {
    private $routes = [];
    private $middlewares = [];
    private $currentRoute = null;

    /**
     * Add GET route
     */
    public function get($uri, $action, $middleware = []) {
        $this->addRoute('GET', $uri, $action, $middleware);
    }

    /**
     * Add POST route
     */
    public function post($uri, $action, $middleware = []) {
        $this->addRoute('POST', $uri, $action, $middleware);
    }

    /**
     * Add route for any HTTP method
     */
    private function addRoute($method, $uri, $action, $middleware = []) {
        // Convert URI to regex pattern
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $uri);
        $pattern = '#^' . $pattern . '$#';

        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'pattern' => $pattern,
            'action' => $action,
            'middleware' => (array)$middleware
        ];
    }

    /**
     * Dispatch the router
     */
    public function dispatch() {
        $requestUri = $this->getRequestUri();
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            // Check if method matches
            if ($route['method'] !== $requestMethod) {
                continue;
            }

            // Check if URI matches pattern
            if (preg_match($route['pattern'], $requestUri, $matches)) {
                $this->currentRoute = $route;

                // Extract route parameters (use array_values to create a positional
                // array so that PHP 8's named-argument behaviour in
                // call_user_func_array does not require param names to match)
                $params = array_values(array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY));

                // Execute middleware
                if (!empty($route['middleware'])) {
                    foreach ($route['middleware'] as $middleware) {
                        $middlewareClass = $middleware;
                        if (class_exists($middlewareClass)) {
                            $middlewareInstance = new $middlewareClass();
                            if (!$middlewareInstance->handle()) {
                                return; // Middleware stopped execution
                            }
                        }
                    }
                }

                // Execute controller action
                return $this->executeAction($route['action'], $params);
            }
        }

        // No route found - 404
        $this->handleNotFound();
    }

    /**
     * Execute controller action
     */
    private function executeAction($action, $params = []) {
        if (is_callable($action)) {
            // Direct function call
            return call_user_func_array($action, $params);
        }

        if (is_string($action)) {
            // Controller@method format
            list($controllerName, $method) = explode('@', $action);
            
            if (strpos($controllerName, 'Controller') === false) {
                $controllerClass = $controllerName . 'Controller';
            } else {
                $controllerClass = $controllerName;
            }
            $controllerFile = __DIR__ . '/../controllers/' . $controllerClass . '.php';

            if (!file_exists($controllerFile)) {
                throw new Exception("Controller file not found: {$controllerFile}");
            }

            require_once $controllerFile;

            if (!class_exists($controllerClass)) {
                throw new Exception("Controller class not found: {$controllerClass}");
            }

            $controller = new $controllerClass();

            if (!method_exists($controller, $method)) {
                throw new Exception("Method {$method} not found in {$controllerClass}");
            }

            return call_user_func_array([$controller, $method], $params);
        }
    }

    /**
     * Get clean request URI
     */
    private function getRequestUri() {
        $uri = $_SERVER['REQUEST_URI'];
        
        // Remove query string
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }

        // Remove base path if in subdirectory
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $basePath = rtrim(dirname($scriptName), '/');
        
        // Normalize base path
        if ($basePath === '/' || $basePath === '\\') {
            $basePath = '';
        }
        
        // Remove base path from URI if present
        if ($basePath && strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }

        // Ensure URI starts with / and has no trailing slash (except for root)
        $uri = '/' . ltrim($uri, '/');
        return rtrim($uri, '/') ?: '/';
    }

    /**
     * Handle 404 Not Found
     */
    private function handleNotFound() {
        http_response_code(404);
        
        try {
            // Render 404 view with layout
            $view = new View();
            $view->render('errors/404', [], 'layouts/main');
        } catch (Exception $e) {
            // Fallback if view rendering fails
            error_log("Failed to render 404 view: " . $e->getMessage());
            echo "<h1>404 - Page Not Found</h1>";
            echo "<p>The requested route could not be found.</p>";
            
            // Show debug info in debug mode
            $isDebug = defined('DEBUG') ? constant('DEBUG') : false;
            if ($isDebug) {
                echo "<hr>";
                echo "<h3>Debug Info:</h3>";
                echo "<p><strong>Script Name:</strong> " . ($_SERVER['SCRIPT_NAME'] ?? 'NOT SET') . "</p>";
                echo "<p><strong>Request URI:</strong> " . ($_SERVER['REQUEST_URI'] ?? 'NOT SET') . "</p>";
                echo "<p><strong>Resolved Path:</strong> " . $this->getRequestUri() . "</p>";
                echo "<p><strong>View Error:</strong> " . $e->getMessage() . "</p>";
            }
        }
    }

    /**
     * Generate URL from route name
     */
    public static function url($path) {
        // Get the base path (directory where index.php is located)
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
        $basePath = rtrim(dirname($scriptName), '/');
        
        // If we're in root directory, basePath will be empty or '/' or '.'
        // Normalize to empty string for consistency
        if ($basePath === '/' || $basePath === '\\' || $basePath === '.') {
            $basePath = '';
        }
        
        // Ensure path starts with /
        $path = '/' . ltrim($path, '/');
        
        // Return clean URL - for root installs, this will be /path
        // For subdirectory installs, this will be /subdir/path
        return $basePath . $path;
    }

    /**
     * Redirect to URL
     */
    public static function redirect($url) {
        // If URL starts with http:// or https://, redirect directly
        if (preg_match('/^https?:\/\//', $url)) {
            header("Location: " . $url);
            exit;
        }
        
        // Otherwise, generate proper URL with base path
        header("Location: " . self::url($url));
        exit;
    }
}
