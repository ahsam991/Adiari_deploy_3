<?php
/**
 * Shop User Model (POS System)
 * Adapted from adiari_shopping-main for unified database
 */

class ShopUser {
    private $db;
    private $table = 'shop_users';
    
    public function __construct() {
        $this->db = Database::getConnection('grocery');
    }
    
    public function authenticate($username, $password) {
        try {
            $query = "SELECT * FROM {$this->table} WHERE username = :username AND is_active = 1 LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && password_verify($password, $user['password_hash'])) {
                $this->updateLastLogin($user['id']);
                unset($user['password_hash']);
                return $user;
            }
            return false;
        } catch (PDOException $e) {
            error_log("ShopUser auth error: " . $e->getMessage());
            return false;
        }
    }
    
    public function getUserById($id) {
        try {
            $query = "SELECT id, username, full_name, email, role, created_at, last_login, is_active 
                     FROM {$this->table} WHERE id = :id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("ShopUser get error: " . $e->getMessage());
            return false;
        }
    }

    public function getAllUsers() {
        try {
            $query = "SELECT id, username, full_name, email, role, created_at, last_login, is_active 
                     FROM {$this->table} ORDER BY created_at DESC";
            $stmt = $this->db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("ShopUser getAll error: " . $e->getMessage());
            return [];
        }
    }

    public function createUser($data) {
        try {
            $query = "INSERT INTO {$this->table} 
                     (username, password_hash, full_name, email, role, is_active) 
                     VALUES (:username, :password_hash, :full_name, :email, :role, :is_active)";
            $stmt = $this->db->prepare($query);
            $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);
            $stmt->bindParam(':username', $data['username']);
            $stmt->bindParam(':password_hash', $passwordHash);
            $stmt->bindParam(':full_name', $data['full_name']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':role', $data['role']);
            $isActive = $data['is_active'] ?? 1;
            $stmt->bindParam(':is_active', $isActive);
            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            error_log("ShopUser create error: " . $e->getMessage());
            return false;
        }
    }
    
    public function hasPermission($userId, $module, $action) {
        $user = $this->getUserById($userId);
        if (!$user) return false;
        $role = $user['role'];
        if ($role === 'admin') return true;
        
        $permissions = [
            'manager' => [
                'dashboard' => ['view'],
                'inventory' => ['view', 'add', 'edit', 'delete'],
                'sales' => ['view', 'delete'],
                'pos' => ['view', 'add'],
                'customers' => ['view', 'add', 'edit'],
                'coupons' => ['view', 'add', 'edit', 'delete'],
                'reports' => ['view']
            ],
            'staff' => [
                'inventory' => ['view', 'add'],
                'pos' => ['view', 'add'],
                'customers' => ['view', 'add']
            ]
        ];
        
        if (isset($permissions[$role][$module])) {
            return in_array($action, $permissions[$role][$module]);
        }
        return false;
    }
    
    public function logActivity($userId, $action, $module = null, $details = null) {
        try {
            $query = "INSERT INTO activity_logs (user_id, action, module, details, ip_address) 
                     VALUES (:user_id, :action, :module, :details, :ip_address)";
            $stmt = $this->db->prepare($query);
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':action', $action);
            $stmt->bindParam(':module', $module);
            $stmt->bindParam(':details', $details);
            $stmt->bindParam(':ip_address', $ipAddress);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("ShopUser logActivity error: " . $e->getMessage());
            return false;
        }
    }
    
    public function createSession($userId) {
        try {
            $token = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', strtotime('+30 minutes'));
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
            $query = "INSERT INTO sessions (user_id, session_token, expires_at, ip_address, user_agent) 
                     VALUES (:user_id, :token, :expires_at, :ip_address, :user_agent)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':expires_at', $expiresAt);
            $stmt->bindParam(':ip_address', $ipAddress);
            $stmt->bindParam(':user_agent', $userAgent);
            if ($stmt->execute()) return $token;
            return false;
        } catch (PDOException $e) {
            error_log("ShopUser createSession error: " . $e->getMessage());
            return false;
        }
    }
    
    public function destroySession($token) {
        try {
            $query = "DELETE FROM sessions WHERE session_token = :token";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':token', $token);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    private function updateLastLogin($userId) {
        try {
            $query = "UPDATE {$this->table} SET last_login = CURRENT_TIMESTAMP WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $userId);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
