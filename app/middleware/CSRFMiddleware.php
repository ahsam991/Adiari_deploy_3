<?php
/**
 * CSRF Middleware
 * Validates CSRF tokens for POST requests
 */

class CSRFMiddleware {
    /**
     * Handle the middleware check
     * @return bool
     */
    public function handle() {
        // Only check POST, PUT, DELETE requests
        $method = $_SERVER['REQUEST_METHOD'];
        
        if (in_array($method, ['POST', 'PUT', 'DELETE'])) {
            // Get token from request
            $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;

            // Validate token
            if (!Security::validateCsrfToken($token)) {
                http_response_code(403);
                echo json_encode(['error' => 'Invalid CSRF token']);
                exit;
            }
        }

        return true;
    }
}
