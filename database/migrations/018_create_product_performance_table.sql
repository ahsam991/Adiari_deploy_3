-- =======================================================
-- ADI ARI Grocery Ecommerce - Product Performance Table
-- Migration: 018_create_product_performance_table.sql
-- Database: adiari_analytics
-- =======================================================

CREATE TABLE IF NOT EXISTS product_performance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL COMMENT 'References products.id from adiari_grocery',
    date DATE NOT NULL,
    views INT DEFAULT 0,
    add_to_cart_count INT DEFAULT 0,
    units_sold INT DEFAULT 0,
    revenue DECIMAL(12, 2) DEFAULT 0.00,
    conversion_rate DECIMAL(5, 2) DEFAULT 0.00 COMMENT 'Percentage',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE KEY unique_product_date (product_id, date),
    INDEX idx_product_id (product_id),
    INDEX idx_date (date),
    INDEX idx_units_sold (units_sold)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
