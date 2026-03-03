<?php
/**
 * Shop Sales Model (POS System)
 * Adapted from adiari_shopping-main for unified database
 */

class ShopSales {
    private $db;
    
    public function __construct() {
        $this->db = Database::getConnection('grocery');
    }
    
    public function recordSale($data) {
        try {
            $this->db->beginTransaction();
            $inventoryModel = new ShopInventory();
            $item = $inventoryModel->getItemById($data['inventory_id']);
            if (!$item) throw new Exception("Item not found");
            
            $invoiceNumber = 'MAN-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $query = "INSERT INTO invoices (invoice_number, customer_name, subtotal, total_amount, payment_method, created_at)
                     VALUES (:invoice, :customer, :total, :total2, 'Cash', :date)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':invoice' => $invoiceNumber,
                ':customer' => $data['customer_name'] ?? 'Walk-in',
                ':total' => $data['total_amount'],
                ':total2' => $data['total_amount'],
                ':date' => $data['sale_date'] ?? date('Y-m-d H:i:s')
            ]);
            $invoiceId = $this->db->lastInsertId();
            
            $itemQuery = "INSERT INTO invoice_items (invoice_id, inventory_id, product_name, barcode, quantity, unit_price, line_total)
                         VALUES (:inv_id, :item_id, :name, :barcode, :qty, :price, :total)";
            $itemStmt = $this->db->prepare($itemQuery);
            $itemStmt->execute([
                ':inv_id' => $invoiceId,
                ':item_id' => $item['id'],
                ':name' => $item['product_name'],
                ':barcode' => $item['barcode'],
                ':qty' => $data['quantity_sold'],
                ':price' => $item['unit_price'],
                ':total' => $data['total_amount']
            ]);
            
            $newQuantity = $item['quantity'] - $data['quantity_sold'];
            $inventoryModel->updateQuantity($item['id'], $newQuantity);
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            error_log("ShopSales recordSale error: " . $e->getMessage());
            return false;
        }
    }
    
    public function getAllSales($limit = null) {
        try {
            $query = "SELECT ii.id, ii.product_name, ii.quantity as quantity_sold, 
                        ii.line_total as total_amount, inv.created_at as sale_date,
                        inv.customer_name, i.category, i.unit_price
                     FROM invoice_items ii
                     JOIN invoices inv ON ii.invoice_id = inv.id
                     LEFT JOIN inventory_items i ON ii.inventory_id = i.id
                     ORDER BY inv.created_at DESC";
            if ($limit !== null) $query .= " LIMIT :limit";
            
            $stmt = $this->db->prepare($query);
            if ($limit !== null) $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("ShopSales getAllSales error: " . $e->getMessage());
            return [];
        }
    }
    
    public function getTodaySales() {
        try {
            $todayStart = date('Y-m-d 00:00:00');
            $todayEnd = date('Y-m-d 23:59:59');
            $query = "SELECT COALESCE(SUM(total_amount), 0) as today_sales FROM invoices WHERE created_at BETWEEN :start AND :end";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':start', $todayStart);
            $stmt->bindParam(':end', $todayEnd);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['today_sales'] ?? 0;
        } catch (PDOException $e) {
            return 0;
        }
    }
    
    public function getBestSellingProducts($limit = 5) {
        try {
            $query = "SELECT ii.product_name, i.category, SUM(ii.quantity) as total_sold,
                        SUM(ii.line_total) as total_revenue
                     FROM invoice_items ii
                     LEFT JOIN inventory_items i ON ii.inventory_id = i.id
                     GROUP BY ii.product_name
                     ORDER BY total_sold DESC LIMIT :limit";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
}
