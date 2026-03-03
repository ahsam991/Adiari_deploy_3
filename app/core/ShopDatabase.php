<?php
/**
 * Shop Database Bridge
 * Provides a PDO connection compatible with the shopping project's Database singleton
 * This bridges the admin dashboard's Database class with the shopping project's models
 */

class ShopDatabase {
    private static $instance = null;
    private $connection;

    private function __construct() {
        // Use the main Database class to get a connection to the 'shop' config
        // which points to the same Hostinger database
        $this->connection = Database::getConnection('grocery');
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

    private function __clone() {}

    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}
