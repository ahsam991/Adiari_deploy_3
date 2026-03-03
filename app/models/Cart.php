<?php
/**
 * Cart Model
 * Handles shopping cart operations
 */

require_once __DIR__ . '/../core/Model.php';

class Cart extends Model {
    protected $table = 'cart';
    protected $db = 'grocery';
    protected $fillable = ['user_id', 'product_id', 'quantity', 'price'];
    protected $timestamps = false;

    /**
     * Get user cart items
     * @param int $userId User ID
     * @return array
     */
    public function getUserCart($userId) {
        $query = "SELECT c.*, p.name, p.slug, p.primary_image, p.stock_quantity, p.status,
                         (c.quantity * c.price) as item_total
                  FROM {$this->table} c
                  INNER JOIN products p ON c.product_id = p.id
                  WHERE c.user_id = ? 
                  AND p.deleted_at IS NULL
                  ORDER BY c.created_at DESC";
        
        return Database::fetchAll($query, [$userId], $this->db);
    }

    /**
     * Add item to cart
     * @param int $userId User ID
     * @param int $productId Product ID
     * @param int $quantity Quantity
     * @param float $price Price
     * @return bool
     */
    public function addItem($userId, $productId, $quantity, $price) {
        // Check if item already exists in cart
        $existing = $this->findBy(['user_id' => $userId, 'product_id' => $productId]);

        if ($existing) {
            // Update quantity
            return $this->updateItemQuantity($userId, $productId, $existing['quantity'] + $quantity);
        }

        // Create new cart item
        return $this->create([
            'user_id' => $userId,
            'product_id' => $productId,
            'quantity' => $quantity,
            'price' => $price
        ]);
    }

    /**
     * Update item quantity
     * @param int $userId User ID
     * @param int $productId Product ID
     * @param int $quantity New quantity
     * @return bool
     */
    public function updateItemQuantity($userId, $productId, $quantity) {
        if ($quantity <= 0) {
            return $this->removeItem($userId, $productId);
        }

        $query = "UPDATE {$this->table} 
                  SET quantity = ?, updated_at = NOW()
                  WHERE user_id = ? AND product_id = ?";
        
        Database::query($query, [$quantity, $userId, $productId], $this->db);
        return true;
    }

    /**
     * Remove item from cart
     * @param int $userId User ID
     * @param int $productId Product ID
     * @return bool
     */
    public function removeItem($userId, $productId) {
        $query = "DELETE FROM {$this->table} 
                  WHERE user_id = ? AND product_id = ?";
        
        Database::query($query, [$userId, $productId], $this->db);
        return true;
    }

    /**
     * Clear user cart
     * @param int $userId User ID
     */
    public function clearCart($userId) {
        $query = "DELETE FROM {$this->table} WHERE user_id = ?";
        Database::query($query, [$userId], $this->db);
    }

    /**
     * Get cart totals
     * @param int $userId User ID
     * @return array
     */
    public function getCartTotals($userId) {
        $query = "SELECT 
                    COUNT(*) as item_count,
                    SUM(quantity) as total_quantity,
                    SUM(quantity * price) as subtotal
                  FROM {$this->table}
                  WHERE user_id = ?";
        
        $result = Database::fetchOne($query, [$userId], $this->db);
        
        return [
            'item_count' => (int)($result['item_count'] ?? 0),
            'total_quantity' => (int)($result['total_quantity'] ?? 0),
            'subtotal' => (float)($result['subtotal'] ?? 0)
        ];
    }

    /**
     * Get cart item count
     * @param int $userId User ID
     * @return int
     */
    public function getCartCount($userId) {
        $totals = $this->getCartTotals($userId);
        return $totals['item_count'];
    }

    /**
     * Validate cart items (check stock and availability)
     * @param int $userId User ID
     * @return array Validation results
     */
    public function validateCart($userId) {
        $items = $this->getUserCart($userId);
        $errors = [];

        foreach ($items as $item) {
            // Check if product is active
            if ($item['status'] !== 'active') {
                $errors[] = "Product '{$item['name']}' is no longer available";
            }

            // Check stock
            if ($item['stock_quantity'] < $item['quantity']) {
                $errors[] = "Product '{$item['name']}' has insufficient stock";
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
}
