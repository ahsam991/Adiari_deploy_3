<?php
/**
 * Security Helper Class
 * Handles security-related functions
 */

class Security {
    /**
     * Generate CSRF token
     * @return string
     */
    public static function generateCsrfToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Validate CSRF token
     * @param string $token Token to validate
     * @return bool
     */
    public static function validateCsrfToken($token) {
        // Check if session token exists
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }
        
        // Check if provided token is valid (not null or empty)
        if (empty($token) || !is_string($token)) {
            return false;
        }
        
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Get CSRF token field HTML
     * @return string
     */
    public static function getCsrfField() {
        $token = self::generateCsrfToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }

    /**
     * Get CSRF token value
     * @return string
     */
    public static function getCsrfToken() {
        return self::generateCsrfToken();
    }


    /**
     * Hash password
     * @param string $password Plain text password
     * @return string
     */
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    /**
     * Verify password
     * @param string $password Plain text password
     * @param string $hash Hashed password
     * @return bool
     */
    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    /**
     * Sanitize input to prevent XSS
     * @param string $input Input string
     * @return string
     */
    public static function sanitize($input) {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Sanitize array of inputs
     * @param array $data Input data
     * @return array
     */
    public static function sanitizeArray($data) {
        $sanitized = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = self::sanitizeArray($value);
            } else {
                $sanitized[$key] = self::sanitize($value);
            }
        }
        return $sanitized;
    }

    /**
     * Generate random token
     * @param int $length Token length
     * @return string
     */
    public static function generateToken($length = 32) {
        return bin2hex(random_bytes($length));
    }

    /**
     * Validate file upload
     * @param array $file $_FILES array element
     * @param array $allowedTypes Allowed MIME types
     * @param int $maxSize Max file size in bytes
     * @return bool|string True if valid, error message otherwise
     */
    public static function validateFileUpload($file, $allowedTypes = [], $maxSize = 5242880) {
        // Check if file was uploaded
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return 'File upload failed';
        }

        // Check file size
        if ($file['size'] > $maxSize) {
            return 'File is too large (max ' . ($maxSize / 1024 / 1024) . 'MB)';
        }

        // Check MIME type
        if (!empty($allowedTypes)) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            // Note: finfo resources are automatically freed in PHP 7.4+ and 8.x
            // No need to call finfo_close() explicitly

            if (!in_array($mimeType, $allowedTypes)) {
                return 'File type not allowed';
            }
        }

        return true;
    }

    /**
     * Prevent SQL injection by escaping string
     * Note: Use prepared statements instead when possible
     * @param string $string String to escape
     * @return string
     */
    public static function escapeString($string) {
        return addslashes($string);
    }

    /**
     * Generate secure filename
     * @param string $originalName Original filename
     * @return string
     */
    public static function generateSecureFilename($originalName) {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $filename = bin2hex(random_bytes(16));
        return $filename . '.' . $extension;
    }
}
