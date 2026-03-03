<?php
/**
 * Setting Model
 * Handles site-wide settings stored in the settings table
 */

require_once __DIR__ . '/../core/Model.php';

class Setting extends Model {
    protected $table = 'settings';
    protected $db = 'grocery';
    protected $fillable = ['setting_key', 'setting_value', 'setting_type', 'description'];

    /**
     * Get a setting value by key
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, $default = null) {
        $result = Database::fetchOne(
            "SELECT setting_value, setting_type FROM settings WHERE setting_key = ? LIMIT 1",
            [$key],
            'grocery'
        );

        if (!$result) return $default;

        // Cast based on type
        switch ($result['setting_type']) {
            case 'number':
                return (float) $result['setting_value'];
            case 'boolean':
                return (bool) $result['setting_value'];
            case 'json':
                return json_decode($result['setting_value'], true);
            default:
                return $result['setting_value'];
        }
    }

    /**
     * Set a setting value
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public static function set($key, $value) {
        $existing = Database::fetchOne(
            "SELECT id FROM settings WHERE setting_key = ? LIMIT 1",
            [$key],
            'grocery'
        );

        if ($existing) {
            Database::query(
                "UPDATE settings SET setting_value = ?, updated_at = NOW() WHERE setting_key = ?",
                [(string) $value, $key],
                'grocery'
            );
        } else {
            Database::query(
                "INSERT INTO settings (setting_key, setting_value, created_at, updated_at) VALUES (?, ?, NOW(), NOW())",
                [$key, (string) $value],
                'grocery'
            );
        }

        return true;
    }

    /**
     * Get all settings as key => value array
     * @return array
     */
    public static function getAll() {
        $rows = Database::fetchAll("SELECT * FROM settings ORDER BY setting_key", [], 'grocery');
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['setting_key']] = $row;
        }
        return $settings;
    }

    /**
     * Get the effective tax rate for a product
     * If the product has a custom tax_rate, use that. Otherwise, use the global rate.
     * @param array $product Product record
     * @return float Tax rate as a percentage (e.g. 10 = 10%)
     */
    public static function getProductTaxRate($product) {
        // If product has a custom tax rate set
        if (isset($product['tax_rate']) && $product['tax_rate'] !== null && $product['tax_rate'] !== '') {
            return (float) $product['tax_rate'];
        }
        // Fallback to global rate
        return self::get('global_tax_rate', 10);
    }

    /**
     * Calculate tax amount from a subtotal
     * @param float $subtotal
     * @param float $taxRate Tax rate percentage
     * @param bool $inclusive Whether tax is included in the price
     * @return float
     */
    public static function calculateTax($subtotal, $taxRate = null, $inclusive = null) {
        $enabled = self::get('tax_enabled', true);
        if (!$enabled) return 0;

        if ($taxRate === null) {
            $taxRate = self::get('global_tax_rate', 10);
        }
        if ($inclusive === null) {
            $inclusive = self::get('tax_included_in_price', true);
        }

        if ($inclusive) {
            // Tax is already included in the price
            // Extract tax: price / (1 + rate/100) * (rate/100)
            return round($subtotal / (1 + $taxRate / 100) * ($taxRate / 100), 2);
        } else {
            // Tax is added on top
            return round($subtotal * ($taxRate / 100), 2);
        }
    }
}
