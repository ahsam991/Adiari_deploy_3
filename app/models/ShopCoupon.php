<?php
/**
 * Shop Coupon Model (POS System)
 * Adapted from adiari_shopping-main for unified database
 * Note: Uses the same 'coupons' table but with MySQL DATE functions
 */

class ShopCoupon {
    private $db;
    private $table = 'coupons';
    
    public function __construct() {
        $this->db = Database::getConnection('grocery');
    }
    
    public function validateCoupon($code, $total) {
        try {
            $query = "SELECT * FROM {$this->table} 
                     WHERE code = :code 
                     AND is_active = 1 
                     AND (valid_from IS NULL OR valid_from <= NOW())
                     AND (valid_until IS NULL OR valid_until >= NOW())
                     AND (usage_limit = 0 OR times_used < usage_limit)
                     LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':code', $code);
            $stmt->execute();
            $coupon = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$coupon) {
                return ['valid' => false, 'message' => 'Invalid or expired coupon code'];
            }
            
            $minPurchase = $coupon['min_purchase_amount'] ?? $coupon['min_purchase'] ?? 0;
            if ($minPurchase > 0 && $total < $minPurchase) {
                return ['valid' => false, 'message' => 'Minimum purchase of ¥' . number_format($minPurchase, 0) . ' required'];
            }
            
            if ($coupon['discount_type'] === 'percentage') {
                $discount = ($total * $coupon['discount_value']) / 100;
                $maxDiscount = $coupon['max_discount_amount'] ?? $coupon['max_discount'] ?? 0;
                if ($maxDiscount > 0 && $discount > $maxDiscount) {
                    $discount = $maxDiscount;
                }
            } else {
                $discount = $coupon['discount_value'];
            }
            
            return [
                'valid' => true,
                'coupon' => $coupon,
                'discount' => $discount,
                'message' => 'Coupon applied successfully'
            ];
        } catch (PDOException $e) {
            return ['valid' => false, 'message' => 'Error validating coupon'];
        }
    }
    
    public function applyCoupon($couponId) {
        try {
            $query = "UPDATE {$this->table} SET times_used = times_used + 1 WHERE coupon_id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $couponId);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function getAllCoupons() {
        try {
            $query = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
            $stmt = $this->db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
    
    public function createCoupon($data) {
        try {
            $query = "INSERT INTO {$this->table} 
                     (code, description, discount_type, discount_value, min_purchase_amount, 
                      max_discount_amount, valid_from, valid_until, usage_limit, is_active) 
                     VALUES (:code, :description, :discount_type, :discount_value, :min_purchase,
                             :max_discount, :valid_from, :valid_until, :usage_limit, :is_active)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':code', $data['code']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':discount_type', $data['discount_type']);
            $stmt->bindParam(':discount_value', $data['discount_value']);
            $stmt->bindParam(':min_purchase', $data['min_purchase']);
            $stmt->bindParam(':max_discount', $data['max_discount']);
            $stmt->bindParam(':valid_from', $data['valid_from']);
            $stmt->bindParam(':valid_until', $data['valid_until']);
            $stmt->bindParam(':usage_limit', $data['usage_limit']);
            $stmt->bindParam(':is_active', $data['is_active']);
            if ($stmt->execute()) return $this->db->lastInsertId();
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }
}
