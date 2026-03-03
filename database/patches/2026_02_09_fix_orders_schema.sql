-- Fix orders table schema to match Order model expectations
-- Add total_amount column and sync with existing total column

ALTER TABLE orders
  ADD COLUMN IF NOT EXISTS total_amount DECIMAL(10,2) DEFAULT NULL AFTER total,
  ADD COLUMN IF NOT EXISTS discount_amount DECIMAL(10,2) DEFAULT 0.00 AFTER discount,
  ADD COLUMN IF NOT EXISTS tax_amount DECIMAL(10,2) DEFAULT 0.00 AFTER tax;

-- Copy existing data
UPDATE orders SET total_amount = total WHERE total_amount IS NULL;
UPDATE orders SET discount_amount = discount WHERE discount_amount IS NULL OR discount_amount = 0;
UPDATE orders SET tax_amount = tax WHERE tax_amount IS NULL OR tax_amount = 0;

-- Add indexes for performance
CREATE INDEX IF NOT EXISTS idx_total_amount ON orders(total_amount);
CREATE INDEX IF NOT EXISTS idx_created_date ON orders(created_at);
