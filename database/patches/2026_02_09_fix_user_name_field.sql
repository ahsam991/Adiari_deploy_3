-- Fix the 'name' field issue in users table
-- Make it nullable and add a trigger to auto-populate from first_name and last_name

-- Make name field nullable
ALTER TABLE users MODIFY COLUMN name VARCHAR(255) DEFAULT NULL;

-- Update existing records to populate name from first_name and last_name
UPDATE users 
SET name = CONCAT(COALESCE(first_name, ''), ' ', COALESCE(last_name, ''))
WHERE name IS NULL OR name = '';

-- Create a trigger to auto-populate name field on insert
DROP TRIGGER IF EXISTS users_before_insert;

DELIMITER $$
CREATE TRIGGER users_before_insert
BEFORE INSERT ON users
FOR EACH ROW
BEGIN
    IF NEW.name IS NULL OR NEW.name = '' THEN
        SET NEW.name = CONCAT(COALESCE(NEW.first_name, ''), ' ', COALESCE(NEW.last_name, ''));
    END IF;
END$$
DELIMITER ;

-- Create a trigger to auto-update name field on update
DROP TRIGGER IF EXISTS users_before_update;

DELIMITER $$
CREATE TRIGGER users_before_update
BEFORE UPDATE ON users
FOR EACH ROW
BEGIN
    IF (NEW.first_name != OLD.first_name OR NEW.last_name != OLD.last_name) THEN
        SET NEW.name = CONCAT(COALESCE(NEW.first_name, ''), ' ', COALESCE(NEW.last_name, ''));
    END IF;
END$$
DELIMITER ;
