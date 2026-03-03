<?php
/**
 * Authentication Controller
 * Handles user registration, login, logout, and password reset
 */

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/Validation.php';
require_once __DIR__ . '/../helpers/Security.php';
require_once __DIR__ . '/../helpers/Session.php';
require_once __DIR__ . '/../helpers/Logger.php';

class AuthController extends Controller {
    private $userModel;

    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
    }

    /**
     * Show registration form
     */
    public function register() {
        // If already logged in, redirect to home
        if ($this->isLoggedIn()) {
            $this->redirect('/');
        }

        $this->view('auth/register', [
            'title' => 'Register - ADI ARI Fresh'
        ]);
    }

    /**
     * Process registration
     */
    public function registerPost() {
        // Validate CSRF token
        if (!$this->validateCsrf()) {
            Session::setFlash('error', 'Invalid request. Please try again.');
            $this->redirect('/register');
        }

        // Get form data
        $data = [
            'first_name' => $this->post('first_name'),
            'last_name' => $this->post('last_name'),
            'email' => $this->post('email'),
            'phone' => $this->post('phone'),
            'password' => $this->post('password'),
            'password_confirm' => $this->post('password_confirm')
        ];

        // Validate input
        $validation = new Validation();
        $validation->validate($data, [
            'first_name' => 'required|min:2|max:100',
            'last_name' => 'required|min:2|max:100',
            'email' => 'required|email|unique:users,email',
            'phone' => 'min:10|max:20',
            'password' => 'required|min:6|max:50',
            'password_confirm' => 'required|match:password'
        ]);

        if (!$validation->passes()) {
            Session::setFlash('error', 'Please fix the errors below.');
            Session::setFlash('errors', $validation->errors());
            Session::setFlash('old', $data);
            $this->redirect('/register');
        }

        try {
            // Create user
            $userId = $this->userModel->createUser([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => $data['password'],
                'role' => 'customer',
                'status' => 'active'
            ]);

            // Log activity
            Logger::info("New user registered: {$data['email']}", ['user_id' => $userId]);
            Logger::activity($userId, 'user_registered', [
                'email' => $data['email'],
                'name' => $data['first_name'] . ' ' . $data['last_name']
            ]);

            // Set success message
            Session::setFlash('success', 'Registration successful! Please login.');

            // Redirect to login
            $this->redirect('/login');

        } catch (Exception $e) {
            Logger::error("Registration failed: " . $e->getMessage());
            Session::setFlash('error', 'Registration failed. Please try again.');
            Session::setFlash('old', $data);
            $this->redirect('/register');
        }
    }

    /**
     * Show login form
     */
    public function login() {
        // If already logged in, redirect to appropriate dashboard
        if ($this->isLoggedIn()) {
            $this->redirectToDashboard();
        }

        $this->view('auth/login', [
            'title' => 'Login - ADI ARI Fresh'
        ]);
    }

    /**
     * Process login
     */
    public function loginPost() {
        // Validate CSRF token
        if (!$this->validateCsrf()) {
            Session::setFlash('error', 'Invalid request. Please try again.');
            $this->redirect('/login');
        }

        $email = $this->post('email');
        $password = $this->post('password');
        $remember = $this->post('remember');

        // Validate input
        $validation = new Validation();
        $validation->validate([
            'email' => $email,
            'password' => $password
        ], [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!$validation->passes()) {
            Session::setFlash('error', 'Please provide valid credentials.');
            Session::setFlash('old', ['email' => $email]);
            $this->redirect('/login');
        }

        // Check rate limit for login attempts (5 attempts per 15 minutes)
        $rateCheck = RateLimit::check('login', $email, 5, 900);
        if (!$rateCheck['allowed']) {
            Session::setFlash('error', "Too many login attempts. Please try again in {$rateCheck['reset_in']} seconds.");
            Logger::warning("Rate limit exceeded for login: {$email}");
            $this->redirect('/login');
        }

        // Authenticate user
        try {
            $user = $this->userModel->authenticate($email, $password);
        } catch (Exception $e) {
            Logger::error("Login DB error: " . $e->getMessage());
            Session::setFlash('error', 'Service temporarily unavailable. Please try again shortly.');
            Session::setFlash('old', ['email' => $email]);
            $this->redirect('/login');
            return;
        }

        if (!$user) {
            RateLimit::recordAttempt('login', $email);
            Session::setFlash('error', 'Invalid email or password.');
            Session::setFlash('old', ['email' => $email]);
            Logger::warning("Failed login attempt for: {$email}");
            $this->redirect('/login');
        }

        // Check if user is active
        if ($user['status'] !== 'active') {
            Session::setFlash('error', 'Your account is inactive. Please contact support.');
            Logger::warning("Inactive account login attempt: {$email}");
            $this->redirect('/login');
        }

        // Reset rate limit on successful login
        RateLimit::reset('login', $email);

        // Set session data
        Session::set('user_id', $user['id']);
        Session::set('user_email', $user['email']);
        Session::set('user_name', $user['first_name'] . ' ' . $user['last_name']);
        Session::set('user_role', $user['role']);
        Session::set('logged_in', true);

        // Regenerate session ID for security
        Session::regenerate();

        // Log activity
        Logger::info("User logged in: {$email}", ['user_id' => $user['id']]);
        Logger::activity($user['id'], 'user_login', [
            'email' => $email,
            'remember_me' => !empty($remember)
        ]);

        // Set success message
        Session::setFlash('success', 'Welcome back, ' . $user['first_name'] . '!');

        // Redirect to appropriate dashboard
        $this->redirectToDashboard();
    }

    /**
     * Logout user
     */
    public function logout() {
        $userId = Session::get('user_id');
        $userEmail = Session::get('user_email');
        
        // Log activity before destroying session
        if ($userId && $userEmail) {
            Logger::info("User logged out: {$userEmail}");
            Logger::activity($userId, 'user_logout', ['email' => $userEmail]);
        }

        // Clear all session data and regenerate
        Session::clear();
        Session::regenerate();

        // Set success message (after clearing, so new session picks it up)
        Session::setFlash('success', 'You have been logged out successfully.');

        // Redirect to home
        $this->redirect('/');
    }

    /**
     * Show forgot password form
     */
    public function forgotPassword() {
        // If already logged in, redirect to home
        if ($this->isLoggedIn()) {
            $this->redirect('/');
        }

        $this->view('auth/forgot-password', [
            'title' => 'Forgot Password - ADI ARI Fresh'
        ]);
    }

    /**
     * Process forgot password request
     */
    public function forgotPasswordPost() {
        // Validate CSRF token
        if (!$this->validateCsrf()) {
            Session::setFlash('error', 'Invalid request. Please try again.');
            $this->redirect('/forgot-password');
        }

        $email = $this->post('email');

        // Validate input
        $validation = new Validation();
        $validation->validate(['email' => $email], [
            'email' => 'required|email'
        ]);

        if (!$validation->passes()) {
            Session::setFlash('error', 'Please provide a valid email address.');
            $this->redirect('/forgot-password');
        }

        // Generate reset token
        $token = $this->userModel->generatePasswordResetToken($email);

        if ($token) {
            // In production, send email with reset link
            // For now, we'll just show a success message
            // Reset link format: /reset-password?token={$token}
            
            Logger::info("Password reset requested for: {$email}");
            
            Session::setFlash('success', 'Password reset instructions have been sent to your email.');
            Session::setFlash('reset_token', $token); // Remove this in production
        } else {
            // Don't reveal if email exists or not (security best practice)
            Session::setFlash('success', 'If that email exists, password reset instructions have been sent.');
        }

        $this->redirect('/forgot-password');
    }

    /**
     * Show reset password form
     */
    public function resetPassword() {
        $token = $this->get('token');

        if (!$token) {
            Session::setFlash('error', 'Invalid reset token.');
            $this->redirect('/login');
        }

        $this->view('auth/reset-password', [
            'title' => 'Reset Password - ADI ARI Fresh',
            'token' => $token
        ]);
    }

    /**
     * Process reset password
     */
    public function resetPasswordPost() {
        // Validate CSRF token
        if (!$this->validateCsrf()) {
            Session::setFlash('error', 'Invalid request. Please try again.');
            $this->redirect('/login');
        }

        $token = $this->post('token');
        $password = $this->post('password');
        $passwordConfirm = $this->post('password_confirm');

        // Validate input
        $validation = new Validation();
        $validation->validate([
            'password' => $password,
            'password_confirm' => $passwordConfirm
        ], [
            'password' => 'required|min:6|max:50',
            'password_confirm' => 'required|match:password'
        ]);

        if (!$validation->passes()) {
            Session::setFlash('error', 'Please fix the errors below.');
            Session::setFlash('errors', $validation->errors());
            $this->redirect('/reset-password?token=' . $token);
        }

        // Reset password
        $result = $this->userModel->resetPasswordWithToken($token, $password);

        if ($result) {
            Logger::info("Password reset successful for token: {$token}");
            Session::setFlash('success', 'Password reset successful! Please login with your new password.');
            $this->redirect('/login');
        } else {
            Session::setFlash('error', 'Invalid or expired reset token. Please request a new one.');
            $this->redirect('/forgot-password');
        }
    }

    /**
     * Redirect to appropriate dashboard based on role
     */
    private function redirectToDashboard() {
        $role = Session::get('user_role');

        switch ($role) {
            case 'admin':
                $this->redirect('/admin');
                return;
            case 'manager':
                $this->redirect('/manager');
                return;
            default:
                // Default to customer account for any other role or null
                $this->redirect('/account');
                return;
        }
    }
}
