<?php
/**
 * Category Model
 * Handles product category operations
 */

require_once __DIR__ . '/../core/Model.php';

class Category extends Model {
    protected $table = 'categories';
    protected $db = 'grocery';
    protected $fillable = [
        'name', 'slug', 'description', 'parent_id', 
        'image', 'icon', 'display_order', 'status',
        'meta_title', 'meta_description'
    ];

    /**
     * Get all active categories
     * @return array
     */
    public function getActiveCategories() {
        return $this->findAll(['status' => 'active'], null, 0);
    }

    /**
     * Get category by slug
     * @param string $slug Category slug
     * @return array|false
     */
    public function findBySlug($slug) {
        return $this->findBy(['slug' => $slug]);
    }

    /**
     * Get categories with product count
     * @return array
     */
    public function getCategoriesWithProductCount() {
        $query = "SELECT c.*, COUNT(p.id) as product_count
                  FROM {$this->table} c
                  LEFT JOIN products p ON c.id = p.category_id AND p.status = 'active' AND p.deleted_at IS NULL
                  WHERE c.status = 'active'
                  GROUP BY c.id
                  ORDER BY c.display_order ASC, c.name ASC";
        
        return Database::fetchAll($query, [], $this->db);
    }

    /**
     * Get parent categories (main categories)
     * @return array
     */
    public function getParentCategories() {
        $query = "SELECT * FROM {$this->table}
                  WHERE parent_id IS NULL 
                  AND status = 'active'
                  ORDER BY display_order ASC, name ASC";
        
        return Database::fetchAll($query, [], $this->db);
    }

    /**
     * Get subcategories
     * @param int $parentId Parent category ID
     * @return array
     */
    public function getSubcategories($parentId) {
        return $this->findAll(['parent_id' => $parentId, 'status' => 'active']);
    }

    /**
     * Get category with subcategories
     * @param int $id Category ID
     * @return array|false
     */
    public function getCategoryWithSubcategories($id) {
        $category = $this->find($id);
        
        if ($category) {
            $category['subcategories'] = $this->getSubcategories($id);
        }

        return $category;
    }
}
