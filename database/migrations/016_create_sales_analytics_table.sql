-- =======================================================
-- ADI ARI Grocery Ecommerce - Sales Analytics Table
-- Migration: 016_create_sales_analytics_table.sql
-- Database: adiari_analytics
-- =======================================================

CREATE TABLE IF NOT EXISTS sales_analytics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL UNIQUE,
    total_orders INT DEFAULT 0,
    total_revenue DECIMAL(12, 2) DEFAULT 0.00,
    total_items_sold INT DEFAULT 0,
    average_order_value DECIMAL(10, 2) DEFAULT 0.00,
    new_customers INT DEFAULT 0,
    returning_customers INT DEFAULT 0,
    cancelled_orders INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_date (date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
