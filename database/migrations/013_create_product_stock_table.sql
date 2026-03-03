-- =======================================================
-- ADI ARI Grocery Ecommerce - Product Stock Table
-- Migration: 013_create_product_stock_table.sql
-- Database: adiari_inventory
-- =======================================================

CREATE TABLE IF NOT EXISTS product_stock (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL UNIQUE COMMENT 'References products.id from adiari_grocery',
    warehouse_id INT DEFAULT NULL,
    quantity INT NOT NULL DEFAULT 0,
    reserved_quantity INT DEFAULT 0 COMMENT 'Quantity in pending orders',
    available_quantity INT GENERATED ALWAYS AS (quantity - reserved_quantity) STORED,
    reorder_level INT DEFAULT 10,
    reorder_quantity INT DEFAULT 50,
    last_restock_date DATETIME DEFAULT NULL,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_product_id (product_id),
    INDEX idx_warehouse_id (warehouse_id),
    INDEX idx_available_quantity (available_quantity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
