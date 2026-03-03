<?php
/**
 * Logger Helper Class
 * Handles application logging (both file-based and database)
 */

class Logger {
    private static $logPath = __DIR__ . '/../../logs/';
    private static $useDatabase = true; // Toggle database logging

    /**
     * Log info message
     * @param string $message Log message
     * @param array $context Additional context
     */
    public static function info($message, $context = []) {
        self::log('INFO', $message, $context);
    }

    /**
     * Log error message
     * @param string $message Log message
     * @param array $context Additional context
     */
    public static function error($message, $context = []) {
        self::log('ERROR', $message, $context);
    }

    /**
     * Log warning message
     * @param string $message Log message
     * @param array $context Additional context
     */
    public static function warning($message, $context = []) {
        self::log('WARNING', $message, $context);
    }

    /**
     * Log debug message
     * @param string $message Log message
     * @param array $context Additional context
     */
    public static function debug($message, $context = []) {
        self::log('DEBUG', $message, $context);
    }

    /**
     * Log user activity (both file and database)
     * @param int $userId User ID
     * @param string $action Action performed
     * @param array $details Additional details
     */
    public static function activity($userId, $action, $details = []) {
        $message = "User {$userId} performed: {$action}";
        self::log('ACTIVITY', $message, $details, 'activity.log');
        
        // Also log to database if enabled
        if (self::$useDatabase) {
            self::logToDatabase($userId, $action, $details);
        }
    }

    /**
     * Log activity to database
     * @param int $userId User ID
     * @param string $activityType Activity type
     * @param array $metadata Additional metadata
     */
    private static function logToDatabase($userId, $activityType, $metadata = []) {
        try {
            $db = Database::getConnection('analytics');
            
            $pageUrl = $_SERVER['REQUEST_URI'] ?? null;
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
            $sessionId = session_id();
            
            $stmt = $db->prepare("
                INSERT INTO user_activity 
                (user_id, activity_type, page_url, ip_address, user_agent, session_id, metadata) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            
            $metadataJson = json_encode($metadata);
            $stmt->execute([
                $userId,
                $activityType,
                $pageUrl,
                $ipAddress,
                $userAgent,
                $sessionId,
                $metadataJson
            ]);
        } catch (Exception $e) {
            // Silently fail - don't break app if logging fails
            self::error('Failed to log to database: ' . $e->getMessage());
        }
    }

    /**
     * Get recent activity logs from database
     * @param int $limit Number of logs to retrieve
     * @param int|null $userId Filter by user ID
     * @return array Activity logs
     */
    public static function getRecentActivity($limit = 100, $userId = null) {
        try {
            $db = Database::getConnection('analytics');
            
            $sql = "SELECT * FROM user_activity";
            if ($userId) {
                $sql .= " WHERE user_id = ?";
            }
            $sql .= " ORDER BY created_at DESC LIMIT ?";
            
            $stmt = $db->prepare($sql);
            if ($userId) {
                $stmt->execute([$userId, $limit]);
            } else {
                $stmt->execute([$limit]);
            }
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            self::error('Failed to retrieve activity logs: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Write  to log file
     * @param string $level Log level
     * @param string $message Log message
     * @param array $context Additional context
     * @param string $filename Log filename
     */
    private static function log($level, $message, $context = [], $filename = 'app.log') {
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? json_encode($context) : '';
        
        $logMessage = "[{$timestamp}] [{$level}] {$message}";
        if ($contextStr) {
            $logMessage .= " | Context: {$contextStr}";
        }
        $logMessage .= PHP_EOL;

        // Ensure log directory exists
        if (!is_dir(self::$logPath)) {
            mkdir(self::$logPath, 0755, true);
        }

        $logFile = self::$logPath . $filename;
        file_put_contents($logFile, $logMessage, FILE_APPEND);

        // Rotate log if too large (> 10MB)
        if (file_exists($logFile) && filesize($logFile) > 10485760) {
            rename($logFile, $logFile . '.' . date('Y-m-d-H-i-s') . '.bak');
        }
    }
}
