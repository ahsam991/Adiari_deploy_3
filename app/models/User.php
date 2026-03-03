<?php
/**
 * User Model
 * Handles user authentication and management
 */

require_once __DIR__ . '/../core/Model.php';
require_once __DIR__ . '/../helpers/Security.php';

class User extends Model {
    protected $table = 'users';
    protected $db = 'grocery';
    protected $fillable = [
        'email', 'password', 'first_name', 'last_name', 'phone', 'role', 'status',
        'login_attempts', 'lockout_until', 'last_login_at', 'last_login_ip',
        'email_verified_at', 'email_verification_token', 'password_reset_token', 'password_reset_expires'
    ];

    /**
     * Create new user
     * @param array $data User data
     * @return int User ID
     */
    public function createUser($data) {
        // Hash password
        if (isset($data['password'])) {
            $data['password'] = Security::hashPassword($data['password']);
        }

        // Set default role if not provided
        if (!isset($data['role'])) {
            $data['role'] = 'customer';
        }

        return $this->create($data);
    }

    /**
     * Find user by email
     * @param string $email Email address
     * @return array|false
     */
    public function findByEmail($email) {
        return $this->findBy(['email' => $email]);
    }

    /**
     * Authenticate user
     * @param string $email Email
     * @param string $password Password
     * @return array|false User data if successful, false otherwise
     */
    public function authenticate($email, $password) {
        $user = $this->findByEmail($email);

        if (!$user) {
            return false;
        }

        // Check if account is locked
        if ($this->isAccountLocked($user)) {
            return false;
        }

        // Verify password
        if (!Security::verifyPassword($password, $user['password'])) {
            $this->incrementLoginAttempts($user['id']);
            return false;
        }

        // Reset login attempts and update last login
        $this->resetLoginAttempts($user['id']);
        $this->updateLastLogin($user['id']);

        // Remove password from returned data
        unset($user['password']);

        return $user;
    }

    /**
     * Check if account is locked
     * @param array $user User data
     * @return bool
     */
    private function isAccountLocked($user) {
        if (!isset($user['lockout_until']) || empty($user['lockout_until'])) {
            return false;
        }

        $lockoutTime = strtotime($user['lockout_until']);
        $currentTime = time();

        return $lockoutTime > $currentTime;
    }

    /**
     * Increment login attempts
     * @param int $userId User ID
     */
    private function incrementLoginAttempts($userId) {
        $user = $this->find($userId);
        $attempts = (isset($user['login_attempts']) ? $user['login_attempts'] : 0) + 1;

        // Lock account after 5 failed attempts
        $data = ['login_attempts' => $attempts];
        
        if ($attempts >= 5) {
            $data['lockout_until'] = date('Y-m-d H:i:s', time() + 900); // 15 minutes
        }

        $this->update($userId, $data);
    }

    /**
     * Reset login attempts
     * @param int $userId User ID
     */
    private function resetLoginAttempts($userId) {
        $this->update($userId, [
            'login_attempts' => 0,
            'lockout_until' => null
        ]);
    }

    /**
     * Update last login
     * @param int $userId User ID
     */
    private function updateLastLogin($userId) {
        $this->update($userId, [
            'last_login_at' => date('Y-m-d H:i:s'),
            'last_login_ip' => $_SERVER['REMOTE_ADDR'] ?? null
        ]);
    }

    /**
     * Update user password
     * @param int $userId User ID
     * @param string $newPassword New password
     * @return bool
     */
    public function updatePassword($userId, $newPassword) {
        $hashedPassword = Security::hashPassword($newPassword);
        return $this->update($userId, ['password' => $hashedPassword]);
    }

    /**
     * Verify email
     * @param int $userId User ID
     * @return bool
     */
    public function verifyEmail($userId) {
        return $this->update($userId, [
            'email_verified_at' => date('Y-m-d H:i:s'),
            'email_verification_token' => null
        ]);
    }

    /**
     * Generate password reset token
     * @param string $email Email
     * @return string|false Token if successful, false otherwise
     */
    public function generatePasswordResetToken($email) {
        $user = $this->findByEmail($email);
        
        if (!$user) {
            return false;
        }

        $token = Security::generateToken();
        $expires = date('Y-m-d H:i:s', time() + 3600); // 1 hour

        $this->update($user['id'], [
            'password_reset_token' => $token,
            'password_reset_expires' => $expires
        ]);

        return $token;
    }

    /**
     * Reset password using token
     * @param string $token Reset token
     * @param string $newPassword New password
     * @return bool
     */
    public function resetPasswordWithToken($token, $newPassword) {
        $query = "SELECT * FROM {$this->table} 
                  WHERE password_reset_token = ? 
                  AND password_reset_expires > NOW() 
                  LIMIT 1";
        
        $user = Database::fetchOne($query, [$token], $this->db);

        if (!$user) {
            return false;
        }

        // Update password and clear reset token
        $hashedPassword = Security::hashPassword($newPassword);
        
        return $this->update($user['id'], [
            'password' => $hashedPassword,
            'password_reset_token' => null,
            'password_reset_expires' => null
        ]);
    }

    /**
     * Get all customers
     * @return array
     */
    public function getCustomers() {
        return $this->findAll(['role' => 'customer']);
    }

    /**
     * Get all managers
     * @return array
     */
    public function getManagers() {
        return $this->findAll(['role' => 'manager']);
    }

    /**
     * Get all admins
     * @return array
     */
    public function getAdmins() {
        return $this->findAll(['role' => 'admin']);
    }

    /**
     * Check if user has role
     * @param int $userId User ID
     * @param string|array $roles Role(s) to check
     * @return bool
     */
    public function hasRole($userId, $roles) {
        $user = $this->find($userId);
        
        if (!$user) {
            return false;
        }

        if (is_array($roles)) {
            return in_array($user['role'], $roles);
        }

        return $user['role'] === $roles;
    }
}
