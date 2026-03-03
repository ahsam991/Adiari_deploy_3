-- =======================================================
-- ADI ARI Grocery Ecommerce - Categories Table
-- Migration: 002_create_categories_table.sql
-- Database: adiari_grocery
-- =======================================================

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT DEFAULT NULL,
    parent_id INT DEFAULT NULL,
    image VARCHAR(255) DEFAULT NULL,
    icon VARCHAR(255) DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    status VARCHAR(20) DEFAULT 'active',
    sort_order INT DEFAULT 0,
    display_order INT DEFAULT 0,
    meta_title VARCHAR(255) DEFAULT NULL,
    meta_description VARCHAR(500) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_slug (slug),
    INDEX idx_parent_id (parent_id),
    INDEX idx_is_active (is_active),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
