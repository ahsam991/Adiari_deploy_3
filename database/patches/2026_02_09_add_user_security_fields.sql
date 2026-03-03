-- Add security and tracking fields to users table
-- This patch adds login attempt tracking, account lockout, and other security features

ALTER TABLE users
  ADD COLUMN IF NOT EXISTS first_name VARCHAR(100) DEFAULT NULL AFTER id,
  ADD COLUMN IF NOT EXISTS last_name VARCHAR(100) DEFAULT NULL AFTER first_name,
  ADD COLUMN IF NOT EXISTS status VARCHAR(20) DEFAULT 'active' AFTER role,
  ADD COLUMN IF NOT EXISTS login_attempts INT DEFAULT 0 AFTER status,
  ADD COLUMN IF NOT EXISTS lockout_until DATETIME DEFAULT NULL AFTER login_attempts,
  ADD COLUMN IF NOT EXISTS last_login_at DATETIME DEFAULT NULL AFTER lockout_until,
  ADD COLUMN IF NOT EXISTS last_login_ip VARCHAR(45) DEFAULT NULL AFTER last_login_at,
  ADD COLUMN IF NOT EXISTS email_verified_at DATETIME DEFAULT NULL AFTER email_verified,
  ADD COLUMN IF NOT EXISTS email_verification_token VARCHAR(100) DEFAULT NULL AFTER email_verified_at,
  ADD COLUMN IF NOT EXISTS password_reset_token VARCHAR(100) DEFAULT NULL AFTER email_verification_token,
  ADD COLUMN IF NOT EXISTS password_reset_expires DATETIME DEFAULT NULL AFTER password_reset_token;

-- Migrate existing data
UPDATE users SET first_name = SUBSTRING_INDEX(name, ' ', 1) WHERE first_name IS NULL AND name IS NOT NULL;
UPDATE users SET last_name = SUBSTRING_INDEX(name, ' ', -1) WHERE last_name IS NULL AND name IS NOT NULL;
UPDATE users SET status = IF(is_active = 1, 'active', 'inactive') WHERE status IS NULL OR status = '';
UPDATE users SET email_verified_at = created_at WHERE email_verified = 1 AND email_verified_at IS NULL;
UPDATE users SET last_login_at = last_login WHERE last_login_at IS NULL AND last_login IS NOT NULL;

-- Add indexes for performance
CREATE INDEX IF NOT EXISTS idx_lockout_until ON users(lockout_until);
CREATE INDEX IF NOT EXISTS idx_status ON users(status);
CREATE INDEX IF NOT EXISTS idx_password_reset_token ON users(password_reset_token);
