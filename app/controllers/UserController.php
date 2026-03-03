<?php
/**
 * User Controller
 * Handles user account management and profile
 */

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/UserAddress.php';
require_once __DIR__ . '/../models/Wishlist.php';
require_once __DIR__ . '/../helpers/Validation.php';
require_once __DIR__ . '/../helpers/Security.php';
require_once __DIR__ . '/../helpers/Session.php';

class UserController extends Controller {
    private $userModel;
    private $addressModel;
    private $wishlistModel;

    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
        $this->addressModel = new UserAddress();
        $this->wishlistModel = new Wishlist();
    }

    /**
     * User dashboard/account page
     */
    public function account() {
        // Require authentication
        if (!$this->isLoggedIn()) {
            Session::setFlash('error', 'Please login to access your account.');
            $this->redirect('/login');
        }

        $userId = Session::get('user_id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            Session::setFlash('error', 'User not found.');
            $this->redirect('/logout');
        }

        // Remove password from view data
        unset($user['password']);

        // Load models for dashboard statistics
        require_once __DIR__ . '/../models/Order.php';
        require_once __DIR__ . '/../models/Wishlist.php';
        $orderModel = new Order();
        $wishlistModel = new Wishlist();

        // Get user statistics
        $userOrders = $orderModel->getUserOrders($userId, 100); // Fetch recent 100 to check status
        $totalOrders = count($userOrders);
        $pendingOrders = count(array_filter($userOrders, function($o) { return $o['status'] === 'pending'; }));
        $wishlistCount = count($wishlistModel->getUserWishlist($userId));

        $this->view('user/account', [
            'title' => 'My Account - ADI ARI Fresh',
            'user' => $user,
            'totalOrders' => $totalOrders,
            'pendingOrders' => $pendingOrders,
            'wishlistCount' => $wishlistCount
        ]);
    }

    /**
     * Show edit profile form
     */
    public function profile() {
        // Require authentication
        if (!$this->isLoggedIn()) {
            Session::setFlash('error', 'Please login to access your profile.');
            $this->redirect('/login');
        }

        $userId = Session::get('user_id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            Session::setFlash('error', 'User not found.');
            $this->redirect('/logout');
        }

        // Remove password from view data
        unset($user['password']);

        $this->view('user/profile', [
            'title' => 'Edit Profile - ADI ARI Fresh',
            'user' => $user
        ]);
    }

    /**
     * Update profile
     */
    public function profileUpdate() {
        // Require authentication
        if (!$this->isLoggedIn()) {
            $this->redirect('/login');
        }

        // Validate CSRF token
        if (!$this->validateCSRF()) {
            Session::setFlash('error', 'Invalid request. Please try again.');
            $this->redirect('/account/profile');
        }

        $userId = Session::get('user_id');

        // Get form data
        $data = [
            'first_name' => $this->post('first_name'),
            'last_name' => $this->post('last_name'),
            'phone' => $this->post('phone')
        ];

        // Validate input
        $validation = new Validation();
        $validation->validate($data, [
            'first_name' => 'required|min:2|max:100',
            'last_name' => 'required|min:2|max:100',
            'phone' => 'min:10|max:20'
        ]);

        if (!$validation->passes()) {
            Session::setFlash('error', 'Please fix the errors below.');
            Session::setFlash('errors', $validation->errors());
            Session::setFlash('old', $data);
            $this->redirect('/account/profile');
        }

        try {
            // Update user
            $this->userModel->update($userId, $data);

            // Update session name
            Session::set('user_name', $data['first_name'] . ' ' . $data['last_name']);

            Session::setFlash('success', 'Profile updated successfully!');
            $this->redirect('/account/profile');

        } catch (Exception $e) {
            Session::setFlash('error', 'Failed to update profile. Please try again.');
            Session::setFlash('old', $data);
            $this->redirect('/account/profile');
        }
    }

    /**
     * Show change password form
     */
    public function changePassword() {
        // Require authentication
        if (!$this->isLoggedIn()) {
            Session::setFlash('error', 'Please login to change your password.');
            $this->redirect('/login');
        }

        $this->view('user/change-password', [
            'title' => 'Change Password - ADI ARI Fresh'
        ]);
    }

    /**
     * Process password change
     */
    public function changePasswordPost() {
        // Require authentication
        if (!$this->isLoggedIn()) {
            $this->redirect('/login');
        }

        // Validate CSRF token
        if (!$this->validateCSRF()) {
            Session::setFlash('error', 'Invalid request. Please try again.');
            $this->redirect('/account/change-password');
        }

        $userId = Session::get('user_id');
        $currentPassword = $this->post('current_password');
        $newPassword = $this->post('new_password');
        $confirmPassword = $this->post('confirm_password');

        // Validate input
        $validation = new Validation();
        $validation->validate([
            'current_password' => $currentPassword,
            'new_password' => $newPassword,
            'confirm_password' => $confirmPassword
        ], [
            'current_password' => 'required',
            'new_password' => 'required|min:6|max:50',
            'confirm_password' => 'required|match:new_password'
        ]);

        if (!$validation->passes()) {
            Session::setFlash('error', 'Please fix the errors below.');
            Session::setFlash('errors', $validation->errors());
            $this->redirect('/account/change-password');
        }

        // Verify current password
        $user = $this->userModel->find($userId);
        
        if (!Security::verifyPassword($currentPassword, $user['password'])) {
            Session::setFlash('error', 'Current password is incorrect.');
            $this->redirect('/account/change-password');
        }

        try {
            // Update password
            $this->userModel->updatePassword($userId, $newPassword);

            Session::setFlash('success', 'Password changed successfully!');
            $this->redirect('/account');

        } catch (Exception $e) {
            Session::setFlash('error', 'Failed to change password. Please try again.');
            $this->redirect('/account/change-password');
        }
    }

    /**
     * Addresses list and add form
     */
    public function addresses() {
        if (!$this->isLoggedIn()) {
            Session::setFlash('error', 'Please login to manage addresses.');
            $this->redirect('/login');
        }
        $userId = Session::get('user_id');
        $addresses = $this->addressModel->getByUserId($userId);
        $this->view('user/addresses', [
            'title' => 'My Addresses - ADI ARI Fresh',
            'addresses' => $addresses
        ]);
    }

    /**
     * Add new address (POST)
     */
    public function addAddress() {
        if (!$this->isLoggedIn()) {
            $this->redirect('/login');
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Security::validateCsrfToken($this->post('csrf_token'))) {
            Session::setFlash('error', 'Invalid request.');
            $this->redirect('/account/addresses');
        }
        $userId = (int) Session::get('user_id');
        $data = [
            'user_id' => $userId,
            'address_type' => $this->post('address_type') ?: 'home',
            'first_name' => trim($this->post('first_name')),
            'last_name' => trim($this->post('last_name')),
            'phone' => trim($this->post('phone')),
            'address_line1' => trim($this->post('address_line1')),
            'address_line2' => trim($this->post('address_line2')),
            'city' => trim($this->post('city')),
            'state' => trim($this->post('state')),
            'postal_code' => trim($this->post('postal_code')),
            'country' => trim($this->post('country')) ?: 'Japan',
            'is_default' => $this->post('is_default') ? 1 : 0
        ];
        if (empty($data['first_name']) || empty($data['last_name']) || empty($data['phone']) || empty($data['address_line1']) || empty($data['city']) || empty($data['postal_code'])) {
            Session::setFlash('error', 'Please fill required fields.');
            $this->redirect('/account/addresses');
        }
        $this->addressModel->create($data);
        Session::setFlash('success', 'Address added.');
        $this->redirect('/account/addresses');
    }

    /**
     * Wishlist page
     */
    public function wishlist() {
        if (!$this->isLoggedIn()) {
            Session::setFlash('error', 'Please login to view wishlist.');
            $this->redirect('/login');
        }
        $userId = Session::get('user_id');
        $items = $this->wishlistModel->getUserWishlist($userId);
        $this->view('user/wishlist', [
            'title' => 'My Wishlist - ADI ARI Fresh',
            'items' => $items
        ]);
    }

    /**
     * Toggle Wishlist Item (AJAX)
     */
    public function toggleWishlist() {
        if (!$this->isAjax()) {
            $this->redirect('/');
        }
        
        if (!$this->isLoggedIn()) {
            $this->json(['success' => false, 'message' => 'Please login to use the wishlist.', 'redirect' => '/login'], 401);
        }

        $userId = Session::get('user_id');
        $productId = (int) $this->post('product_id');

        if (!$productId) {
            $this->json(['success' => false, 'message' => 'Invalid product.'], 400);
        }

        try {
            $isWishlisted = $this->wishlistModel->findBy(['user_id' => $userId, 'product_id' => $productId]);
            
            if ($isWishlisted) {
                $this->wishlistModel->remove($userId, $productId);
                $message = 'Removed from wishlist';
                $action = 'removed';
            } else {
                $this->wishlistModel->add($userId, $productId);
                $message = 'Added to wishlist';
                $action = 'added';
            }

            $this->json([
                'success' => true, 
                'message' => $message,
                'action' => $action
            ]);

        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => 'An error occurred.'], 500);
        }
    }
}
