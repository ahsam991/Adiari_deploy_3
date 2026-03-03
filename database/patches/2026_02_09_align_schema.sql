-- Align schema with application model fields

ALTER TABLE products
  ADD COLUMN IF NOT EXISTS stock_quantity INT DEFAULT 0 AFTER quantity,
  ADD COLUMN IF NOT EXISTS min_stock_level INT DEFAULT 5 AFTER min_quantity,
  ADD COLUMN IF NOT EXISTS halal_cert_number VARCHAR(100) DEFAULT NULL AFTER is_halal,
  ADD COLUMN IF NOT EXISTS is_organic TINYINT(1) DEFAULT 0 AFTER halal_cert_number,
  ADD COLUMN IF NOT EXISTS is_new TINYINT(1) DEFAULT 0 AFTER is_featured,
  ADD COLUMN IF NOT EXISTS is_on_sale TINYINT(1) DEFAULT 0 AFTER is_new,
  ADD COLUMN IF NOT EXISTS status VARCHAR(20) DEFAULT 'active' AFTER is_active,
  ADD COLUMN IF NOT EXISTS primary_image VARCHAR(255) DEFAULT NULL AFTER status,
  ADD COLUMN IF NOT EXISTS views_count INT DEFAULT 0 AFTER deleted_at;

ALTER TABLE categories
  ADD COLUMN IF NOT EXISTS icon VARCHAR(255) DEFAULT NULL AFTER image,
  ADD COLUMN IF NOT EXISTS status VARCHAR(20) DEFAULT 'active' AFTER is_active,
  ADD COLUMN IF NOT EXISTS display_order INT DEFAULT 0 AFTER sort_order,
  ADD COLUMN IF NOT EXISTS meta_title VARCHAR(255) DEFAULT NULL AFTER display_order,
  ADD COLUMN IF NOT EXISTS meta_description VARCHAR(500) DEFAULT NULL AFTER meta_title;

UPDATE products SET stock_quantity = quantity WHERE stock_quantity IS NULL;
UPDATE products SET min_stock_level = min_quantity WHERE min_stock_level IS NULL;
UPDATE products SET status = IF(is_active = 1, 'active', 'inactive') WHERE status IS NULL OR status = '';
UPDATE categories SET status = IF(is_active = 1, 'active', 'inactive') WHERE status IS NULL OR status = '';
UPDATE categories SET display_order = sort_order WHERE display_order IS NULL;
