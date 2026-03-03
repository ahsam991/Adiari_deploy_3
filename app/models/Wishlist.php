<?php
/**
 * Wishlist Model
 */

require_once __DIR__ . '/../core/Model.php';

class Wishlist extends Model {
    protected $table = 'wishlist';
    protected $db = 'grocery';
    protected $timestamps = false;
    protected $fillable = ['user_id', 'product_id'];

    public function getUserWishlist($userId) {
        $query = "SELECT w.*, p.name, p.slug, p.primary_image, p.price, p.sale_price, p.status
                  FROM {$this->table} w
                  INNER JOIN products p ON w.product_id = p.id
                  WHERE w.user_id = ? AND p.deleted_at IS NULL
                  ORDER BY w.created_at DESC";
        return Database::fetchAll($query, [$userId], $this->db);
    }

    public function add($userId, $productId) {
        $exists = $this->findBy(['user_id' => $userId, 'product_id' => $productId]);
        if ($exists) return true;
        return $this->create(['user_id' => $userId, 'product_id' => $productId]);
    }

    public function remove($userId, $productId) {
        $query = "DELETE FROM {$this->table} WHERE user_id = ? AND product_id = ?";
        Database::query($query, [$userId, $productId], $this->db);
        return true;
    }
}
