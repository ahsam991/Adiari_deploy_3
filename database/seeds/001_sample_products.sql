-- =======================================================
-- ADI ARI Grocery Ecommerce - Sample Data Seeder
-- Seed: 001_sample_products.sql
-- Run after all migrations are complete
-- =======================================================

USE adiari_grocery;

-- =======================================================
-- 1. CREATE DEFAULT USERS
-- =======================================================
-- Password for all users: admin123 (for admin), manager123 (for manager), customer123 (for customer)
INSERT INTO users (name, email, password, phone, role, is_active, email_verified) VALUES
('Admin User', 'admin@adiarifresh.com', '$2y$12$LQv3c1yycmFJyPbVNm7wIeKPrzzKaQlJq4j1uGVVdQWTOPZ4Xl6YG', '080-3408-8044', 'admin', 1, 1),
('Manager User', 'manager@adiarifresh.com', '$2y$12$LQv3c1yycmFJyPbVNm7wIeKPrzzKaQlJq4j1uGVVdQWTOPZ4Xl6YG', '080-3408-8045', 'manager', 1, 1),
('Customer Test', 'customer@example.com', '$2y$12$LQv3c1yycmFJyPbVNm7wIeKPrzzKaQlJq4j1uGVVdQWTOPZ4Xl6YG', '080-3408-8046', 'customer', 1, 1);

-- =======================================================
-- 2. CREATE CATEGORIES
-- =======================================================
INSERT INTO categories (name, slug, description, is_active, sort_order) VALUES
('Vegetables', 'vegetables', 'Fresh vegetables from local farms', 1, 1),
('Fruits', 'fruits', 'Seasonal fresh fruits', 1, 2),
('Halal Meat', 'halal-meat', 'Certified halal meat products', 1, 3),
('Dairy & Eggs', 'dairy-eggs', 'Fresh dairy products and eggs', 1, 4),
('Pantry', 'pantry', 'Rice, oil, and cooking essentials', 1, 5),
('Snacks', 'snacks', 'Healthy snacks and treats', 1, 6),
('Beverages', 'beverages', 'Drinks and beverages', 1, 7),
('Frozen Foods', 'frozen-foods', 'Frozen vegetables and ready meals', 1, 8);

-- =======================================================
-- 3. CREATE SAMPLE PRODUCTS
-- =======================================================

-- Vegetables (5 products)
INSERT INTO products (category_id, name, slug, description, short_description, price, sale_price, sku, quantity, min_quantity, weight, unit, is_halal, is_featured, is_active) VALUES
(1, 'Fresh Tomatoes', 'fresh-tomatoes', 'Juicy red tomatoes perfect for salads and cooking', 'Fresh organic tomatoes', 3.50, 2.99, 'VEG-TOM-001', 100, 10, 1.00, 'kg', 1, 1, 1),
(1, 'Green Cabbage', 'green-cabbage', 'Fresh green cabbage, locally grown', 'Crisp green cabbage', 2.50, NULL, 'VEG-CAB-001', 80, 10, 1.50, 'kg', 1, 0, 1),
(1, 'Carrots', 'carrots', 'Sweet orange carrots, rich in vitamins', 'Fresh organic carrots', 2.00, 1.80, 'VEG-CAR-001', 120, 15, 0.50, 'kg', 1, 1, 1),
(1, 'Spinach', 'spinach', 'Fresh spinach leaves, perfect for salads', 'Organic spinach', 4.00, NULL, 'VEG-SPI-001', 60, 10, 0.30, 'kg', 1, 0, 1),
(1, 'Onions', 'onions', 'Yellow onions, essential cooking ingredient', 'Fresh yellow onions', 1.80, 1.50, 'VEG-ONI-001', 150, 20, 1.00, 'kg', 1, 0, 1);

-- Fruits (4 products)
INSERT INTO products (category_id, name, slug, description, short_description, price, sale_price, sku, quantity, min_quantity, weight, unit, is_halal, is_featured, is_active) VALUES
(2, 'Japanese Apples', 'japanese-apples', 'Sweet and crispy Japanese apples', 'Premium Japanese apples', 6.00, 5.50, 'FRU-APP-001', 80, 10, 0.20, 'piece', 1, 1, 1),
(2, 'Fresh Bananas', 'fresh-bananas', 'Ripe bananas, perfect for snacking', 'Sweet bananas', 2.50, NULL, 'FRU-BAN-001', 100, 15, 0.15, 'piece', 1, 1, 1),
(2, 'Strawberries', 'strawberries', 'Sweet Japanese strawberries', 'Fresh strawberries', 8.00, 7.50, 'FRU-STR-001', 40, 5, 0.25, 'pack', 1, 1, 1),
(2, 'Oranges', 'oranges', 'Juicy oranges packed with vitamin C', 'Fresh oranges', 4.50, NULL, 'FRU-ORA-001', 90, 10, 0.25, 'piece', 1, 0, 1);

-- Halal Meat (3 products)
INSERT INTO products (category_id, name, slug, description, short_description, price, sale_price, sku, quantity, min_quantity, weight, unit, is_halal, is_featured, is_active) VALUES
(3, 'Halal Chicken Breast', 'halal-chicken-breast', 'Certified halal chicken breast, boneless', 'Premium halal chicken', 12.00, 10.50, 'MEAT-CHI-001', 50, 5, 1.00, 'kg', 1, 1, 1),
(3, 'Halal Beef Steak', 'halal-beef-steak', 'Premium halal beef steak cuts', 'Quality beef steak', 25.00, NULL, 'MEAT-BEF-001', 30, 5, 0.50, 'kg', 1, 1, 1),
(3, 'Halal Lamb Chops', 'halal-lamb-chops', 'Tender halal lamb chops', 'Fresh lamb chops', 18.00, 16.50, 'MEAT-LAM-001', 25, 5, 0.50, 'kg', 1, 0, 1);

-- Dairy & Eggs (4 products)
INSERT INTO products (category_id, name, slug, description, short_description, price, sale_price, sku, quantity, min_quantity, weight, unit, is_halal, is_featured, is_active) VALUES
(4, 'Fresh Milk 1L', 'fresh-milk-1l', 'Fresh whole milk, daily delivery', 'Fresh whole milk', 3.00, NULL, 'DAI-MIL-001', 120, 15, 1.00, 'liter', 1, 1, 1),
(4, 'Free Range Eggs (10pcs)', 'free-range-eggs', 'Free range chicken eggs, premium quality', 'Farm fresh eggs', 5.50, 4.99, 'DAI-EGG-001', 80, 10, 0.60, 'pack', 1, 1, 1),
(4, 'Greek Yogurt', 'greek-yogurt', 'Creamy Greek yogurt, high protein', 'Healthy yogurt', 6.00, NULL, 'DAI-YOG-001', 60, 10, 0.50, 'kg', 1, 0, 1),
(4, 'Butter Unsalted', 'butter-unsalted', 'Premium unsalted butter for cooking', 'Pure butter', 7.50, 7.00, 'DAI-BUT-001', 40, 5, 0.25, 'kg', 1, 0, 1);

-- Pantry Items (3 products)
INSERT INTO products (category_id, name, slug, description, short_description, price, sale_price, sku, quantity, min_quantity, weight, unit, is_halal, is_featured, is_active) VALUES
(5, 'Japanese Rice 5kg', 'japanese-rice-5kg', 'Premium Japanese short grain rice', 'Premium rice', 15.00, 13.50, 'PAN-RIC-001', 100, 10, 5.00, 'kg', 1, 1, 1),
(5, 'Olive Oil 1L', 'olive-oil-1l', 'Extra virgin olive oil, cold pressed', 'Pure olive oil', 12.00, NULL, 'PAN-OIL-001', 70, 10, 1.00, 'liter', 1, 0, 1),
(5, 'Soy Sauce 500ml', 'soy-sauce-500ml', 'Authentic Japanese soy sauce', 'Quality soy sauce', 4.50, 3.99, 'PAN-SOY-001', 90, 15, 0.50, 'liter', 1, 0, 1);

-- =======================================================
-- 4. SETUP INVENTORY DATABASE
-- =======================================================
USE adiari_inventory;

-- Create warehouse
INSERT INTO warehouse (name, code, address, city, postal_code, country, phone, email, manager_name, is_active) VALUES
('ADI ARI Main Warehouse', 'WH-MAIN-001', '114-0031 Higashi Tabata 2-3-1 Otsu building 101', 'Tokyo', '114-0031', 'Japan', '080-3408-8044', 'warehouse@adiarifresh.com', 'Manager User', 1);

-- Create product stock records for all products
INSERT INTO product_stock (product_id, warehouse_id, quantity, reserved_quantity, reorder_level, reorder_quantity) VALUES
-- Vegetables
(1, 1, 100, 0, 10, 100),
(2, 1, 80, 0, 10, 80),
(3, 1, 120, 0, 15, 120),
(4, 1, 60, 0, 10, 60),
(5, 1, 150, 0, 20, 150),
-- Fruits
(6, 1, 80, 0, 10, 80),
(7, 1, 100, 0, 15, 100),
(8, 1, 40, 0, 5, 40),
(9, 1, 90, 0, 10, 90),
-- Halal Meat
(10, 1, 50, 0, 5, 50),
(11, 1, 30, 0, 5, 30),
(12, 1, 25, 0, 5, 25),
-- Dairy
(13, 1, 120, 0, 15, 120),
(14, 1, 80, 0, 10, 80),
(15, 1, 60, 0, 10, 60),
(16, 1, 40, 0, 5, 40),
-- Pantry
(17, 1, 100, 0, 10, 100),
(18, 1, 70, 0, 10, 70),
(19, 1, 90, 0, 15, 90);

-- =======================================================
-- SEED DATA COMPLETE
-- =======================================================
-- Total Users: 3 (1 admin, 1 manager, 1 customer)
-- Total Categories: 8
-- Total Products: 19
-- Total Warehouses: 1
-- Total Stock Records: 19
-- =======================================================
