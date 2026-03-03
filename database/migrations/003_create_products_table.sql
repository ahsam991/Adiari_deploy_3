-- =======================================================
-- ADI ARI Grocery Ecommerce - Products Table
-- Migration: 003_create_products_table.sql
-- Database: adiari_grocery
-- =======================================================

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT DEFAULT NULL,
    short_description VARCHAR(500) DEFAULT NULL,
    price DECIMAL(10, 2) NOT NULL,
    sale_price DECIMAL(10, 2) DEFAULT NULL,
    cost_price DECIMAL(10, 2) DEFAULT NULL,
    sku VARCHAR(100) UNIQUE DEFAULT NULL,
    barcode VARCHAR(100) DEFAULT NULL,
    quantity INT DEFAULT 0,
    stock_quantity INT DEFAULT 0,
    min_quantity INT DEFAULT 5,
    min_stock_level INT DEFAULT 5,
    weight DECIMAL(10, 2) DEFAULT NULL COMMENT 'Weight in kg',
    unit VARCHAR(50) DEFAULT 'piece' COMMENT 'kg, piece, pack, etc',
    is_halal TINYINT(1) DEFAULT 0,
    halal_cert_number VARCHAR(100) DEFAULT NULL,
    is_organic TINYINT(1) DEFAULT 0,
    is_featured TINYINT(1) DEFAULT 0,
    is_new TINYINT(1) DEFAULT 0,
    is_on_sale TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    status VARCHAR(20) DEFAULT 'active',
    primary_image VARCHAR(255) DEFAULT NULL,
    meta_title VARCHAR(255) DEFAULT NULL,
    meta_description VARCHAR(500) DEFAULT NULL,
    meta_keywords VARCHAR(255) DEFAULT NULL,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    views_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    INDEX idx_category_id (category_id),
    INDEX idx_slug (slug),
    INDEX idx_sku (sku),
    INDEX idx_price (price),
    INDEX idx_is_active (is_active),
    INDEX idx_status (status),
    INDEX idx_is_featured (is_featured),
    INDEX idx_is_halal (is_halal),
    INDEX idx_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
