-- =======================================================
-- ADI ARI Grocery Ecommerce - Stock Logs Table
-- Migration: 014_create_stock_logs_table.sql
-- Database: adiari_inventory
-- =======================================================

CREATE TABLE IF NOT EXISTS stock_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL COMMENT 'References products.id from adiari_grocery',
    warehouse_id INT DEFAULT NULL,
    type ENUM('in', 'out', 'adjustment', 'return', 'damage') NOT NULL,
    quantity INT NOT NULL,
    previous_quantity INT NOT NULL,
    new_quantity INT NOT NULL,
    reference_type VARCHAR(50) DEFAULT NULL COMMENT 'order, purchase, adjustment',
    reference_id INT DEFAULT NULL COMMENT 'ID of related record',
    notes TEXT DEFAULT NULL,
    user_id INT DEFAULT NULL COMMENT 'References users.id from adiari_grocery',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_product_id (product_id),
    INDEX idx_type (type),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
