<?php
/**
 * Shop Controller
 * Integrates the POS/Shopping system into the admin dashboard MVC framework
 * Handles: Shop login, Dashboard, Inventory, Sales, POS, Customers, Coupons, Import
 */

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/ShopUser.php';
require_once __DIR__ . '/../models/ShopInventory.php';
require_once __DIR__ . '/../models/ShopSales.php';
require_once __DIR__ . '/../models/ShopCustomer.php';
require_once __DIR__ . '/../models/ShopCoupon.php';
require_once __DIR__ . '/../helpers/CurrencyHelper.php';

class ShopController extends Controller {
    
    private $shopUserModel;
    private $inventoryModel;
    private $salesModel;
    private $customerModel;
    private $couponModel;
    
    public function __construct() {
        parent::__construct();
        $this->shopUserModel = new ShopUser();
        $this->inventoryModel = new ShopInventory();
        $this->salesModel = new ShopSales();
        $this->customerModel = new ShopCustomer();
        $this->couponModel = new ShopCoupon();
    }
    
    /**
     * Check if shop user is logged in
     */
    private function requireShopAuth() {
        if (!isset($_SESSION['shop_user_id'])) {
            $this->redirect('/shop/login');
            return false;
        }
        return true;
    }
    
    /**
     * Check shop role permission
     */
    private function requireShopPermission($module, $action) {
        if (!$this->requireShopAuth()) return false;
        if (!$this->shopUserModel->hasPermission($_SESSION['shop_user_id'], $module, $action)) {
            $_SESSION['shop_error'] = 'You do not have permission to perform this action';
            $this->redirect('/shop/dashboard');
            return false;
        }
        return true;
    }
    
    // ==========================================
    // AUTHENTICATION
    // ==========================================
    
    public function login() {
        if (isset($_SESSION['shop_user_id'])) {
            $this->redirect('/shop/dashboard');
            return;
        }
        $this->renderShopView('auth/login');
    }
    
    public function loginPost() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/shop/login');
            return;
        }
        
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($username) || empty($password)) {
            $_SESSION['shop_error'] = 'Username and password are required';
            $this->redirect('/shop/login');
            return;
        }
        
        $user = $this->shopUserModel->authenticate($username, $password);
        
        if ($user) {
            $_SESSION['shop_user_id'] = $user['id'];
            $_SESSION['shop_username'] = $user['username'];
            $_SESSION['shop_full_name'] = $user['full_name'];
            $_SESSION['shop_role'] = $user['role'];
            
            $token = $this->shopUserModel->createSession($user['id']);
            $_SESSION['shop_session_token'] = $token;
            
            $this->shopUserModel->logActivity($user['id'], 'login', 'auth', 'User logged in');
            
            if ($user['role'] === 'staff') {
                $this->redirect('/shop/pos');
            } else {
                $this->redirect('/shop/dashboard');
            }
        } else {
            $_SESSION['shop_error'] = 'Invalid username or password';
            $this->redirect('/shop/login');
        }
    }
    
    public function logout() {
        if (isset($_SESSION['shop_session_token'])) {
            $this->shopUserModel->destroySession($_SESSION['shop_session_token']);
        }
        if (isset($_SESSION['shop_user_id'])) {
            $this->shopUserModel->logActivity($_SESSION['shop_user_id'], 'logout', 'auth', 'User logged out');
        }
        
        // Clear only shop session data
        unset($_SESSION['shop_user_id'], $_SESSION['shop_username'], 
              $_SESSION['shop_full_name'], $_SESSION['shop_role'],
              $_SESSION['shop_session_token']);
        
        $this->redirect('/shop/login');
    }
    
    // ==========================================
    // DASHBOARD
    // ==========================================
    
    public function dashboard() {
        if (!$this->requireShopAuth()) return;
        
        $role = $_SESSION['shop_role'] ?? 'staff';
        if ($role === 'staff') {
            $this->redirect('/shop/pos');
            return;
        }
        
        $stats = [
            'total_items' => $this->inventoryModel->getTotalCount(),
            'total_value' => $this->inventoryModel->getTotalValue(),
            'today_sales' => $this->salesModel->getTodaySales(),
            'low_stock_count' => count($this->inventoryModel->getLowStockItems())
        ];
        
        $recentSales = $this->salesModel->getAllSales(5);
        $lowStockItems = $this->inventoryModel->getLowStockItems();
        
        $this->renderShopView('dashboard/index', [
            'stats' => $stats,
            'recentSales' => $recentSales,
            'lowStockItems' => $lowStockItems
        ]);
    }
    
    // ==========================================
    // INVENTORY
    // ==========================================
    
    public function inventory() {
        if (!$this->requireShopAuth()) return;
        $items = $this->inventoryModel->getAllItems();
        $this->renderShopView('inventory/index', ['items' => $items]);
    }
    
    public function inventoryAdd() {
        if (!$this->requireShopAuth()) return;
        $this->renderShopView('inventory/add');
    }
    
    public function inventoryStore() {
        if (!$this->requireShopAuth()) return;
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/shop/inventory');
            return;
        }
        
        $barcode = !empty($_POST['barcode']) ? $this->sanitize($_POST['barcode']) : null;
        if (empty($barcode)) {
            $barcode = '2' . substr(time(), -9) . str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
        }
        
        $data = [
            'product_name' => $this->sanitize($_POST['product_name']),
            'category' => $this->sanitize($_POST['category']),
            'barcode' => $barcode,
            'quantity' => (int)$_POST['quantity'],
            'unit_price' => (float)$_POST['unit_price'],
            'reorder_level' => (int)$_POST['reorder_level'],
            'supplier_name' => $this->sanitize($_POST['supplier_name'] ?? ''),
            'supplier_contact' => $this->sanitize($_POST['supplier_contact'] ?? '')
        ];
        
        if ($this->inventoryModel->addItem($data)) {
            $_SESSION['shop_success'] = 'Item added successfully!';
        } else {
            $_SESSION['shop_error'] = 'Failed to add item.';
        }
        $this->redirect('/shop/inventory');
    }
    
    public function inventoryEdit($id) {
        if (!$this->requireShopAuth()) return;
        $item = $this->inventoryModel->getItemById((int)$id);
        if (!$item) {
            $_SESSION['shop_error'] = 'Item not found.';
            $this->redirect('/shop/inventory');
            return;
        }
        $this->renderShopView('inventory/edit', ['item' => $item]);
    }
    
    public function inventoryUpdate($id) {
        if (!$this->requireShopAuth()) return;
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/shop/inventory');
            return;
        }
        
        $data = [
            'product_name' => $this->sanitize($_POST['product_name']),
            'category' => $this->sanitize($_POST['category']),
            'quantity' => (int)$_POST['quantity'],
            'unit_price' => (float)$_POST['unit_price'],
            'reorder_level' => (int)$_POST['reorder_level'],
            'supplier_name' => $this->sanitize($_POST['supplier_name'] ?? ''),
            'supplier_contact' => $this->sanitize($_POST['supplier_contact'] ?? ''),
            'barcode' => $this->sanitize($_POST['barcode'] ?? '')
        ];
        
        if ($this->inventoryModel->updateItem((int)$id, $data)) {
            $_SESSION['shop_success'] = 'Item updated successfully!';
        } else {
            $_SESSION['shop_error'] = 'Failed to update item.';
        }
        $this->redirect('/shop/inventory');
    }
    
    public function inventoryDelete($id) {
        if (!$this->requireShopAuth()) return;
        if (!$this->requireShopPermission('inventory', 'delete')) return;
        
        if ($this->inventoryModel->deleteItem((int)$id)) {
            $_SESSION['shop_success'] = 'Item deleted successfully!';
        } else {
            $_SESSION['shop_error'] = 'Failed to delete item.';
        }
        $this->redirect('/shop/inventory');
    }
    
    // ==========================================
    // SALES
    // ==========================================
    
    public function sales() {
        if (!$this->requireShopAuth()) return;
        $sales = $this->salesModel->getAllSales();
        $this->renderShopView('sales/index', ['sales' => $sales]);
    }
    
    public function salesAdd() {
        if (!$this->requireShopAuth()) return;
        $items = $this->inventoryModel->getAllItems();
        $this->renderShopView('sales/add', ['items' => $items]);
    }
    
    public function salesStore() {
        if (!$this->requireShopAuth()) return;
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/shop/sales');
            return;
        }
        
        $item = $this->inventoryModel->getItemById($_POST['inventory_id']);
        if (!$item) {
            $_SESSION['shop_error'] = 'Item not found.';
            $this->redirect('/shop/sales/add');
            return;
        }
        
        $quantitySold = (int)$_POST['quantity_sold'];
        if ($item['quantity'] < $quantitySold) {
            $_SESSION['shop_error'] = "Insufficient stock. Available: {$item['quantity']}";
            $this->redirect('/shop/sales/add');
            return;
        }
        
        $totalAmount = $item['unit_price'] * $quantitySold;
        $data = [
            'inventory_id' => (int)$_POST['inventory_id'],
            'quantity_sold' => $quantitySold,
            'total_amount' => $totalAmount,
            'customer_name' => $this->sanitize($_POST['customer_name'] ?? 'Walk-in'),
            'sale_date' => date('Y-m-d H:i:s')
        ];
        
        if ($this->salesModel->recordSale($data)) {
            $_SESSION['shop_success'] = 'Sale recorded! Total: ¥' . number_format($totalAmount, 0);
        } else {
            $_SESSION['shop_error'] = 'Failed to record sale.';
        }
        $this->redirect('/shop/sales');
    }
    
    // ==========================================
    // POS (Point of Sale)
    // ==========================================
    
    public function pos() {
        if (!$this->requireShopAuth()) return;
        $customers = $this->customerModel->getAllCustomers();
        $this->renderShopView('pos/index', ['customers' => $customers]);
    }
    
    public function posSearchProduct() {
        if (!$this->requireShopAuth()) return;
        header('Content-Type: application/json');
        
        $term = isset($_POST['term']) ? trim($_POST['term']) : (isset($_POST['barcode']) ? trim($_POST['barcode']) : '');
        if (empty($term)) {
            echo json_encode(['success' => false, 'message' => 'Search term required']);
            exit;
        }
        
        try {
            $db = Database::getConnection('grocery');
            
            // Try barcode first - FIXED: using products table
            $query = "SELECT id, name as product_name, barcode, sku, price as unit_price, stock_quantity as quantity, status 
                      FROM products 
                      WHERE (barcode = :barcode OR sku = :sku) 
                      AND status = 'active' 
                      AND deleted_at IS NULL 
                      LIMIT 1";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':barcode', $term);
            $stmt->bindParam(':sku', $term);
            $stmt->execute();
            $product = $stmt->fetch();
            
            if ($product) {
                if ($product['quantity'] > 0) {
                    echo json_encode(['success' => true, 'product' => $product]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Product out of stock']);
                }
                exit;
            }
            
            // Search by name - FIXED: using products table
            $query = "SELECT id, name as product_name, barcode, sku, price as unit_price, stock_quantity as quantity, status 
                      FROM products 
                      WHERE name LIKE :name 
                      AND status = 'active' 
                      AND deleted_at IS NULL 
                      LIMIT 10";
            $stmt = $db->prepare($query);
            $nameTerm = "%$term%";
            $stmt->bindParam(':name', $nameTerm);
            $stmt->execute();
            $products = $stmt->fetchAll();
            
            if (count($products) === 1) {
                $product = $products[0];
                if ($product['quantity'] > 0) {
                    echo json_encode(['success' => true, 'product' => $product]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Product out of stock']);
                }
            } elseif (count($products) > 1) {
                echo json_encode(['success' => true, 'multiple' => true, 'products' => $products]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Product not found']);
            }
        } catch (Exception $e) {
            error_log("POS Search Error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        exit;
    }
    
    public function posValidateCoupon() {
        if (!$this->requireShopAuth()) return;
        header('Content-Type: application/json');
        
        $code = trim($_POST['code'] ?? '');
        $total = floatval($_POST['total'] ?? 0);
        
        $result = $this->couponModel->validateCoupon($code, $total);
        echo json_encode($result);
        exit;
    }
    
    public function posAddCustomer() {
        if (!$this->requireShopAuth()) return;
        header('Content-Type: application/json');
        
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['name']) || empty($data['name']) || !isset($data['phone']) || empty($data['phone'])) {
            echo json_encode(['success' => false, 'message' => 'Name and Phone are required']);
            exit;
        }
        
        $customerData = [
            'customer_name' => trim($data['name']),
            'phone' => trim($data['phone']),
            'email' => isset($data['email']) ? trim($data['email']) : '',
            'address' => isset($data['address']) ? trim($data['address']) : ''
        ];
        
        $customerId = $this->customerModel->createCustomer($customerData);
        if ($customerId) {
            $customer = $this->customerModel->getCustomerById($customerId);
            echo json_encode(['success' => true, 'message' => 'Customer created', 'customer' => $customer]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create customer']);
        }
        exit;
    }
    
    public function posGetCustomer() {
        if (!$this->requireShopAuth()) return;
        header('Content-Type: application/json');
        
        $customerId = (int)($_GET['id'] ?? 0);
        $customer = $this->customerModel->getCustomerById($customerId);
        echo json_encode($customer ? ['success' => true, 'customer' => $customer] : ['success' => false]);
        exit;
    }
    
    public function posCheckout() {
        if (!$this->requireShopAuth()) return;
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }
        
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        if (!$data || !isset($data['items']) || empty($data['items'])) {
            echo json_encode(['success' => false, 'message' => 'Cart is empty']);
            exit;
        }
        
        try {
            $db = Database::getConnection('grocery');
            $db->beginTransaction();
            
            $verifiedItems = [];
            $recalculatedSubtotal = 0;
            
            foreach ($data['items'] as $item) {
                $dbItem = $this->inventoryModel->getItemById($item['id']);
                if (!$dbItem) throw new Exception("Item '{$item['product_name']}' not found.");
                if ($dbItem['quantity'] < $item['quantity']) {
                    throw new Exception("Insufficient stock for '{$dbItem['product_name']}'.");
                }
                
                $unitPrice = floatval($dbItem['unit_price']);
                $lineTotal = $unitPrice * $item['quantity'];
                $recalculatedSubtotal += $lineTotal;
                
                $verifiedItems[] = [
                    'id' => $dbItem['id'],
                    'product_name' => $dbItem['product_name'],
                    'barcode' => $dbItem['barcode'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $unitPrice,
                    'line_total' => $lineTotal
                ];
            }
            
            $vatRate = 10;
            $tax = round($recalculatedSubtotal * ($vatRate / 100));
            $discount = floatval($data['discount'] ?? 0);
            $couponId = !empty($data['couponId']) ? (int)$data['couponId'] : null;
            
            if ($discount > ($recalculatedSubtotal + $tax)) {
                $discount = $recalculatedSubtotal + $tax;
            }
            
            $total = $recalculatedSubtotal + $tax - $discount;
            $amountPaid = floatval($data['amountPaid']);
            
            if ($amountPaid < $total) {
                throw new Exception("Amount paid (¥{$amountPaid}) is less than Total (¥{$total})");
            }
            $change = $amountPaid - $total;
            
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $customerId = !empty($data['customerId']) ? (int)$data['customerId'] : null;
            $staffId = !empty($_SESSION['shop_user_id']) ? (int)$_SESSION['shop_user_id'] : null;
            
            $customerName = $data['customerName'] ?? null;
            $customerPhone = $data['customerPhone'] ?? null;
            if ($customerId) {
                $customer = $this->customerModel->getCustomerById($customerId);
                if ($customer) {
                    $customerName = $customer['customer_name'] ?? $customerName;
                    $customerPhone = $customer['phone'] ?? $customerPhone;
                }
            }
            
            $invoiceQuery = "INSERT INTO invoices 
                (invoice_number, customer_name, customer_phone, customer_id, coupon_id, staff_id,
                 subtotal, tax, discount, total_amount, payment_method, amount_paid, change_amount) 
                VALUES (:invoice_number, :customer_name, :customer_phone, :customer_id, :coupon_id, :staff_id,
                        :subtotal, :tax, :discount, :total, :payment_method, :amount_paid, :change)";
            
            $stmt = $db->prepare($invoiceQuery);
            $stmt->execute([
                'invoice_number' => $invoiceNumber,
                'customer_name' => $customerName,
                'customer_phone' => $customerPhone,
                'customer_id' => $customerId,
                'coupon_id' => $couponId,
                'staff_id' => $staffId,
                'subtotal' => $recalculatedSubtotal,
                'tax' => $tax,
                'discount' => $discount,
                'total' => $total,
                'payment_method' => $data['paymentMethod'] ?? 'Cash',
                'amount_paid' => $amountPaid,
                'change' => $change
            ]);
            
            $invoiceId = $db->lastInsertId();
            
            $itemQuery = "INSERT INTO invoice_items 
                (invoice_id, inventory_id, product_name, barcode, quantity, unit_price, line_total) 
                VALUES (:invoice_id, :inventory_id, :product_name, :barcode, :quantity, :unit_price, :line_total)";
            $itemStmt = $db->prepare($itemQuery);
            
            foreach ($verifiedItems as $item) {
                $itemStmt->execute([
                    'invoice_id' => $invoiceId,
                    'inventory_id' => $item['id'],
                    'product_name' => $item['product_name'],
                    'barcode' => $item['barcode'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'line_total' => $item['line_total']
                ]);
                
                if (!$this->inventoryModel->decrementStock($item['id'], $item['quantity'])) {
                    throw new Exception("Stock update failed for '{$item['product_name']}'.");
                }
            }
            
            if ($couponId) $this->couponModel->applyCoupon($couponId);
            if ($customerId) $this->customerModel->updatePurchases($customerId, $total);
            
            $db->commit();
            
            echo json_encode([
                'success' => true,
                'message' => 'Sale completed successfully',
                'invoice_number' => $invoiceNumber,
                'invoice_id' => $invoiceId
            ]);
        } catch (Exception $e) {
            $db = Database::getConnection('grocery');
            if ($db->inTransaction()) $db->rollBack();
            echo json_encode(['success' => false, 'message' => 'Transaction Failed: ' . $e->getMessage()]);
        }
        exit;
    }
    
    public function posInvoice($id) {
        if (!$this->requireShopAuth()) return;
        
        try {
            $db = Database::getConnection('grocery');
            
            $query = "SELECT * FROM invoices WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $invoice = $stmt->fetch();
            
            if (!$invoice) {
                $_SESSION['shop_error'] = 'Invoice not found';
                $this->redirect('/shop/pos');
                return;
            }
            
            $itemsQuery = "SELECT * FROM invoice_items WHERE invoice_id = :invoice_id";
            $itemsStmt = $db->prepare($itemsQuery);
            $itemsStmt->bindParam(':invoice_id', $id);
            $itemsStmt->execute();
            $items = $itemsStmt->fetchAll();
            
            $this->renderShopView('pos/invoice', ['invoice' => $invoice, 'items' => $items]);
        } catch (Exception $e) {
            $_SESSION['shop_error'] = 'Error loading invoice';
            $this->redirect('/shop/pos');
        }
    }
    
    public function posInvoices() {
        if (!$this->requireShopAuth()) return;
        
        try {
            $db = Database::getConnection('grocery');
            $query = "SELECT * FROM invoices ORDER BY created_at DESC LIMIT 50";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $invoices = $stmt->fetchAll();
            $this->renderShopView('pos/invoices', ['invoices' => $invoices]);
        } catch (Exception $e) {
            $this->renderShopView('pos/invoices', ['invoices' => []]);
        }
    }
    
    // ==========================================
    // CUSTOMERS
    // ==========================================
    
    public function customers() {
        if (!$this->requireShopAuth()) return;
        $customers = $this->customerModel->getAllCustomers();
        $this->renderShopView('customers/index', ['customers' => $customers]);
    }
    
    public function customerAdd() {
        if (!$this->requireShopAuth()) return;
        $this->renderShopView('customers/add');
    }
    
    public function customerStore() {
        if (!$this->requireShopAuth()) return;
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/shop/customers');
            return;
        }
        
        $data = [
            'customer_name' => trim($_POST['customer_name'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'address' => trim($_POST['address'] ?? '')
        ];
        
        if ($this->customerModel->createCustomer($data)) {
            $_SESSION['shop_success'] = 'Customer added!';
        } else {
            $_SESSION['shop_error'] = 'Failed to add customer.';
        }
        $this->redirect('/shop/customers');
    }
    
    public function customerEdit($id) {
        if (!$this->requireShopAuth()) return;
        $customer = $this->customerModel->getCustomerById((int)$id);
        if (!$customer) {
            $_SESSION['shop_error'] = 'Customer not found.';
            $this->redirect('/shop/customers');
            return;
        }
        $this->renderShopView('customers/edit', ['customer' => $customer]);
    }
    
    public function customerUpdate($id) {
        if (!$this->requireShopAuth()) return;
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/shop/customers');
            return;
        }
        
        $data = [
            'customer_name' => trim($_POST['customer_name'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'address' => trim($_POST['address'] ?? '')
        ];
        
        if ($this->customerModel->updateCustomer((int)$id, $data)) {
            $_SESSION['shop_success'] = 'Customer updated!';
        } else {
            $_SESSION['shop_error'] = 'Failed to update customer.';
        }
        $this->redirect('/shop/customers');
    }
    
    // ==========================================
    // COUPONS
    // ==========================================
    
    public function coupons() {
        if (!$this->requireShopAuth()) return;
        $coupons = $this->couponModel->getAllCoupons();
        $this->renderShopView('coupons/index', ['coupons' => $coupons]);
    }
    
    // ==========================================
    // HELPERS
    // ==========================================
    
    private function sanitize($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }
    
    /**
     * Render a shop view with the shop layout
     * Views are loaded from app/views/shop/
     */
    private function renderShopView($view, $data = []) {
        // Set common shop data
        $data['APP_NAME'] = 'ADI ARI FRESH VEGETABLE AND HALAL FOOD';
        $data['shop_user'] = [
            'id' => $_SESSION['shop_user_id'] ?? null,
            'username' => $_SESSION['shop_username'] ?? null,
            'full_name' => $_SESSION['shop_full_name'] ?? null,
            'role' => $_SESSION['shop_role'] ?? null,
        ];
        $data['shop_success'] = $_SESSION['shop_success'] ?? null;
        $data['shop_error'] = $_SESSION['shop_error'] ?? null;
        $data['shop_errors'] = $_SESSION['shop_errors'] ?? null;
        
        // Clear flash messages
        unset($_SESSION['shop_success'], $_SESSION['shop_error'], $_SESSION['shop_errors']);
        
        extract($data);
        
        $viewFile = __DIR__ . '/../views/shop/' . $view . '.php';
        if (!file_exists($viewFile)) {
            throw new Exception("Shop view not found: {$viewFile}");
        }
        
        // Login page has its own full HTML layout
        $standaloneViews = ['auth/login'];
        if (in_array($view, $standaloneViews)) {
            require $viewFile;
            return;
        }
        
        // Include shop layout
        require __DIR__ . '/../views/shop/layouts/header.php';
        require $viewFile;
        require __DIR__ . '/../views/shop/layouts/footer.php';
    }
}
