-- Migration: Create or update settings table with proper structure
-- Date: 2026-02-14
-- Description: Creates settings table with all required columns including setting_type

USE adiari_grocery;

-- Drop table if exists to recreate with proper structure
DROP TABLE IF EXISTS settings;

-- Create settings table with proper structure
CREATE TABLE settings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    setting_type ENUM('string', 'number', 'boolean', 'json') DEFAULT 'string',
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_setting_key (setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default system settings
INSERT INTO settings (setting_key, setting_value, setting_type, description) VALUES
('global_tax_rate', '10', 'number', 'Global tax rate percentage (Japan consumption tax)'),
('tax_enabled', '1', 'boolean', 'Whether tax calculation is enabled'),
('tax_label', 'Consumption Tax', 'string', 'Label shown on invoices and receipts'),
('tax_included_in_price', '1', 'boolean', 'Whether product prices already include tax'),
('site_name', 'ADI ARI Fresh Vegetables and Halal Food', 'string', 'Site name'),
('site_email', 'admin@adiarifresh.com', 'string', 'Admin email address'),
('currency', 'JPY', 'string', 'Site currency'),
('currency_symbol', '¥', 'string', 'Currency symbol'),
('date_format', 'Y-m-d', 'string', 'Date format for display'),
('items_per_page', '20', 'number', 'Default items per page'),
('maintenance_mode', '0', 'boolean', 'Site maintenance mode'),
('low_stock_threshold', '10', 'number', 'Alert threshold for low stock');
