-- ============================================
-- LOCALHOST QUICK SETUP SCRIPT
-- ADI ARI Fresh - Grocery Ecommerce
-- ============================================
-- 
-- This script creates the necessary databases
-- for local development (XAMPP/MAMP)
--
-- Run this in phpMyAdmin on localhost
-- ============================================

-- Create databases
CREATE DATABASE IF NOT EXISTS `adiari_grocery` 
  DEFAULT CHARACTER SET utf8mb4 
  COLLATE utf8mb4_unicode_ci;

CREATE DATABASE IF NOT EXISTS `adiari_inventory` 
  DEFAULT CHARACTER SET utf8mb4 
  COLLATE utf8mb4_unicode_ci;

CREATE DATABASE IF NOT EXISTS `adiari_analytics` 
  DEFAULT CHARACTER SET utf8mb4 
  COLLATE utf8mb4_unicode_ci;

-- Switch to main database
USE `adiari_grocery`;

-- Message
SELECT 'Databases created successfully! Now import the main schema...' AS message;
SELECT 'Go to phpMyAdmin -> adiari_grocery -> Import -> database/unified_hostinger_setup.sql' AS next_step;
