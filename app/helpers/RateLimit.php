<?php
/**
 * Rate Limiter Helper
 * Prevents brute force attacks and spam
 */

class RateLimit {
    /**
     * Check if user has exceeded rate limit for an action
     * @param string $action Action identifier (e.g., 'login', 'register')
     * @param string $identifier Unique identifier (IP or email)
     * @param int $maxAttempts Maximum allowed attempts
     * @param int $windowSeconds Time window in seconds
     * @return array Result with 'allowed' bool and 'remaining' count
     */
    public static function check($action, $identifier, $maxAttempts = 5, $windowSeconds = 900) {
        $key = "ratelimit_{$action}_{$identifier}";
        
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = [
                'attempts' => 0,
                'first_attempt' => time()
            ];
        }

        $data = $_SESSION[$key];
        $elapsed = time() - $data['first_attempt'];

        // Reset if window has passed
        if ($elapsed > $windowSeconds) {
            $_SESSION[$key] = [
                'attempts' => 0,
                'first_attempt' => time()
            ];
            return [
                'allowed' => true,
                'remaining' => $maxAttempts
            ];
        }

        // Check if limit exceeded
        $allowed = $data['attempts'] < $maxAttempts;
        $remaining = max(0, $maxAttempts - $data['attempts']);

        return [
            'allowed' => $allowed,
            'remaining' => $remaining,
            'reset_in' => $windowSeconds - $elapsed
        ];
    }

    /**
     * Record an attempt
     * @param string $action Action identifier
     * @param string $identifier Unique identifier
     */
    public static function recordAttempt($action, $identifier) {
        $key = "ratelimit_{$action}_{$identifier}";
        
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = [
                'attempts' => 0,
                'first_attempt' => time()
            ];
        }

        $_SESSION[$key]['attempts']++;
    }

    /**
     * Reset rate limit
     * @param string $action Action identifier
     * @param string $identifier Unique identifier
     */
    public static function reset($action, $identifier) {
        $key = "ratelimit_{$action}_{$identifier}";
        unset($_SESSION[$key]);
    }
}
?>
