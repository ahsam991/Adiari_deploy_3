<?php
/**
 * Product Controller
 * Handles public product browsing, searching, and details
 */

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../helpers/Session.php';
require_once __DIR__ . '/../helpers/Logger.php';

class ProductController extends Controller {
    private $productModel;
    private $categoryModel;

    public function __construct() {
        parent::__construct();
        $this->productModel = new Product();
        $this->categoryModel = new Category();
    }

    /**
     * Shop/Product Listing Page
     */
    public function index() {
        // Get query parameters
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = isset($_GET['search']) ? trim($_GET['search']) : (isset($_GET['q']) ? trim($_GET['q']) : '');
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
        $categoryParam = isset($_GET['category']) ? $_GET['category'] : null;
        
        // Validate and sanitize pagination
        $page = max(1, $page); // Ensure page is at least 1
        
        $limit = 100; // Products per page
        $offset = ($page - 1) * $limit;

        // Try to resolve category (by ID or slug)
        $currentCategory = null;
        if ($categoryParam) {
            if (is_numeric($categoryParam)) {
                $currentCategory = $this->categoryModel->find((int)$categoryParam);
            } else {
                $currentCategory = $this->categoryModel->findBySlug($categoryParam);
            }
        }

        if ($search) {
            // Search products
            $products = $this->productModel->searchProducts($search, $limit, $offset);
            $totalProducts = $this->productModel->countSearchProducts($search);
            $title = "Search results for \"" . htmlspecialchars($search, ENT_QUOTES, 'UTF-8') . "\" - ADI ARI Fresh";
        } elseif ($currentCategory) {
            // Filter by category
            $products = $this->productModel->getProductsByCategory($currentCategory['id'], $limit, $offset, $sort);
            $totalProducts = $this->productModel->countProductsByCategory($currentCategory['id']);
            $title = htmlspecialchars($currentCategory['name']) . ' - ADI ARI Fresh';
        } else {
            // Get all active products
            $products = $this->productModel->getActiveProducts($limit, $offset, $sort);
            $totalProducts = $this->productModel->countActiveProducts();
            $title = 'Shop - ADI ARI Fresh';
        }

        // Get categories for sidebar
        $categories = $this->categoryModel->getCategoriesWithProductCount();

        // Calculate pagination
        $totalPages = ceil($totalProducts / $limit);

        $this->view('products/index', [
            'title' => $title,
            'products' => $products,
            'categories' => $categories,
            'currentCategory' => $currentCategory,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search,
            'sort' => $sort,
            'totalProducts' => $totalProducts
        ]);
    }

    /**
     * Product Detail Page
     * @param string $id Product ID or Slug (currently ID based on routes, but robust to handle ID)
     */
    public function show($id) {
        $product = $this->productModel->getProductDetails((int)$id);

        if (!$product) {
            Session::setFlash('error', 'Product not found.');
            $this->redirect('/products');
        }

        // Increment view count
        $this->productModel->incrementViews((int)$id);

        // Log activity if user is logged in
        if ($this->isLoggedIn()) {
            $userId = Session::get('user_id');
            Logger::activity($userId, 'product_view', [
                'product_id' => $product['id'],
                'product_name' => $product['name'],
                'category' => $product['category_name']
            ]);
        }

        // Get related products
        $relatedProducts = $this->productModel->getRelatedProducts((int)$id, $product['category_id'], 4);

        $inWishlist = false;
        if ($this->isLoggedIn()) {
            require_once __DIR__ . '/../models/Wishlist.php';
            $wishlistModel = new Wishlist();
            $inWishlist = (bool) $wishlistModel->findBy(['user_id' => Session::get('user_id'), 'product_id' => $product['id']]);
        }

        $this->view('products/show', [
            'title' => $product['name'] . ' - ADI ARI Fresh',
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'inWishlist' => $inWishlist
        ]);
    }

    /**
     * Products by Category
     * @param string $slug Category Slug
     */
    public function category($slug) {
        $category = $this->categoryModel->findBySlug($slug);

        if (!$category) {
            Session::setFlash('error', 'Category not found.');
            $this->redirect('/products');
        }

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
        
        // Validate and sanitize pagination
        $page = max(1, $page); // Ensure page is at least 1
        
        $limit = 100;
        $offset = ($page - 1) * $limit;

        // Get products in this category
        $products = $this->productModel->getProductsByCategory($category['id'], $limit, $offset, $sort);
        $totalProducts = $this->productModel->countProductsByCategory($category['id']);

        // Get all categories for sidebar
        $categories = $this->categoryModel->getCategoriesWithProductCount();

        $totalPages = ceil($totalProducts / $limit);

        $this->view('products/index', [
            'title' => $category['name'] . ' - ADI ARI Fresh',
            'products' => $products,
            'categories' => $categories,
            'currentCategory' => $category,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'sort' => $sort,
            'totalProducts' => $totalProducts,
            'search' => ''
        ]);
    }
}
