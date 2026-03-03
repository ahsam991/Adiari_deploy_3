<?php
/**
 * Product Model
 * Handles product data and operations
 */

require_once __DIR__ . '/../core/Model.php';

class Product extends Model {
    protected $table = 'products';
    protected $db = 'grocery';
    protected $fillable = [
        'name', 'slug', 'sku', 'description', 'short_description',
        'category_id', 'price', 'sale_price', 'cost_price', 'tax_rate',
        'stock_quantity', 'min_stock_level', 'unit', 'weight',
        'is_halal', 'halal_cert_number', 'is_organic', 'is_featured',
        'is_new', 'is_on_sale', 'status', 'primary_image',
        'meta_title', 'meta_description', 'meta_keywords', 'deleted_at'
    ];

    /**
     * Soft delete product
     * @param int $id Product ID
     * @return bool
     */
    public function softDelete($id) {
        return $this->update($id, ['deleted_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * Get all active products
     * @param int $limit Limit
     * @param int $offset Offset
     * @param string $sort Sort order
     * @return array
     */
    public function getActiveProducts($limit = null, $offset = 0, $sort = 'newest') {
        $query = "SELECT p.*, c.name as category_name 
                  FROM {$this->table} p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.status = 'active' AND p.deleted_at IS NULL";

        // Apply sorting
        switch ($sort) {
            case 'price_asc':
                $query .= " ORDER BY p.price ASC";
                break;
            case 'price_desc':
                $query .= " ORDER BY p.price DESC";
                break;
            case 'name_asc':
                $query .= " ORDER BY p.name ASC";
                break;
            case 'oldest':
                $query .= " ORDER BY p.created_at ASC";
                break;
            case 'newest':
            default:
                $query .= " ORDER BY p.created_at DESC";
                break;
        }

        if ($limit !== null) {
            $query .= " LIMIT ? OFFSET ?";
            return Database::fetchAll($query, [$limit, $offset], $this->db);
        }

        return Database::fetchAll($query, [], $this->db);
    }

    /**
     * Count all active products
     * @return int
     */
    public function countActiveProducts() {
        $query = "SELECT COUNT(*) as count FROM {$this->table} 
                  WHERE status = 'active' AND deleted_at IS NULL";
        $result = Database::fetchOne($query, [], $this->db);
        return (int)($result['count'] ?? 0);
    }

    /**
     * Get featured products
     * @param int $limit Limit
     * @return array
     */
    public function getFeaturedProducts($limit = 8) {
        $query = "SELECT p.*, c.name as category_name 
                  FROM {$this->table} p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.status = 'active' 
                  AND p.is_featured = 1 
                  AND p.deleted_at IS NULL
                  ORDER BY p.created_at DESC
                  LIMIT {$limit}";

        return Database::fetchAll($query, [], $this->db);
    }

    /**
     * Get products by category
     * @param int $categoryId Category ID
     * @param int $limit Limit
     * @param int $offset Offset
     * @param string $sort Sort order
     * @return array
     */
    public function getProductsByCategory($categoryId, $limit = 20, $offset = 0, $sort = 'newest') {
        $query = "SELECT p.*, c.name as category_name 
                  FROM {$this->table} p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.category_id = ? 
                  AND p.status = 'active' 
                  AND p.deleted_at IS NULL";

        // Apply sorting
        switch ($sort) {
            case 'price_asc':
                $query .= " ORDER BY p.price ASC";
                break;
            case 'price_desc':
                $query .= " ORDER BY p.price DESC";
                break;
            case 'name_asc':
                $query .= " ORDER BY p.name ASC";
                break;
            case 'oldest':
                $query .= " ORDER BY p.created_at ASC";
                break;
            case 'newest':
            default:
                $query .= " ORDER BY p.created_at DESC";
                break;
        }

        $query .= " LIMIT ? OFFSET ?";

        return Database::fetchAll($query, [$categoryId, $limit, $offset], $this->db);
    }

    /**
     * Count products by category
     * @param int $categoryId Category ID
     * @return int
     */
    public function countProductsByCategory($categoryId) {
        $query = "SELECT COUNT(*) as count FROM {$this->table} 
                  WHERE category_id = ? AND status = 'active' AND deleted_at IS NULL";
        $result = Database::fetchOne($query, [$categoryId], $this->db);
        return (int)($result['count'] ?? 0);
    }

    /**
     * Search products
     * @param string $searchQuery Search query
     * @param int $limit Limit
     * @param int $offset Offset
     * @return array
     */
    public function searchProducts($searchQuery, $limit = 20, $offset = 0) {
        $query = "SELECT p.*, c.name as category_name 
                  FROM {$this->table} p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE (p.name LIKE ? OR p.description LIKE ? OR p.sku LIKE ?)
                  AND p.status = 'active' 
                  AND p.deleted_at IS NULL
                  ORDER BY p.name ASC
                  LIMIT ? OFFSET ?";

        $searchTerm = '%' . $searchQuery . '%';
        return Database::fetchAll($query, [$searchTerm, $searchTerm, $searchTerm, $limit, $offset], $this->db);
    }

    /**
     * Count search results
     * @param string $searchQuery Search query
     * @return int
     */
    public function countSearchProducts($searchQuery) {
        $query = "SELECT COUNT(*) as count FROM {$this->table} 
                  WHERE (name LIKE ? OR description LIKE ? OR sku LIKE ?)
                  AND status = 'active' AND deleted_at IS NULL";
        
        $searchTerm = '%' . $searchQuery . '%';
        $result = Database::fetchOne($query, [$searchTerm, $searchTerm, $searchTerm], $this->db);
        return (int)($result['count'] ?? 0);
    }

    /**
     * Get product with details
     * @param int $id Product ID
     * @return array|false
     */
    public function getProductDetails($id) {
        $query = "SELECT p.*, c.name as category_name, c.slug as category_slug
                  FROM {$this->table} p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.id = ? AND p.deleted_at IS NULL
                  LIMIT 1";

        $product = Database::fetchOne($query, [$id], $this->db);

        if ($product) {
            // Get product images
            $product['images'] = $this->getProductImages($id);
            
            // Get average rating
            $product['average_rating'] = $this->getAverageRating($id);
            $product['review_count'] = $this->getReviewCount($id);
        }

        return $product;
    }

    /**
     * Get product images
     * @param int $productId Product ID
     * @return array
     */
    public function getProductImages($productId) {
        $query = "SELECT * FROM product_images 
                  WHERE product_id = ? 
                  ORDER BY is_primary DESC, sort_order ASC";
        
        return Database::fetchAll($query, [$productId], $this->db);
    }
    
    /**
     * Add product image
     * @param int $productId Product ID
     * @param string $imagePath Image path
     * @param bool $isPrimary Is primary image
     * @param int $sortOrder Sort order
     * @param string|null $altText Alt text
     * @return bool
     */
    public function addImage($productId, $imagePath, $isPrimary = false, $sortOrder = 0, $altText = null) {
        // If setting as primary, unset other primary images
        if ($isPrimary) {
            $updateQuery = "UPDATE product_images SET is_primary = 0 WHERE product_id = ?";
            Database::query($updateQuery, [$productId], $this->db);
        }
        
        $query = "INSERT INTO product_images (product_id, image_path, is_primary, sort_order, alt_text) 
                  VALUES (?, ?, ?, ?, ?)";
        
        return Database::query($query, [
            $productId,
            $imagePath,
            $isPrimary ? 1 : 0,
            $sortOrder,
            $altText
        ], $this->db);
    }
    
    /**
     * Delete product image
     * @param int $imageId Image ID
     * @return bool
     */
    public function deleteImage($imageId) {
        try {
            // Get image info before deleting
            $query = "SELECT * FROM product_images WHERE id = ?";
            $image = Database::fetchOne($query, [$imageId], $this->db);
            
            if (!$image) {
                return false;
            }
            
            // Delete the file from disk
            $imagePath = ltrim($image['image_path'] ?? '', '/');
            if ($imagePath) {
                $filePath = __DIR__ . '/../../public/' . $imagePath;
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }
            
            // Delete from database
            $deleteQuery = "DELETE FROM product_images WHERE id = ?";
            Database::query($deleteQuery, [$imageId], $this->db);
            return true;
        } catch (Exception $e) {
            error_log('Product::deleteImage error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Set primary image
     * @param int $productId Product ID
     * @param int $imageId Image ID
     * @return bool
     */
    public function setPrimaryImage($productId, $imageId) {
        // Unset all primary images for this product
        $updateQuery = "UPDATE product_images SET is_primary = 0 WHERE product_id = ?";
        Database::query($updateQuery, [$productId], $this->db);
        
        // Set new primary
        $setPrimaryQuery = "UPDATE product_images SET is_primary = 1 WHERE id = ? AND product_id = ?";
        return Database::query($setPrimaryQuery, [$imageId, $productId], $this->db);
    }


    /**
     * Get average rating
     * @param int $productId Product ID
     * @return float
     */
    private function getAverageRating($productId) {
        $query = "SELECT AVG(rating) as avg_rating 
                  FROM reviews 
                  WHERE product_id = ? AND is_approved = 1";
        
        $result = Database::fetchOne($query, [$productId], $this->db);
        return round($result['avg_rating'] ?? 0, 1);
    }

    /**
     * Get review count
     * @param int $productId Product ID
     * @return int
     */
    private function getReviewCount($productId) {
        $query = "SELECT COUNT(*) as count 
                  FROM reviews 
                  WHERE product_id = ? AND is_approved = 1";
        
        $result = Database::fetchOne($query, [$productId], $this->db);
        return (int)($result['count'] ?? 0);
    }

    /**
     * Increment view count
     * @param int $productId Product ID
     */
    public function incrementViews($productId) {
        $query = "UPDATE {$this->table} 
                  SET views_count = views_count + 1 
                  WHERE id = ?";
        
        Database::query($query, [$productId], $this->db);
    }

    /**
     * Check if product is in stock
     * @param int $productId Product ID
     * @param int $quantity Quantity to check
     * @return bool
     */
    public function isInStock($productId, $quantity = 1) {
        $product = $this->find($productId);
        
        if (!$product) {
            return false;
        }

        return $product['stock_quantity'] >= $quantity && $product['status'] === 'active';
    }

    /**
     * Get related products
     * @param int $productId Product ID
     * @param int $categoryId Category ID
     * @param int $limit Limit
     * @return array
     */
    public function getRelatedProducts($productId, $categoryId, $limit = 4) {
        $query = "SELECT * FROM {$this->table}
                  WHERE category_id = ? 
                  AND id != ? 
                  AND status = 'active'
                  AND deleted_at IS NULL
                  ORDER BY RAND()
                  LIMIT ?";
        
        return Database::fetchAll($query, [$categoryId, $productId, $limit], $this->db);
    }

    /**
     * Update product stock
     * @param int $productId Product ID
     * @param int $quantity Quantity to add/subtract (negative to decrease)
     * @return bool
     */
    public function updateStock($productId, $quantity) {
        $product = $this->find($productId);
        if (!$product) {
            return false;
        }

        $newStock = max(0, (int)$product['stock_quantity'] + (int)$quantity);
        return $this->update($productId, ['stock_quantity' => $newStock]);
    }

    /**
     * Get low stock products
     * @param int $threshold Threshold for low stock
     * @return array
     */
    public function getLowStockProducts($threshold = null) {
        if ($threshold === null) {
            // Use min_stock_level if not specified
            $query = "SELECT * FROM {$this->table}
                      WHERE stock_quantity <= min_stock_level
                      AND status = 'active'
                      AND deleted_at IS NULL
                      ORDER BY stock_quantity ASC";
            return Database::fetchAll($query, [], $this->db);
        }

        $query = "SELECT * FROM {$this->table}
                  WHERE stock_quantity <= ?
                  AND status = 'active'
                  AND deleted_at IS NULL
                  ORDER BY stock_quantity ASC";
        return Database::fetchAll($query, [$threshold], $this->db);
    }
}
