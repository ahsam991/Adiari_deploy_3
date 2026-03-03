<?php
/**
 * Currency Helper Functions
 * 
 * Handles currency formatting and calculations for Japanese Yen
 */

if (!function_exists('formatCurrency')) {
    function formatCurrency($amount) {
        return '¥' . number_format($amount, 0);
    }
}

if (!function_exists('convertToYen')) {
    function convertToYen($usd, $rate = 150) {
        return round($usd * $rate);
    }
}

if (!function_exists('calculateVAT')) {
    function calculateVAT($amount, $rate) {
        return round(($amount * $rate) / 100);
    }
}

if (!function_exists('calculateDiscount')) {
    function calculateDiscount($amount, $type, $value) {
        if ($type === 'percentage') {
            return round(($amount * $value) / 100);
        } else {
            return $value;
        }
    }
}

if (!function_exists('calculatePriceWithVAT')) {
    function calculatePriceWithVAT($subtotal, $vatRate) {
        $vat = calculateVAT($subtotal, $vatRate);
        $total = $subtotal + $vat;
        return ['vat' => $vat, 'total' => $total];
    }
}

if (!function_exists('parseCurrency')) {
    function parseCurrency($input) {
        $cleaned = str_replace(['¥', ',', ' '], '', $input);
        return floatval($cleaned);
    }
}

if (!function_exists('formatNumber')) {
    function formatNumber($number) {
        return number_format($number, 0);
    }
}
