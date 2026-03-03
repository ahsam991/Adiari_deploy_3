<?php
/**
 * Offer/Deal Model
 */

require_once __DIR__ . '/../core/Model.php';

class Offer extends Model {
    protected $table = 'offers';
    protected $db = 'grocery';
    protected $fillable = ['product_id', 'discount_type', 'discount_value', 'start_date', 'end_date', 'status'];

    /**
     * Get all active offers
     */
    public function getActiveOffers() {
        $today = date('Y-m-d');
        $query = "SELECT o.*, 
                         p.name as product_name, 
                         p.slug as product_slug,
                         p.price as original_price,
                         p.primary_image,
                         p.status as product_status,
                         CASE 
                            WHEN o.discount_type = 'percentage' THEN p.price - (p.price * o.discount_value / 100)
                            ELSE p.price - o.discount_value
                         END as discounted_price
                  FROM {$this->table} o
                  INNER JOIN products p ON o.product_id = p.id
                  WHERE o.status = 'active' 
                    AND o.start_date <= ?
                    AND o.end_date >= ?
                    AND p.deleted_at IS NULL
                    AND p.status = 'active'
                  ORDER BY o.created_at DESC";
        
        return Database::fetchAll($query, [$today, $today], $this->db);
    }

    /**
     * Get all offers with product details for admin
     */
    public function getAllOffersForAdmin() {
        $query = "SELECT o.*, 
                         p.name as product_name, 
                         p.slug as product_slug,
                         p.price as original_price,
                         p.primary_image
                  FROM {$this->table} o
                  INNER JOIN products p ON o.product_id = p.id
                  WHERE p.deleted_at IS NULL
                  ORDER BY o.created_at DESC";
        
        return Database::fetchAll($query, [], $this->db);
    }

    /**
     * Create new offer
     */
    public function createOffer($data) {
        return $this->create([
            'product_id' => $data['product_id'],
            'discount_type' => $data['discount_type'],
            'discount_value' => $data['discount_value'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'status' => $data['status'] ?? 'active'
        ]);
    }

    /**
     * Update offer
     */
    public function updateOffer($id, $data) {
        return $this->update($id, [
            'product_id' => $data['product_id'],
            'discount_type' => $data['discount_type'],
            'discount_value' => $data['discount_value'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'status' => $data['status']
        ]);
    }

    /**
     * Update expired offers
     */
    public function updateExpiredOffers() {
        $today = date('Y-m-d');
        $query = "UPDATE {$this->table} 
                  SET status = 'expired' 
                  WHERE end_date < ? AND status = 'active'";
        
        return Database::query($query, [$today], $this->db);
    }

    /**
     * Check if product has active offer
     */
    public function hasActiveOffer($productId) {
        $today = date('Y-m-d');
        $query = "SELECT * FROM {$this->table} 
                  WHERE product_id = ? 
                    AND status = 'active'
                    AND start_date <= ?
                    AND end_date >= ?";
        
                return Database::fetchOne($query, [$productId, $today, $today], $this->db);
    }
}
