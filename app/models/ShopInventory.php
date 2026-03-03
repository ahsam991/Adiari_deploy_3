<?php
/**
 * Shop Inventory Model (POS System)
 * Adapted from adiari_shopping-main for unified database
 */

class ShopInventory {
    private $db;
    private $table = 'products';
    
    public function __construct() {
        $this->db = Database::getConnection('grocery');
    }
    
    // Helper to map product fields to POS expected format
    private function mapProduct($p) {
        if (!$p) return false;
        return [
            'id' => $p['id'],
            'product_name' => $p['name'],
            'category' => 'General', // We'll fetch category name if needed, but for now simple string
            'barcode' => $p['barcode'] ?? $p['sku'], // Fallback to SKU if no barcode
            'quantity' => $p['stock_quantity'],
            'unit_price' => $p['price'],
            'reorder_level' => $p['reorder_level'] ?? 10,
            'supplier_name' => 'Internal',
            'supplier_contact' => '',
            'is_active' => ($p['status'] === 'active' ? 1 : 0),
            // Keep original fields too just in case
            'original_id' => $p['id'],
            'sku' => $p['sku']
        ];
    }

    public function getAllItems() {
        try {
            $query = "SELECT p.*, c.name as category_name 
                      FROM {$this->table} p 
                      LEFT JOIN categories c ON p.category_id = c.id 
                      WHERE p.status = 'active' 
                      ORDER BY p.id DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $products = $stmt->fetchAll();
            
            return array_map(function($p) {
                $item = $this->mapProduct($p);
                $item['category'] = $p['category_name'] ?? 'Uncategorized';
                return $item;
            }, $products);
        } catch (PDOException $e) {
            error_log("ShopInventory getAllItems error: " . $e->getMessage());
            return [];
        }
    }
    
    public function getItemById($id) {
        try {
            $query = "SELECT * FROM {$this->table} WHERE id = :id AND status = 'active' LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $this->mapProduct($stmt->fetch());
        } catch (PDOException $e) {
            error_log("ShopInventory getItemById error: " . $e->getMessage());
            return false;
        }
    }
    
    // Note: In POS we might create products. For now, we map to products table.
    public function addItem($data) {
        try {
            // Find category ID or default to 1
            $catId = 1; 
            
            $query = "INSERT INTO {$this->table} 
                     (name, category_id, slug, sku, barcode, stock_quantity, price, reorder_level, status) 
                     VALUES 
                     (:name, :category_id, :slug, :sku, :barcode, :quantity, :price, :reorder_level, 'active')";
            
            $stmt = $this->db->prepare($query);
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['product_name'])));
            
            $stmt->bindParam(':name', $data['product_name']);
            $stmt->bindParam(':category_id', $catId);
            $stmt->bindParam(':slug', $slug);
            $stmt->bindParam(':sku', $data['barcode']); // Use barcode as SKU if not provided
            $stmt->bindParam(':barcode', $data['barcode']);
            $stmt->bindParam(':quantity', $data['quantity'], PDO::PARAM_INT);
            $stmt->bindParam(':price', $data['unit_price']);
            $stmt->bindParam(':reorder_level', $data['reorder_level'], PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("ShopInventory addItem error: " . $e->getMessage());
            return false;
        }
    }
    
    public function updateItem($id, $data) {
        try {
            $query = "UPDATE {$this->table} 
                     SET name = :name, 
                         stock_quantity = :quantity, 
                         price = :price,
                         reorder_level = :reorder_level, 
                         barcode = :barcode
                     WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $data['product_name']);
            $stmt->bindParam(':quantity', $data['quantity'], PDO::PARAM_INT);
            $stmt->bindParam(':price', $data['unit_price']);
            $stmt->bindParam(':reorder_level', $data['reorder_level'], PDO::PARAM_INT);
            $stmt->bindParam(':barcode', $data['barcode']);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("ShopInventory updateItem error: " . $e->getMessage());
            return false;
        }
    }
    
    public function deleteItem($id) {
        try {
            // Soft delete
            $query = "UPDATE {$this->table} SET status = 'inactive' WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("ShopInventory deleteItem error: " . $e->getMessage());
            return false;
        }
    }
    
    public function updateQuantity($id, $quantity) {
        try {
            $query = "UPDATE {$this->table} SET stock_quantity = :quantity WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("ShopInventory updateQuantity error: " . $e->getMessage());
            return false;
        }
    }
    
    public function decrementStock($id, $quantity) {
        try {
            $query = "UPDATE {$this->table} SET stock_quantity = stock_quantity - :quantity WHERE id = :id AND stock_quantity >= :quantity";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("ShopInventory decrementStock error: " . $e->getMessage());
            return false;
        }
    }
    
    public function getLowStockItems() {
        try {
            $query = "SELECT * FROM {$this->table} WHERE stock_quantity <= reorder_level AND status = 'active' ORDER BY stock_quantity ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $products = $stmt->fetchAll();
            return array_map([$this, 'mapProduct'], $products);
        } catch (PDOException $e) {
            return [];
        }
    }
    
    public function getTotalCount() {
        try {
            $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE status = 'active'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            return 0;
        }
    }
    
    public function getTotalValue() {
        try {
            $query = "SELECT SUM(stock_quantity * price) as total_value FROM {$this->table} WHERE status = 'active'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['total_value'] ?? 0;
        } catch (PDOException $e) {
            return 0;
        }
    }
    
    public function searchItems($search) {
        try {
            $query = "SELECT * FROM {$this->table} 
                     WHERE (name LIKE :search OR sku LIKE :search OR barcode LIKE :search) AND status = 'active'
                     ORDER BY name ASC";
            $stmt = $this->db->prepare($query);
            $searchTerm = "%{$search}%";
            $stmt->bindParam(':search', $searchTerm);
            $stmt->execute();
            $products = $stmt->fetchAll();
            return array_map([$this, 'mapProduct'], $products);
        } catch (PDOException $e) {
            return [];
        }
    }
}
