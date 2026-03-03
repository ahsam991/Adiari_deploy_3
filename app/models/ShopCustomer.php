<?php
/**
 * Shop Customer Model (POS System)
 * Adapted from adiari_shopping-main for unified database
 */

class ShopCustomer {
    private $db;
    private $table = 'users';
    
    public function __construct() {
        $this->db = Database::getConnection('grocery');
    }
    
    // Helper to map user fields to POS customer format
    private function mapCustomer($u) {
        if (!$u) return false;
        return [
            'id' => $u['id'],
            'customer_name' => $u['first_name'] . ' ' . $u['last_name'],
            'phone' => $u['phone'] ?? '',
            'email' => $u['email'],
            'address' => '', // Address is in another table, leaving empty for list view performance
            'total_purchases' => $u['total_purchases'] ?? 0,
            'loyalty_points' => $u['loyalty_points'] ?? 0,
            // Keep original fields
            'first_name' => $u['first_name'],
            'last_name' => $u['last_name']
        ];
    }

    public function getAllCustomers() {
        try {
            // Only get customers, not admins
            $query = "SELECT * FROM {$this->table} WHERE role = 'customer' ORDER BY first_name ASC";
            $stmt = $this->db->query($query);
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return array_map([$this, 'mapCustomer'], $users);
        } catch (PDOException $e) {
            return [];
        }
    }
    
    public function getCustomerById($id) {
        try {
            $query = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $this->mapCustomer($stmt->fetch(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function createCustomer($data) {
        try {
            // Split name
            $parts = explode(' ', $data['customer_name'], 2);
            $firstName = $parts[0];
            $lastName = $parts[1] ?? '';
            
            // Password is required for users table, generate random one
            $tempPassword = password_hash('customer123', PASSWORD_DEFAULT);
            
            $query = "INSERT INTO {$this->table} (first_name, last_name, phone, email, role, password, status) 
                     VALUES (:first_name, :last_name, :phone, :email, 'customer', :password, 'active')";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':first_name', $firstName);
            $stmt->bindParam(':last_name', $lastName);
            $stmt->bindParam(':phone', $data['phone']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':password', $tempPassword);
            
            if ($stmt->execute()) return $this->db->lastInsertId();
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function updateCustomer($id, $data) {
        try {
            $parts = explode(' ', $data['customer_name'], 2);
            $firstName = $parts[0];
            $lastName = $parts[1] ?? '';

            $query = "UPDATE {$this->table} SET first_name = :first_name, last_name = :last_name, 
                     phone = :phone, email = :email, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':first_name', $firstName);
            $stmt->bindParam(':last_name', $lastName);
            $stmt->bindParam(':phone', $data['phone']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function updatePurchases($customerId, $amount) {
        try {
            $points = floor($amount / 100);
            $query = "UPDATE {$this->table} SET total_purchases = total_purchases + :amount,
                     loyalty_points = loyalty_points + :points, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':amount', $amount);
            $stmt->bindParam(':points', $points);
            $stmt->bindParam(':id', $customerId);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
