<?php
/**
 * Session Helper Class
 * Manages session operations
 */

class Session {
    /**
     * Set session value
     * @param string $key Key name
     * @param mixed $value Value
     */
    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    /**
     * Get session value
     * @param string $key Key name
     * @param mixed $default Default value if key doesn't exist
     * @return mixed
     */
    public static function get($key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Check if session key exists
     * @param string $key Key name
     * @return bool
     */
    public static function has($key) {
        return isset($_SESSION[$key]);
    }

    /**
     * Check if user is logged in
     * @return bool
     */
    public static function isLoggedIn() {
        return !empty($_SESSION['user_id']) && !empty($_SESSION['logged_in']);
    }

    /**
     * Remove session key
     * @param string $key Key name
     */
    public static function remove($key) {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Clear all session data
     */
    public static function clear() {
        session_unset();
    }

    /**
     * Destroy session
     */
    public static function destroy() {
        session_destroy();
    }

    /**
     * Set flash message
     * @param string $type Message type (success, error, warning, info)
     * @param mixed $message Message text (string or array)
     */
    public static function setFlash(string $type, mixed $message): void {
        if (!isset($_SESSION['flash'])) {
            $_SESSION['flash'] = [];
        }
        $_SESSION['flash'][$type] = $message;
    }

    /**
     * Get flash message
     * @param string $type Message type
     * @return mixed|null (string or array)
     */
    public static function getFlash(string $type): mixed {
        if (isset($_SESSION['flash'][$type])) {
            $message = $_SESSION['flash'][$type];
            unset($_SESSION['flash'][$type]);
            return $message;
        }
        return null;
    }

    /**
     * Get all flash messages
     * @return array
     */
    public static function getAllFlash() {
        if (isset($_SESSION['flash'])) {
            $messages = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $messages;
        }
        return [];
    }

    /**
     * Check if flash message exists
     * @param string $type Message type
     * @return bool
     */
    public static function hasFlash($type = null) {
        if ($type === null) {
            return isset($_SESSION['flash']) && !empty($_SESSION['flash']);
        }
        return isset($_SESSION['flash'][$type]);
    }

    /**
     * Regenerate session ID
     */
    public static function regenerate() {
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    }
}
