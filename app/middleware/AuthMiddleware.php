<?php
/**
 * Authentication Middleware
 * Ensures user is authenticated before accessing protected routes
 */

class AuthMiddleware {
    /**
     * Handle the middleware check
     * @return bool
     */
    public function handle() {
        // Check if user is logged in
        if (!Session::isLoggedIn()) {
            // Redirect to login page
            Session::setFlash('error', 'Please login to continue');
            Router::redirect('/login');
            return false;
        }

        return true;
    }
}
