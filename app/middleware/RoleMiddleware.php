<?php
/**
 * Role Middleware
 * Ensures user has required role to access route
 */

class RoleMiddleware {
    private $requiredRole;

    public function __construct($role = 'customer') {
        $this->requiredRole = $role;
    }

    /**
     * Handle the middleware check
     * @return bool
     */
    public function handle() {
        // Check if user is logged in
        if (!Session::has('user')) {
            Session::setFlash('error', 'Please login to continue');
            Router::redirect('/login');
            return false;
        }

        $user = Session::get('user');
        
        // Check user role
        if (!isset($user['role']) || $user['role'] !== $this->requiredRole) {
            Session::setFlash('error', 'You do not have permission to access this page');
            Router::redirect('/');
            return false;
        }

        return true;
    }

    /**
     * Check if user has one of the specified roles
     * @param array $roles Array of allowed roles
     * @return bool
     */
    public static function hasRole($roles) {
        if (!Session::has('user')) {
            return false;
        }

        $user = Session::get('user');
        $userRole = $user['role'] ?? 'guest';

        return in_array($userRole, (array)$roles);
    }

    /**
     * Check if user is admin
     * @return bool
     */
    public static function isAdmin() {
        return self::hasRole(['admin']);
    }

    /**
     * Check if user is manager
     * @return bool
     */
    public static function isManager() {
        return self::hasRole(['manager', 'admin']);
    }

    /**
     * Check if user is customer
     * @return bool
     */
    public static function isCustomer() {
        return self::hasRole(['customer']);
    }
}
