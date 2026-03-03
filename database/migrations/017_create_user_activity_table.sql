-- =======================================================
-- ADI ARI Grocery Ecommerce - User Activity Table
-- Migration: 017_create_user_activity_table.sql
-- Database: adiari_analytics
-- =======================================================

CREATE TABLE IF NOT EXISTS user_activity (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL COMMENT 'References users.id from adiari_grocery',
    activity_type VARCHAR(50) NOT NULL COMMENT 'login, view_product, add_to_cart, checkout, etc',
    page_url VARCHAR(500) DEFAULT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent TEXT DEFAULT NULL,
    session_id VARCHAR(100) DEFAULT NULL,
    metadata JSON DEFAULT NULL COMMENT 'Additional activity data',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_user_id (user_id),
    INDEX idx_activity_type (activity_type),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
