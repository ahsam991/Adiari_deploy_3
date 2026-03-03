-- Migration: Add tax_rate column to products table
-- Date: 2026-02-14
-- Description: Adds optional per-product tax rate override column

USE adiari_grocery;

-- Add tax_rate column to products table
-- NULL means use global tax rate, decimal value overrides global rate
ALTER TABLE products 
ADD COLUMN tax_rate DECIMAL(5,2) DEFAULT NULL COMMENT 'Per-product tax rate override (NULL = use global)' 
AFTER price;

-- Optional: Update existing products to use global rate (10%)
-- Uncomment if you want to set all existing products to 10% explicitly
-- UPDATE products SET tax_rate = 10.00 WHERE tax_rate IS NULL;
