<?php
/**
 * Admin Controller
 * Admin-only: users, settings, analytics, coupons, logs
 */

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Setting.php';
require_once __DIR__ . '/../models/Changelog.php';
require_once __DIR__ . '/../helpers/Session.php';
require_once __DIR__ . '/../helpers/Security.php';
require_once __DIR__ . '/../helpers/Validation.php';
require_once __DIR__ . '/../helpers/Logger.php';

class AdminController extends Controller {
    private $userModel;
    private $orderModel;
    private $productModel;

    public function __construct() {
        parent::__construct();
        if (!Session::isLoggedIn() || Session::get('user_role') !== 'admin') {
            Session::setFlash('error', 'Access denied.');
            $this->redirect('/login');
        }
        $this->userModel = new User();
        $this->orderModel = new Order();
        $this->productModel = new Product();
    }

    public function dashboard() {
        require_once __DIR__ . '/../models/Category.php';
        require_once __DIR__ . '/../models/Offer.php';
        
        $categoryModel = new Category();
        $offerModel = new Offer();

        $stats = [
            'users' => $this->userModel->count([]),
            'products' => $this->productModel->countActiveProducts(),
            'today_orders' => $this->orderModel->getTodayOrdersCount(),
            'today_revenue' => $this->orderModel->getTodayRevenue(),
        ];

        // Users (last 20, no passwords)
        $users = $this->userModel->findAll([], 20, 0);
        foreach ($users as &$u) unset($u['password']);

        // Products (last 15)
        $products = $this->productModel->getActiveProducts(15, 0, 'newest');

        // Low stock products
        $lowStock = $this->productModel->getLowStockProducts();

        // Recent orders (last 15)
        $recentOrders = $this->orderModel->getRecentOrders(15);

        // Categories
        $categories = $categoryModel->getCategoriesWithProductCount();

        // Offers
        $offerModel->updateExpiredOffers();
        $offers = $offerModel->getAllOffersForAdmin();

        // All products for offer dropdown
        $allProducts = $this->productModel->findAll([], 500, 0);

        // Recent activity logs
        $logs = [];
        $userMap = [];
        try {
            $db = Database::getConnection('analytics');
            $stmt = $db->prepare("SELECT * FROM user_activity ORDER BY created_at DESC LIMIT 20");
            $stmt->execute();
            $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Fetch user details for logs
            $userIds = array_unique(array_filter(array_column($logs, 'user_id')));
            if (!empty($userIds)) {
                $groceryDb = Database::getConnection('grocery');
                $placeholders = implode(',', array_fill(0, count($userIds), '?'));
                $userStmt = $groceryDb->prepare("SELECT id, first_name, last_name, email, phone FROM users WHERE id IN ({$placeholders})");
                $userStmt->execute(array_values($userIds));
                $fetchedUsers = $userStmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($fetchedUsers as $fu) {
                    $userMap[$fu['id']] = $fu;
                }
            }
        } catch (Exception $e) {
            // Analytics DB may not be available
        }

        // Tax settings
        $taxSettings = Setting::getAll();

        // Changelog
        $changelogModel = new Changelog();
        $changelog = $changelogModel->getAll();

        $this->view('admin/dashboard', [
            'title' => 'Admin Dashboard',
            'stats' => $stats,
            'users' => $users,
            'products' => $products,
            'lowStock' => $lowStock,
            'recentOrders' => $recentOrders,
            'categories' => $categories,
            'offers' => $offers,
            'allProducts' => $allProducts,
            'logs' => $logs,
            'userMap' => $userMap,
            'taxSettings' => $taxSettings,
            'changelog' => $changelog,
        ]);
    }

    public function users() {
        $users = $this->userModel->findAll([], 100, 0);
        foreach ($users as &$u) unset($u['password']);
        $this->view('admin/users', ['title' => 'User Management', 'users' => $users]);
    }

    public function createUser() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Security::validateCsrfToken($this->post('csrf_token'))) {
            Session::setFlash('error', 'Invalid request.');
            $this->redirect('/admin/users');
        }
        $email = trim($this->post('email'));
        $password = $this->post('password');
        $first = trim($this->post('first_name'));
        $last = trim($this->post('last_name'));
        $role = $this->post('role') ?: 'customer';
        if (!$email || !$password || !$first || !$last) {
            Session::setFlash('error', 'All fields required.');
            $this->redirect('/admin/users');
        }
        if ($this->userModel->findByEmail($email)) {
            Session::setFlash('error', 'Email already exists.');
            $this->redirect('/admin/users');
        }
        $this->userModel->createUser(['email' => $email, 'password' => $password, 'first_name' => $first, 'last_name' => $last, 'role' => $role, 'status' => 'active']);
        Session::setFlash('success', 'User created.');
        $this->redirect('/admin/users');
    }

    public function updateUser($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Security::validateCsrfToken($this->post('csrf_token'))) {
            Session::setFlash('error', 'Invalid request.');
            $this->redirect('/admin/users');
        }
        $user = $this->userModel->find($id);
        if (!$user) {
            Session::setFlash('error', 'User not found.');
            $this->redirect('/admin/users');
        }
        $data = [
            'first_name' => trim($this->post('first_name')),
            'last_name' => trim($this->post('last_name')),
            'status' => $this->post('status') ?: 'active',
        ];
        if ($this->post('phone') !== null) $data['phone'] = trim($this->post('phone'));
        $this->userModel->update($id, $data);
        Session::setFlash('success', 'User updated.');
        $this->redirect('/admin/users');
    }

    public function deleteUser($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Security::validateCsrfToken($this->post('csrf_token'))) {
            Session::setFlash('error', 'Invalid request.');
            $this->redirect('/admin/users');
        }
        if ((int)$id === (int)Session::get('user_id')) {
            Session::setFlash('error', 'Cannot delete yourself.');
            $this->redirect('/admin/users');
        }
        $this->userModel->delete($id);
        Session::setFlash('success', 'User deleted.');
        $this->redirect('/admin/users');
    }

    public function updateUserRole($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Security::validateCsrfToken($this->post('csrf_token'))) {
            Session::setFlash('error', 'Invalid request.');
            $this->redirect('/admin/users');
        }
        $role = $this->post('role');
        if (!in_array($role, ['customer', 'manager', 'admin'])) {
            Session::setFlash('error', 'Invalid role.');
            $this->redirect('/admin/users');
        }
        $this->userModel->update($id, ['role' => $role]);
        Session::setFlash('success', 'Role updated.');
        $this->redirect('/admin/users');
    }

    public function settings() {
        $this->view('admin/settings', ['title' => 'System Settings']);
    }

    public function updateSettings() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Security::validateCsrfToken($this->post('csrf_token'))) {
            Session::setFlash('error', 'Invalid request.');
            $this->redirect('/admin/settings');
        }
        Session::setFlash('success', 'Settings saved.');
        $this->redirect('/admin/settings');
    }

    public function analytics() {
        $this->view('admin/analytics', ['title' => 'Analytics']);
    }

    public function reports() {
        $this->view('admin/reports', ['title' => 'Reports']);
    }

    public function coupons() {
        $this->view('admin/coupons', ['title' => 'Coupons']);
    }

    public function createCoupon() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Security::validateCsrfToken($this->post('csrf_token'))) {
            Session::setFlash('error', 'Invalid request.');
            $this->redirect('/admin/coupons');
        }
        Session::setFlash('success', 'Coupon created.');
        $this->redirect('/admin/coupons');
    }

    public function updateCoupon($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Security::validateCsrfToken($this->post('csrf_token'))) {
            Session::setFlash('error', 'Invalid request.');
            $this->redirect('/admin/coupons');
        }
        Session::setFlash('success', 'Coupon updated.');
        $this->redirect('/admin/coupons');
    }

    public function offers() {
        require_once __DIR__ . '/../models/Offer.php';
        $offerModel = new Offer();
        
        // Update expired offers
        $offerModel->updateExpiredOffers();
        
        // Get all offers
        $offers = $offerModel->getAllOffersForAdmin();
        
        // Get all products for the dropdown
        $products = $this->productModel->findAll([], 500, 0);
        
        $this->view('admin/offers', [
            'title' => 'Weekly Deals Management',
            'offers' => $offers,
            'products' => $products
        ]);
    }

    public function createOffer() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Security::validateCsrfToken($this->post('csrf_token'))) {
            Session::setFlash('error', 'Invalid request.');
            $this->redirect('/admin/offers');
            return;
        }

        require_once __DIR__ . '/../models/Offer.php';
        $offerModel = new Offer();

        $data = [
            'product_id' => $this->post('product_id'),
            'discount_type' => $this->post('discount_type'),
            'discount_value' => $this->post('discount_value'),
            'start_date' => $this->post('start_date'),
            'end_date' => $this->post('end_date'),
            'status' => $this->post('status') ?? 'active'
        ];

        try {
            $offerModel->createOffer($data);
            Session::setFlash('success', 'Offer created successfully.');
        } catch (Exception $e) {
            Session::setFlash('error', 'Failed to create offer: ' . $e->getMessage());
        }

        $this->redirect('/admin/offers');
    }

    public function updateOffer($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Security::validateCsrfToken($this->post('csrf_token'))) {
            Session::setFlash('error', 'Invalid request.');
            $this->redirect('/admin/offers');
            return;
        }

        require_once __DIR__ . '/../models/Offer.php';
        $offerModel = new Offer();

        $data = [
            'product_id' => $this->post('product_id'),
            'discount_type' => $this->post('discount_type'),
            'discount_value' => $this->post('discount_value'),
            'start_date' => $this->post('start_date'),
            'end_date' => $this->post('end_date'),
            'status' => $this->post('status')
        ];

        try {
            $offerModel->updateOffer($id, $data);
            Session::setFlash('success', 'Offer updated successfully.');
        } catch (Exception $e) {
            Session::setFlash('error', 'Failed to update offer: ' . $e->getMessage());
        }

        $this->redirect('/admin/offers');
    }

    public function deleteOffer($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Security::validateCsrfToken($this->post('csrf_token'))) {
            Session::setFlash('error', 'Invalid request.');
            $this->redirect('/admin/offers');
            return;
        }

        require_once __DIR__ . '/../models/Offer.php';
        $offerModel = new Offer();

        try {
            $offerModel->delete($id);
            Session::setFlash('success', 'Offer deleted successfully.');
        } catch (Exception $e) {
            Session::setFlash('error', 'Failed to delete offer: ' . $e->getMessage());
        }

        $this->redirect('/admin/offers');
    }

    public function logs() {
        try {
            // Get filter parameters
            $activityType = isset($_GET['activity_type']) ? trim($_GET['activity_type']) : '';
            $userId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;
            $date = isset($_GET['date']) ? $_GET['date'] : '';
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = 50;
            $offset = ($page - 1) * $limit;

            // Build query
            $db = Database::getConnection('analytics');
            
            $sql = "SELECT * FROM user_activity WHERE 1=1";
            $params = [];

            if ($activityType) {
                $sql .= " AND activity_type = ?";
                $params[] = $activityType;
            }

            if ($userId) {
                $sql .= " AND user_id = ?";
                $params[] = $userId;
            }

            if ($date) {
                $sql .= " AND DATE(created_at) = ?";
                $params[] = $date;
            }

            // Get total count for pagination
            $countSql = "SELECT COUNT(*) as total FROM (" . $sql . ") as subquery";
            $countStmt = $db->prepare($countSql);
            $countStmt->execute($params);
            $totalActivities = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Get paginated results
            $sql .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;

            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Fetch user details (name, email) from grocery DB for all user IDs in logs
            $userMap = [];
            $userIds = array_unique(array_filter(array_column($logs, 'user_id')));
            if (!empty($userIds)) {
                $groceryDb = Database::getConnection('grocery');
                $placeholders = implode(',', array_fill(0, count($userIds), '?'));
                $userStmt = $groceryDb->prepare("SELECT id, first_name, last_name, email, phone FROM users WHERE id IN ({$placeholders})");
                $userStmt->execute(array_values($userIds));
                $users = $userStmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($users as $u) {
                    $userMap[$u['id']] = $u;
                }
            }

            // Get stats
            $statsStmt = $db->query("
                SELECT 
                    COUNT(*) as total,
                    COUNT(DISTINCT user_id) as unique_users,
                    COUNT(DISTINCT session_id) as active_sessions,
                    SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) as today_count
                FROM user_activity
            ");
            $stats = $statsStmt->fetch(PDO::FETCH_ASSOC);

            $totalPages = ceil($totalActivities / $limit);

            $this->view('admin/logs', [
                'title' => 'Activity Logs',
                'logs' => $logs,
                'userMap' => $userMap,
                'totalActivities' => $stats['total'],
                'todayActivities' => $stats['today_count'],
                'uniqueUsers' => $stats['unique_users'],
                'activeSessions' => $stats['active_sessions'],
                'activityType' => $activityType,
                'userId' => $userId,
                'date' => $date,
                'currentPage' => $page,
                'totalPages' => $totalPages
            ]);

        } catch (Exception $e) {
            Logger::error("Failed to load activity logs: " . $e->getMessage());
            $this->view('admin/logs', [
                'title' => 'Activity Logs',
                'logs' => [],
                'totalActivities' => 0,
                'todayActivities' => 0,
                'uniqueUsers' => 0,
                'activeSessions' => 0,
                'error' => 'Failed to load logs. Please check database connection.'
            ]);
        }
    }

    /**
     * Update global tax settings
     */
    public function updateTax() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin');
        }
        if (!Security::validateCsrfToken($this->post('csrf_token'))) {
            Session::setFlash('error', 'Invalid request.');
            $this->redirect('/admin');
        }

        $taxRate = (float) $this->post('global_tax_rate');
        $taxEnabled = $this->post('tax_enabled') ? '1' : '0';
        $taxLabel = trim($this->post('tax_label')) ?: 'Consumption Tax';
        $taxIncluded = $this->post('tax_included_in_price') ? '1' : '0';

        if ($taxRate < 0 || $taxRate > 100) {
            Session::setFlash('error', 'Tax rate must be between 0 and 100.');
            $this->redirect('/admin');
        }

        Setting::set('global_tax_rate', $taxRate);
        Setting::set('tax_enabled', $taxEnabled);
        Setting::set('tax_label', $taxLabel);
        Setting::set('tax_included_in_price', $taxIncluded);

        Session::setFlash('success', 'Tax settings updated successfully!');
        $this->redirect('/admin');
    }

    /**
     * Update per-product tax rate
     */
    public function updateProductTax($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin');
        }
        if (!Security::validateCsrfToken($this->post('csrf_token'))) {
            Session::setFlash('error', 'Invalid request.');
            $this->redirect('/admin');
        }

        $product = $this->productModel->find($id);
        if (!$product) {
            Session::setFlash('error', 'Product not found.');
            $this->redirect('/admin');
        }

        $taxRate = $this->post('tax_rate');
        // Allow null/empty to mean "use global rate"
        if ($taxRate === '' || $taxRate === null) {
            $taxRate = null;
        } else {
            $taxRate = (float) $taxRate;
            if ($taxRate < 0 || $taxRate > 100) {
                Session::setFlash('error', 'Tax rate must be between 0 and 100.');
                $this->redirect('/admin');
            }
        }

        Database::query(
            "UPDATE products SET tax_rate = ? WHERE id = ?",
            [$taxRate, $id],
            'grocery'
        );

        Session::setFlash('success', 'Tax rate updated for ' . htmlspecialchars($product['name']));
        $this->redirect('/admin');
    }

    /**
     * Add a changelog entry
     */
    public function addChangelog() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin');
        }
        if (!Security::validateCsrfToken($this->post('csrf_token'))) {
            Session::setFlash('error', 'Invalid request.');
            $this->redirect('/admin');
        }

        $version = trim($this->post('version'));
        $title = trim($this->post('title'));
        $description = trim($this->post('description'));
        $changeType = $this->post('change_type') ?: 'feature';

        if (!$title) {
            Session::setFlash('error', 'Title is required for changelog entry.');
            $this->redirect('/admin');
        }

        $changelogModel = new Changelog();
        $changelogModel->addEntry([
            'version' => $version,
            'title' => $title,
            'description' => $description,
            'change_type' => $changeType,
            'changed_by' => Session::get('user_name') ?? 'Admin',
        ]);

        Session::setFlash('success', 'Changelog entry added!');
        $this->redirect('/admin');
    }
}
