-- =======================================================
-- ADI ARI - Fix Orders Table Schema
-- Run this on production if diagnose_orders.php shows missing columns
-- =======================================================

-- First, check if we need to migrate from old schema to new schema
-- Old schema has: discount, tax, total, shipping_name, shipping_address, notes
-- New schema has: discount_amount, tax_amount, total_amount, shipping_first_name, etc.

-- Step 1: Add missing columns that are completely new
-- These will only be added if they don't already exist

-- Safe approach: Use a procedure to check before adding
DELIMITER //

DROP PROCEDURE IF EXISTS fix_orders_table//

CREATE PROCEDURE fix_orders_table()
BEGIN
    -- Check if we have old-style columns (i.e., 'total' exists but 'total_amount' does not)
    DECLARE has_old_total INT DEFAULT 0;
    DECLARE has_new_total INT DEFAULT 0;
    DECLARE has_old_discount INT DEFAULT 0;
    DECLARE has_new_discount INT DEFAULT 0;
    DECLARE has_old_tax INT DEFAULT 0;
    DECLARE has_new_tax INT DEFAULT 0;
    DECLARE has_old_shipping_name INT DEFAULT 0;
    DECLARE has_new_shipping_first_name INT DEFAULT 0;
    DECLARE has_old_notes INT DEFAULT 0;
    DECLARE has_new_customer_notes INT DEFAULT 0;
    DECLARE has_old_shipping_address INT DEFAULT 0;
    DECLARE has_shipping_address_line1 INT DEFAULT 0;
    DECLARE has_coupon_code INT DEFAULT 0;
    DECLARE has_admin_notes INT DEFAULT 0;
    DECLARE has_confirmed_at INT DEFAULT 0;
    DECLARE has_shipped_at INT DEFAULT 0;
    DECLARE has_payment_date INT DEFAULT 0;
    DECLARE has_transaction_id INT DEFAULT 0;
    DECLARE has_shipping_state INT DEFAULT 0;
    DECLARE has_shipping_country INT DEFAULT 0;
    DECLARE has_shipping_address_line2 INT DEFAULT 0;

    SELECT COUNT(*) INTO has_old_total FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'orders' AND COLUMN_NAME = 'total';
    SELECT COUNT(*) INTO has_new_total FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'orders' AND COLUMN_NAME = 'total_amount';
    SELECT COUNT(*) INTO has_old_discount FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'orders' AND COLUMN_NAME = 'discount';
    SELECT COUNT(*) INTO has_new_discount FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'orders' AND COLUMN_NAME = 'discount_amount';
    SELECT COUNT(*) INTO has_old_tax FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'orders' AND COLUMN_NAME = 'tax';
    SELECT COUNT(*) INTO has_new_tax FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'orders' AND COLUMN_NAME = 'tax_amount';
    SELECT COUNT(*) INTO has_old_shipping_name FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'orders' AND COLUMN_NAME = 'shipping_name';
    SELECT COUNT(*) INTO has_new_shipping_first_name FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'orders' AND COLUMN_NAME = 'shipping_first_name';
    SELECT COUNT(*) INTO has_old_notes FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'orders' AND COLUMN_NAME = 'notes';
    SELECT COUNT(*) INTO has_new_customer_notes FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'orders' AND COLUMN_NAME = 'customer_notes';
    SELECT COUNT(*) INTO has_old_shipping_address FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'orders' AND COLUMN_NAME = 'shipping_address';
    SELECT COUNT(*) INTO has_shipping_address_line1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'orders' AND COLUMN_NAME = 'shipping_address_line1';
    SELECT COUNT(*) INTO has_coupon_code FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'orders' AND COLUMN_NAME = 'coupon_code';
    SELECT COUNT(*) INTO has_admin_notes FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'orders' AND COLUMN_NAME = 'admin_notes';
    SELECT COUNT(*) INTO has_confirmed_at FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'orders' AND COLUMN_NAME = 'confirmed_at';
    SELECT COUNT(*) INTO has_shipped_at FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'orders' AND COLUMN_NAME = 'shipped_at';
    SELECT COUNT(*) INTO has_payment_date FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'orders' AND COLUMN_NAME = 'payment_date';
    SELECT COUNT(*) INTO has_transaction_id FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'orders' AND COLUMN_NAME = 'transaction_id';
    SELECT COUNT(*) INTO has_shipping_state FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'orders' AND COLUMN_NAME = 'shipping_state';
    SELECT COUNT(*) INTO has_shipping_country FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'orders' AND COLUMN_NAME = 'shipping_country';
    SELECT COUNT(*) INTO has_shipping_address_line2 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'orders' AND COLUMN_NAME = 'shipping_address_line2';

    -- Rename old columns to new names if old schema exists
    IF has_old_total = 1 AND has_new_total = 0 THEN
        ALTER TABLE orders CHANGE COLUMN `total` `total_amount` DECIMAL(10,2) NOT NULL;
    END IF;

    IF has_old_discount = 1 AND has_new_discount = 0 THEN
        ALTER TABLE orders CHANGE COLUMN `discount` `discount_amount` DECIMAL(10,2) DEFAULT 0;
    END IF;

    IF has_old_tax = 1 AND has_new_tax = 0 THEN
        ALTER TABLE orders CHANGE COLUMN `tax` `tax_amount` DECIMAL(10,2) DEFAULT 0;
    END IF;

    IF has_old_notes = 1 AND has_new_customer_notes = 0 THEN
        ALTER TABLE orders CHANGE COLUMN `notes` `customer_notes` TEXT;
    END IF;

    -- For shipping_name -> shipping_first_name + shipping_last_name, we need to add new columns
    IF has_old_shipping_name = 1 AND has_new_shipping_first_name = 0 THEN
        ALTER TABLE orders ADD COLUMN `shipping_first_name` VARCHAR(100) AFTER `coupon_code`;
        ALTER TABLE orders ADD COLUMN `shipping_last_name` VARCHAR(100) AFTER `shipping_first_name`;
        -- Copy data: split shipping_name into first and last
        UPDATE orders SET shipping_first_name = SUBSTRING_INDEX(shipping_name, ' ', 1), shipping_last_name = SUBSTRING_INDEX(shipping_name, ' ', -1) WHERE shipping_name IS NOT NULL;
        ALTER TABLE orders DROP COLUMN `shipping_name`;
    END IF;

    -- For shipping_address -> shipping_address_line1
    IF has_old_shipping_address = 1 AND has_shipping_address_line1 = 0 THEN
        ALTER TABLE orders CHANGE COLUMN `shipping_address` `shipping_address_line1` VARCHAR(255);
    END IF;

    -- Add missing columns if they don't exist
    IF has_new_discount = 0 AND has_old_discount = 0 THEN
        ALTER TABLE orders ADD COLUMN `discount_amount` DECIMAL(10,2) DEFAULT 0 AFTER `shipping_cost`;
    END IF;

    IF has_new_total = 0 AND has_old_total = 0 THEN
        ALTER TABLE orders ADD COLUMN `total_amount` DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER `discount_amount`;
    END IF;

    IF has_new_tax = 0 AND has_old_tax = 0 THEN
        ALTER TABLE orders ADD COLUMN `tax_amount` DECIMAL(10,2) DEFAULT 0 AFTER `subtotal`;
    END IF;

    IF has_coupon_code = 0 THEN
        ALTER TABLE orders ADD COLUMN `coupon_code` VARCHAR(50) NULL AFTER `total_amount`;
    END IF;

    IF has_new_shipping_first_name = 0 AND has_old_shipping_name = 0 THEN
        ALTER TABLE orders ADD COLUMN `shipping_first_name` VARCHAR(100) AFTER `coupon_code`;
        ALTER TABLE orders ADD COLUMN `shipping_last_name` VARCHAR(100) AFTER `shipping_first_name`;
    END IF;

    IF has_shipping_address_line1 = 0 AND has_old_shipping_address = 0 THEN
        ALTER TABLE orders ADD COLUMN `shipping_address_line1` VARCHAR(255) AFTER `shipping_phone`;
    END IF;

    IF has_shipping_address_line2 = 0 THEN
        ALTER TABLE orders ADD COLUMN `shipping_address_line2` VARCHAR(255) AFTER `shipping_address_line1`;
    END IF;

    IF has_shipping_state = 0 THEN
        ALTER TABLE orders ADD COLUMN `shipping_state` VARCHAR(100) AFTER `shipping_city`;
    END IF;

    IF has_shipping_country = 0 THEN
        ALTER TABLE orders ADD COLUMN `shipping_country` VARCHAR(100) DEFAULT 'Japan' AFTER `shipping_postal_code`;
    END IF;

    IF has_new_customer_notes = 0 AND has_old_notes = 0 THEN
        ALTER TABLE orders ADD COLUMN `customer_notes` TEXT AFTER `shipping_country`;
    END IF;

    IF has_admin_notes = 0 THEN
        ALTER TABLE orders ADD COLUMN `admin_notes` TEXT AFTER `customer_notes`;
    END IF;

    IF has_confirmed_at = 0 THEN
        ALTER TABLE orders ADD COLUMN `confirmed_at` DATETIME NULL AFTER `admin_notes`;
    END IF;

    IF has_shipped_at = 0 THEN
        ALTER TABLE orders ADD COLUMN `shipped_at` DATETIME NULL AFTER `confirmed_at`;
    END IF;

    IF has_payment_date = 0 THEN
        ALTER TABLE orders ADD COLUMN `payment_date` DATETIME NULL AFTER `cancelled_at`;
    END IF;

    IF has_transaction_id = 0 THEN
        ALTER TABLE orders ADD COLUMN `transaction_id` VARCHAR(255) NULL AFTER `payment_date`;
    END IF;

    -- Fix the status ENUM to include 'confirmed' if missing
    ALTER TABLE orders MODIFY COLUMN `status` ENUM('pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending';

    SELECT 'Orders table fix completed successfully!' AS result;
END//

DELIMITER ;

-- Run the procedure
CALL fix_orders_table();

-- Clean up
DROP PROCEDURE IF EXISTS fix_orders_table;

-- Also fix order_items table if needed
-- Add product_name and product_sku columns if they don't exist
ALTER TABLE order_items ADD COLUMN IF NOT EXISTS `product_name` VARCHAR(200) NOT NULL DEFAULT 'Unknown Product' AFTER `product_id`;
ALTER TABLE order_items ADD COLUMN IF NOT EXISTS `product_sku` VARCHAR(100) NULL AFTER `product_name`;
