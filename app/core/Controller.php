<?php
/**
 * Base Controller Class
 * All controllers extend this class
 */

class Controller {
    protected $view;
    protected $data = [];

    public function __construct() {
        // Initialize view instance
        $this->view = new View();
    }

    /**
     * Render a view
     * @param string $viewPath Path to view file
     * @param array $data Data to pass to view
     */
    protected function view($viewPath, $data = []) {
        $this->view->render($viewPath, $data);
    }

    /**
     * Return JSON response
     * @param mixed $data Data to encode as JSON
     * @param int $statusCode HTTP status code
     */
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Redirect to another page
     * @param string $url URL to redirect to
     */
    protected function redirect($url) {
        Router::redirect($url);
    }

    /**
     * Get POST data
     * @param string|null $key Specific key to get, or null for all
     * @param mixed $default Default value if key doesn't exist
     */
    protected function post($key = null, $default = null) {
        if ($key === null) {
            return $_POST;
        }
        return $_POST[$key] ?? $default;
    }

    /**
     * Get GET data
     * @param string|null $key Specific key to get, or null for all
     * @param mixed $default Default value if key doesn't exist
     */
    protected function get($key = null, $default = null) {
        if ($key === null) {
            return $_GET;
        }
        return $_GET[$key] ?? $default;
    }

    /**
     * Check if request is POST
     */
    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Check if request is AJAX
     */
    protected function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Set flash message
     * @param string $type Message type (success, error, warning, info)
     * @param string $message Message text
     */
    protected function setFlash($type, $message) {
        Session::setFlash($type, $message);
    }

    /**
     * Get current authenticated user data
     */
    protected function getUser() {
        if (!Session::isLoggedIn()) {
            return null;
        }
        
        return [
            'id' => Session::get('user_id'),
            'email' => Session::get('user_email'),
            'name' => Session::get('user_name'),
            'role' => Session::get('user_role')
        ];
    }

    /**
     * Check if user is authenticated
     */
    protected function isAuthenticated() {
        return Session::isLoggedIn();
    }

    /**
     * Check if user is logged in (session has user_id)
     */
    protected function isLoggedIn() {
        return Session::isLoggedIn();
    }

    /**
     * Validate CSRF token
     */
    protected function validateCsrf() {
        $token = $this->post('csrf_token');
        return Security::validateCsrfToken($token);
    }
}
