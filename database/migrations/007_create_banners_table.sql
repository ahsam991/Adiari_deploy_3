-- Create banners table for admin-managed promotional banners
CREATE TABLE IF NOT EXISTS banners (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    subtitle TEXT,
    image_path VARCHAR(255) NOT NULL,
    link_url VARCHAR(500),
    button_text VARCHAR(100),
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    start_date DATETIME DEFAULT NULL,
    end_date DATETIME DEFAULT NULL,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_active (is_active),
    INDEX idx_order (display_order),
    INDEX idx_dates (start_date, end_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample banner
INSERT INTO banners (title, subtitle, image_path, link_url, button_text, display_order, is_active, created_by)
VALUES 
('Fresh Organic Vegetables', 'Get 20% off on all organic products this week!', '/uploads/banners/default-banner.jpg', '/shop', 'Shop Now', 1, 1, 1);
