<?php
/**
 * Cart Controller
 * Handles shopping cart operations for logged-in users
 */

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Setting.php';
require_once __DIR__ . '/../helpers/Session.php';
require_once __DIR__ . '/../helpers/Security.php';
require_once __DIR__ . '/../helpers/Logger.php';

class CartController extends Controller {
    private $cartModel;
    private $productModel;

    public function __construct() {
        parent::__construct();
        $this->cartModel = new Cart();
        $this->productModel = new Product();
    }

    private function requireUser() {
        if (!Session::isLoggedIn()) {
            Session::setFlash('error', 'Please login to manage your cart.');
            $this->redirect('/login');
        }
        return (int) Session::get('user_id');
    }

    /**
     * Cart page
     */
    public function index() {
        $userId = $this->requireUser();
        $items = $this->cartModel->getUserCart($userId);
        $totals = $this->cartModel->getCartTotals($userId);
        $validation = $this->cartModel->validateCart($userId);

        // Calculate tax dynamically
        $taxEnabled = Setting::get('tax_enabled', true);
        $taxIncluded = Setting::get('tax_included_in_price', true);
        $globalTaxRate = Setting::get('global_tax_rate', 10);
        $taxLabel = Setting::get('tax_label', 'Consumption Tax');
        $taxAmount = 0;

        if ($taxEnabled && !empty($items)) {
            foreach ($items as $item) {
                $product = $this->productModel->find($item['product_id']);
                $itemRate = ($product && $product['tax_rate'] !== null) ? (float)$product['tax_rate'] : $globalTaxRate;
                $itemTotal = (float)$item['price'] * (int)$item['quantity'];
                $taxAmount += Setting::calculateTax($itemTotal, $itemRate, $taxIncluded);
            }
        }

        // Calculate final total
        $subtotal = $totals['subtotal'] ?? 0;
        
        // Add tax to subtotal to get final price as requested by user
        $totalWithTax = $subtotal + $taxAmount;

        $this->view('cart/index', [
            'title' => 'Shopping Cart - ADI ARI Fresh',
            'items' => $items,
            'totals' => $totals,
            'validation' => $validation,
            'taxEnabled' => $taxEnabled,
            'taxIncluded' => $taxIncluded,
            'taxRate' => $globalTaxRate,
            'taxLabel' => $taxLabel,
            'taxAmount' => $taxAmount,
            'totalWithTax' => $totalWithTax,
        ]);
    }

    /**
     * Add item to cart (POST)
     */
    public function add() {
        $userId = $this->requireUser();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/products');
        }

        $token = $this->post('csrf_token');
        if (!Security::validateCsrfToken($token)) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Invalid request.'], 403);
            }
            Session::setFlash('error', 'Invalid request. Please try again.');
            $this->redirect('/cart');
        }

        $productId = (int) ($this->post('product_id') ?? 0);
        $quantity = (int) ($this->post('quantity') ?? 1);

        if ($productId < 1 || $quantity < 1) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Invalid product or quantity.'], 400);
            }
            Session::setFlash('error', 'Invalid product or quantity.');
            $this->redirect('/products');
        }

        $product = $this->productModel->find($productId);
        if (!$product || $product['status'] !== 'active') {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Product not available.'], 404);
            }
            Session::setFlash('error', 'Product not available.');
            $this->redirect('/products');
        }

        if ($product['stock_quantity'] < $quantity) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Insufficient stock.'], 400);
            }
            Session::setFlash('error', 'Insufficient stock for this product.');
            $this->redirect('/product/' . $productId);
        }

        $price = isset($product['sale_price']) && $product['sale_price'] > 0
            ? (float) $product['sale_price']
            : (float) $product['price'];

        $this->cartModel->addItem($userId, $productId, $quantity, $price);

        // Log activity
        Logger::activity($userId, 'cart_add', [
            'product_id' => $productId,
            'product_name' => $product['name'],
            'quantity' => $quantity,
            'price' => $price
        ]);

        if ($this->isAjax()) {
            $count = $this->cartModel->getCartCount($userId);
            $totals = $this->cartModel->getCartTotals($userId);
            $this->json([
                'success' => true,
                'message' => 'Added to cart.',
                'cart_count' => $count,
                'cart_subtotal' => number_format($totals['subtotal'], 2)
            ]);
        }

        Session::setFlash('success', 'Added to cart.');
        $redirect = $this->post('redirect') ?: '/cart';
        $this->redirect($redirect);
    }

    /**
     * Update cart item quantity (POST)
     */
    public function update() {
        $userId = $this->requireUser();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/cart');
        }

        if (!Security::validateCsrfToken($this->post('csrf_token'))) {
            Session::setFlash('error', 'Invalid request.');
            $this->redirect('/cart');
        }

        $productId = (int) ($this->post('product_id') ?? 0);
        $quantity = (int) ($this->post('quantity') ?? 0);

        if ($productId < 1) {
            Session::setFlash('error', 'Invalid item.');
            $this->redirect('/cart');
        }

        if ($quantity <= 0) {
            $this->cartModel->removeItem($userId, $productId);
            Session::setFlash('success', 'Item removed from cart.');
            $this->redirect('/cart');
        }

        $product = $this->productModel->find($productId);
        if (!$product || $product['stock_quantity'] < $quantity) {
            Session::setFlash('error', 'Insufficient stock.');
            $this->redirect('/cart');
        }

        $this->cartModel->updateItemQuantity($userId, $productId, $quantity);
        Session::setFlash('success', 'Cart updated.');
        $this->redirect('/cart');
    }

    /**
     * Remove item from cart (POST)
     */
    public function remove() {
        $userId = $this->requireUser();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/cart');
        }

        if (!Security::validateCsrfToken($this->post('csrf_token'))) {
            Session::setFlash('error', 'Invalid request.');
            $this->redirect('/cart');
        }

        $productId = (int) ($this->post('product_id') ?? 0);
        if ($productId > 0) {
            $this->cartModel->removeItem($userId, $productId);
            Session::setFlash('success', 'Item removed from cart.');
        }
        $this->redirect('/cart');
    }
}
