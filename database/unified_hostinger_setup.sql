-- =======================================================
-- ADI ARI Fresh Vegetables & Halal Food
-- Unified Database Setup - FIXED to match PHP Models
-- =======================================================
-- Works for BOTH Hostinger (u314077991_adiari_shop) and Localhost (adiari_grocery)
-- Run this SQL in phpMyAdmin or MySQL CLI
-- =======================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
SET NAMES utf8mb4;

-- =======================================================
-- SECTION 1: CORE ECOMMERCE TABLES
-- =======================================================

-- Table: users
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    role ENUM('customer', 'manager', 'admin') DEFAULT 'customer',
    status ENUM('active', 'inactive') DEFAULT 'active',
    login_attempts INT DEFAULT 0,
    lockout_until DATETIME NULL,
    last_login_at DATETIME NULL,
    last_login_ip VARCHAR(45) NULL,
    email_verified_at DATETIME NULL,
    email_verification_token VARCHAR(255) NULL,
    password_reset_token VARCHAR(255) NULL,
    password_reset_expires DATETIME NULL,
    loyalty_points INT DEFAULT 0,
    total_purchases DECIMAL(12, 2) DEFAULT 0.00,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_status (status),
    INDEX idx_reset_token (password_reset_token)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: categories
CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    image VARCHAR(255),
    icon VARCHAR(100),
    parent_id INT NULL,
    display_order INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    meta_title VARCHAR(200),
    meta_description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_slug (slug),
    INDEX idx_status (status),
    INDEX idx_parent (parent_id),
    INDEX idx_display_order (display_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: products
CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT NOT NULL,
    name VARCHAR(200) NOT NULL,
    slug VARCHAR(200) UNIQUE NOT NULL,
    description TEXT,
    short_description VARCHAR(500),
    price DECIMAL(10,2) NOT NULL,
    sale_price DECIMAL(10,2) NULL,
    cost_price DECIMAL(10,2) NULL,
    tax_rate DECIMAL(5,2) DEFAULT NULL COMMENT 'Per-product tax rate override (NULL = use global)',
    sku VARCHAR(100) UNIQUE,
    barcode VARCHAR(100) DEFAULT NULL,
    unit VARCHAR(50) DEFAULT 'piece',
    weight DECIMAL(10,2),
    stock_quantity INT DEFAULT 0,
    min_stock_level INT DEFAULT 10,
    reorder_level INT DEFAULT 10,
    primary_image VARCHAR(255) NULL,
    is_halal TINYINT(1) DEFAULT 0,
    halal_cert_number VARCHAR(100) NULL,
    is_organic TINYINT(1) DEFAULT 0,
    is_featured TINYINT(1) DEFAULT 0,
    is_new TINYINT(1) DEFAULT 0,
    is_on_sale TINYINT(1) DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    views_count INT DEFAULT 0,
    meta_title VARCHAR(200),
    meta_description VARCHAR(500),
    meta_keywords VARCHAR(500),
    deleted_at DATETIME NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    INDEX idx_category (category_id),
    INDEX idx_slug (slug),
    INDEX idx_sku (sku),
    INDEX idx_barcode (barcode),
    INDEX idx_featured (is_featured),
    INDEX idx_status (status),
    INDEX idx_price (price),
    INDEX idx_deleted (deleted_at),
    FULLTEXT idx_search (name, description, short_description)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: product_images
CREATE TABLE IF NOT EXISTS product_images (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    is_primary TINYINT(1) DEFAULT 0,
    sort_order INT DEFAULT 0,
    alt_text VARCHAR(200),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_product (product_id),
    INDEX idx_primary (is_primary)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: cart
CREATE TABLE IF NOT EXISTS cart (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    price DECIMAL(10,2) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_product (product_id),
    UNIQUE KEY unique_user_product (user_id, product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: orders
CREATE TABLE IF NOT EXISTS orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    status ENUM('pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    payment_method VARCHAR(50),
    payment_status ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending',
    subtotal DECIMAL(10,2) NOT NULL,
    tax_amount DECIMAL(10,2) DEFAULT 0,
    shipping_cost DECIMAL(10,2) DEFAULT 0,
    discount_amount DECIMAL(10,2) DEFAULT 0,
    total_amount DECIMAL(10,2) NOT NULL,
    coupon_code VARCHAR(50) NULL,
    shipping_first_name VARCHAR(100),
    shipping_last_name VARCHAR(100),
    shipping_email VARCHAR(100),
    shipping_phone VARCHAR(20),
    shipping_address_line1 VARCHAR(255),
    shipping_address_line2 VARCHAR(255),
    shipping_city VARCHAR(100),
    shipping_state VARCHAR(100),
    shipping_postal_code VARCHAR(20),
    shipping_country VARCHAR(100) DEFAULT 'Japan',
    customer_notes TEXT,
    admin_notes TEXT,
    confirmed_at DATETIME NULL,
    shipped_at DATETIME NULL,
    delivered_at DATETIME NULL,
    cancelled_at DATETIME NULL,
    payment_date DATETIME NULL,
    transaction_id VARCHAR(255) NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_order_number (order_number),
    INDEX idx_status (status),
    INDEX idx_payment_status (payment_status),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: order_items
CREATE TABLE IF NOT EXISTS order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(200) NOT NULL,
    product_sku VARCHAR(100) NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_order (order_id),
    INDEX idx_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: user_addresses
CREATE TABLE IF NOT EXISTS user_addresses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    address_type ENUM('home', 'work', 'other') DEFAULT 'home',
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address_line1 VARCHAR(255) NOT NULL,
    address_line2 VARCHAR(255),
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100),
    postal_code VARCHAR(20) NOT NULL,
    country VARCHAR(100) DEFAULT 'Japan',
    is_default TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_type (address_type),
    INDEX idx_default (is_default)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: reviews
CREATE TABLE IF NOT EXISTS reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    title VARCHAR(200),
    comment TEXT,
    is_verified_purchase TINYINT(1) DEFAULT 0,
    is_approved TINYINT(1) DEFAULT 0,
    helpful_count INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_product (product_id),
    INDEX idx_user (user_id),
    INDEX idx_rating (rating),
    INDEX idx_approved (is_approved)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: wishlist
CREATE TABLE IF NOT EXISTS wishlist (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_wishlist (user_id, product_id),
    INDEX idx_user (user_id),
    INDEX idx_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: coupons (unified for both ecommerce + POS)
-- NOTE: PK is coupon_id (used by ShopCoupon model's applyCoupon method)
CREATE TABLE IF NOT EXISTS coupons (
    coupon_id INT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(50) UNIQUE NOT NULL,
    description VARCHAR(255),
    discount_type ENUM('percentage', 'fixed') NOT NULL,
    discount_value DECIMAL(10,2) NOT NULL,
    min_purchase_amount DECIMAL(10,2) DEFAULT 0,
    max_discount_amount DECIMAL(10,2),
    usage_limit INT DEFAULT 0,
    usage_per_user INT DEFAULT 1,
    times_used INT DEFAULT 0,
    valid_from DATETIME NOT NULL,
    valid_until DATETIME NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_code (code),
    INDEX idx_active (is_active),
    INDEX idx_dates (valid_from, valid_until)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: coupon_usage
CREATE TABLE IF NOT EXISTS coupon_usage (
    id INT PRIMARY KEY AUTO_INCREMENT,
    coupon_id INT NOT NULL,
    user_id INT NOT NULL,
    order_id INT NOT NULL,
    discount_amount DECIMAL(10,2) NOT NULL,
    used_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (coupon_id) REFERENCES coupons(coupon_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    INDEX idx_coupon (coupon_id),
    INDEX idx_user (user_id),
    INDEX idx_order (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: settings
CREATE TABLE IF NOT EXISTS settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_type ENUM('string', 'number', 'boolean', 'json') DEFAULT 'string',
    description VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: offers (weekly deals)
CREATE TABLE IF NOT EXISTS offers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT,
    discount_type ENUM('percentage', 'fixed') NOT NULL DEFAULT 'percentage',
    discount_value DECIMAL(10,2) NOT NULL,
    start_date DATETIME NOT NULL,
    end_date DATETIME NOT NULL,
    status ENUM('active', 'expired', 'inactive') DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL,
    INDEX idx_product (product_id),
    INDEX idx_status (status),
    INDEX idx_dates (start_date, end_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: changelog
CREATE TABLE IF NOT EXISTS changelog (
    id INT PRIMARY KEY AUTO_INCREMENT,
    version VARCHAR(20),
    title VARCHAR(200) NOT NULL,
    description TEXT,
    change_type ENUM('feature', 'fix', 'improvement', 'security') DEFAULT 'feature',
    changed_by VARCHAR(100),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =======================================================
-- SECTION 2: INVENTORY / STOCK TABLES
-- =======================================================

-- Table: product_stock
CREATE TABLE IF NOT EXISTS product_stock (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    warehouse_id INT DEFAULT 1,
    quantity INT NOT NULL DEFAULT 0,
    reserved_quantity INT DEFAULT 0,
    reorder_level INT DEFAULT 10,
    last_restock_date DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_product (product_id),
    INDEX idx_warehouse (warehouse_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: stock_logs
CREATE TABLE IF NOT EXISTS stock_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    warehouse_id INT DEFAULT 1,
    change_type ENUM('restock', 'sale', 'adjustment', 'return', 'damaged') NOT NULL,
    quantity_change INT NOT NULL,
    previous_quantity INT NOT NULL,
    new_quantity INT NOT NULL,
    reference_id VARCHAR(100),
    notes TEXT,
    created_by INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_product (product_id),
    INDEX idx_type (change_type),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: warehouse
CREATE TABLE IF NOT EXISTS warehouse (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    address TEXT,
    phone VARCHAR(20),
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =======================================================
-- SECTION 3: ANALYTICS TABLES
-- =======================================================

-- Table: sales_analytics
CREATE TABLE IF NOT EXISTS sales_analytics (
    id INT PRIMARY KEY AUTO_INCREMENT,
    date DATE NOT NULL,
    total_orders INT DEFAULT 0,
    total_revenue DECIMAL(12,2) DEFAULT 0,
    total_items_sold INT DEFAULT 0,
    average_order_value DECIMAL(10,2) DEFAULT 0,
    new_customers INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_date (date),
    INDEX idx_date (date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: user_activity
CREATE TABLE IF NOT EXISTS user_activity (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    activity_type VARCHAR(100) NOT NULL,
    page_url VARCHAR(500) NULL,
    ip_address VARCHAR(45),
    user_agent VARCHAR(500),
    session_id VARCHAR(255) NULL,
    metadata TEXT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user (user_id),
    INDEX idx_activity_type (activity_type),
    INDEX idx_session (session_id),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: product_performance
CREATE TABLE IF NOT EXISTS product_performance (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    date DATE NOT NULL,
    views INT DEFAULT 0,
    add_to_cart INT DEFAULT 0,
    purchases INT DEFAULT 0,
    revenue DECIMAL(10,2) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_product_date (product_id, date),
    INDEX idx_product (product_id),
    INDEX idx_date (date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =======================================================
-- SECTION 4: SHOP/POS MODULE TABLES
-- =======================================================

-- Table: shop_users (internal staff for POS system)
CREATE TABLE IF NOT EXISTS shop_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) DEFAULT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'staff',
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    last_login DATETIME DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: sessions (POS session tracking)
CREATE TABLE IF NOT EXISTS sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    session_token VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent VARCHAR(255) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    expires_at DATETIME NULL,
    FOREIGN KEY (user_id) REFERENCES shop_users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: activity_logs (POS activity logs)
CREATE TABLE IF NOT EXISTS activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    action VARCHAR(50) NOT NULL,
    module VARCHAR(50) DEFAULT NULL,
    details TEXT DEFAULT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: inventory_items (REMOVED - Unified with 'products' table)
-- Table: customers (REMOVED - Unified with 'users' table)

-- Table: import_logs
CREATE TABLE IF NOT EXISTS import_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    filename VARCHAR(255) NOT NULL,
    items_imported INT NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'success',
    records_processed INT DEFAULT 0,
    errors INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: invoices (POS invoices)
CREATE TABLE IF NOT EXISTS invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    invoice_number VARCHAR(50) NOT NULL UNIQUE,
    customer_name VARCHAR(100) DEFAULT NULL,
    customer_phone VARCHAR(20) DEFAULT NULL,
    customer_id INT DEFAULT NULL,
    coupon_id INT DEFAULT NULL,
    staff_id INT DEFAULT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    tax DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    discount DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    total_amount DECIMAL(10, 2) NOT NULL,
    payment_method VARCHAR(50) NOT NULL DEFAULT 'Cash',
    amount_paid DECIMAL(10, 2) DEFAULT 0.00,
    change_amount DECIMAL(10, 2) DEFAULT 0.00,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES users(id) ON DELETE SET NULL, -- CHANGED LINK TO USERS
    FOREIGN KEY (staff_id) REFERENCES shop_users(id) ON DELETE SET NULL,
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: invoice_items
CREATE TABLE IF NOT EXISTS invoice_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    invoice_id INT NOT NULL,
    inventory_id INT DEFAULT NULL,
    product_name VARCHAR(255) NOT NULL,
    barcode VARCHAR(100) DEFAULT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL,
    line_total DECIMAL(10, 2) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE CASCADE,
    FOREIGN KEY (inventory_id) REFERENCES products(id) ON DELETE SET NULL -- CHANGED LINK TO PRODUCTS
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =======================================================
-- SECTION 4.5: DATABASE MIGRATIONS (Add missing columns to existing tables)
-- =======================================================

-- Add loyalty columns to users table if they don't exist
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS loyalty_points INT DEFAULT 0 AFTER password_reset_expires,
ADD COLUMN IF NOT EXISTS total_purchases DECIMAL(12, 2) DEFAULT 0.00 AFTER loyalty_points;

-- Add reorder_level to products table if it doesn't exist
ALTER TABLE products 
ADD COLUMN IF NOT EXISTS reorder_level INT DEFAULT 10 AFTER min_stock_level;

-- =======================================================
-- SECTION 5: DEFAULT DATA
-- =======================================================

-- Default Admin User for ecommerce (password: admin123)
INSERT INTO users (first_name, last_name, email, password, phone, role, status, loyalty_points, total_purchases) VALUES
('Admin', 'User', 'admin@adiarifresh.com', '$2y$12$/hgMUXFkkC0AY0EF31Cj/.bWb7xW3YtQBWF/CqERRCn60IbtTOD8W', '080-3408-8044', 'admin', 'active', 0, 0),
('Manager', 'User', 'manager@adiarifresh.com', '$2y$12$/hgMUXFkkC0AY0EF31Cj/.bWb7xW3YtQBWF/CqERRCn60IbtTOD8W', '080-3408-8045', 'manager', 'active', 0, 0),
('Test', 'Customer', 'customer@example.com', '$2y$12$/hgMUXFkkC0AY0EF31Cj/.bWb7xW3YtQBWF/CqERRCn60IbtTOD8W', '080-1234-5678', 'customer', 'active', 50, 5000.00)
ON DUPLICATE KEY UPDATE email=email;

-- Default POS Admin (password: admin123)
INSERT INTO shop_users (username, password_hash, full_name, email, role) VALUES 
('admin', '$2y$12$4UKx7I6bZVdkQK7H5jRJP.WoHAl.bp055JuB76nAGe2Z3o5zOPayW', 'System Administrator', 'admin@adiarifresh.com', 'admin'),
('manager', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Store Manager', 'manager@adiarifresh.com', 'manager'),
('staff1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Staff Member', 'staff@adiarifresh.com', 'staff')
ON DUPLICATE KEY UPDATE username=username;

-- Default Categories
INSERT INTO categories (name, slug, description, status, display_order) VALUES
('Fresh Vegetables', 'fresh-vegetables', 'Fresh and organic vegetables', 'active', 1),
('Halal Meat', 'halal-meat', 'Certified halal meat products', 'active', 2),
('Dairy Products', 'dairy-products', 'Fresh dairy and cheese', 'active', 3),
('Fruits', 'fruits', 'Fresh seasonal fruits', 'active', 4),
('Spices & Herbs', 'spices-herbs', 'Fresh and dried spices', 'active', 5),
('Frozen Foods', 'frozen-foods', 'Frozen vegetables and meats', 'active', 6),
('Bakery', 'bakery', 'Fresh baked goods', 'active', 7),
('Beverages', 'beverages', 'Drinks and juices', 'active', 8)
ON DUPLICATE KEY UPDATE slug=slug;

-- Sample Products with Images
INSERT INTO products (category_id, name, slug, description, short_description, price, sku, unit, stock_quantity, is_featured, primary_image, status) VALUES
(1, 'Fresh Tomatoes', 'fresh-tomatoes', 'Fresh red tomatoes from local farms', 'Juicy red tomatoes', 399, 'VEG-001', 'kg', 100, 1, '04ffbcb5d4a8462dc3fe955809dc3545.png', 'active'),
(1, 'Organic Spinach', 'organic-spinach', 'Organic spinach leaves', 'Fresh green spinach', 299, 'VEG-002', 'bunch', 80, 1, '7f0e0600d6f699503450fe5d3a454197.png', 'active'),
(1, 'Carrots', 'carrots', 'Fresh orange carrots', 'Sweet crunchy carrots', 250, 'VEG-003', 'kg', 120, 0, NULL, 'active'),
(2, 'Halal Chicken Breast', 'halal-chicken-breast', 'Premium halal chicken breast', 'Tender chicken breast', 1299, 'MEAT-001', 'kg', 50, 1, NULL, 'active'),
(2, 'Halal Beef', 'halal-beef', 'Premium halal beef cuts', 'Quality beef cuts', 1899, 'MEAT-002', 'kg', 30, 1, NULL, 'active'),
(3, 'Fresh Milk', 'fresh-milk', 'Whole fresh milk', 'Rich creamy milk', 450, 'DAIRY-001', 'liter', 200, 0, NULL, 'active'),
(4, 'Bananas', 'bananas', 'Fresh yellow bananas', 'Sweet ripe bananas', 299, 'FRUIT-001', 'bunch', 150, 0, NULL, 'active'),
(5, 'Basmati Rice', 'basmati-rice', 'Premium long grain basmati rice', 'Aromatic basmati rice', 2499, 'GRAIN-001', '5kg', 45, 1, NULL, 'active')
ON DUPLICATE KEY UPDATE slug=slug;

-- Sample Weekly Deals (Offers)
-- Note: Start and end dates are dynamic for demo purposes
INSERT INTO offers (product_id, discount_type, discount_value, start_date, end_date, status)
SELECT id, 'percentage', 20, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 7 DAY), 'active'
FROM products WHERE sku IN ('VEG-001', 'VEG-002')
ON DUPLICATE KEY UPDATE product_id=product_id;



-- Default Settings (Japan-specific)
INSERT INTO settings (setting_key, setting_value, setting_type, description) VALUES
('global_tax_rate', '10', 'number', 'Global tax rate percentage'),
('tax_enabled', '1', 'boolean', 'Whether tax is enabled'),
('tax_label', 'Consumption Tax', 'string', 'Label for tax display'),
('tax_included_in_price', '1', 'boolean', 'Whether tax is included in displayed price'),
('currency', 'JPY', 'string', 'Currency code'),
('currency_symbol', '¥', 'string', 'Currency symbol'),
('site_name', 'ADI ARI Fresh Vegetables & Halal Food', 'string', 'Site name'),
('low_stock_threshold', '10', 'number', 'Low stock alert threshold'),
('store_address', '114-0031 Higashi Tabata 2-3-1 Otsu building 101', 'string', 'Store physical address'),
('store_phone', '080-3408-8044', 'string', 'Store phone number'),
('store_email', 'info@adiarifresh.com', 'string', 'Store email')
ON DUPLICATE KEY UPDATE setting_key=setting_key;

-- Default Warehouse
INSERT INTO warehouse (name, address, phone, is_active) VALUES
('Main Store', '114-0031 Higashi Tabata 2-3-1 Otsu building 101', '080-3408-8044', 1)
ON DUPLICATE KEY UPDATE name=name;

-- Default stock records for products
INSERT INTO product_stock (product_id, warehouse_id, quantity, reorder_level) VALUES
(1, 1, 100, 10), (2, 1, 80, 10), (3, 1, 120, 10), (4, 1, 50, 5),
(5, 1, 30, 5), (6, 1, 200, 20), (7, 1, 150, 15), (8, 1, 100, 10)
ON DUPLICATE KEY UPDATE product_id=product_id;


-- =======================================================
-- SECTION 6: STOCK SYNCHRONIZATION (TRIGGERS)
-- =======================================================
-- NOTE: inventory_items table was removed and unified with 'products' table
-- These triggers are kept for future extensibility if needed

DELIMITER //

-- Drop existing triggers if they exist to avoid conflicts
DROP TRIGGER IF EXISTS after_product_update//
DROP TRIGGER IF EXISTS after_inventory_update//
DROP TRIGGER IF EXISTS after_product_stock_update//

-- Trigger: Update product_stock when products.stock_quantity changes
CREATE TRIGGER after_product_stock_update
AFTER UPDATE ON products
FOR EACH ROW
BEGIN
    IF NEW.stock_quantity <> OLD.stock_quantity THEN
        UPDATE product_stock 
        SET quantity = NEW.stock_quantity 
        WHERE product_id = NEW.id;
    END IF;
END//

DELIMITER ;

SET FOREIGN_KEY_CHECKS = 1;

-- =======================================================
-- SETUP COMPLETE
-- =======================================================

SELECT 'Unified database setup completed!' as Status,
       (SELECT COUNT(*) FROM users) as EcomUsers,
       (SELECT COUNT(*) FROM shop_users) as POSUsers,
       (SELECT COUNT(*) FROM categories) as Categories,
       (SELECT COUNT(*) FROM products) as Products;

-- =======================================================
-- LOGIN CREDENTIALS
-- =======================================================
-- Ecommerce Admin:    admin@adiarifresh.com / admin123
-- Ecommerce Manager:  manager@adiarifresh.com / admin123
-- Test Customer:      customer@example.com / admin123
-- POS Admin:          admin / admin123
-- POS Manager:        manager / admin123
-- POS Staff:          staff1 / admin123
-- =======================================================
