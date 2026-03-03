<?php
/**
 * User Address Model
 */

require_once __DIR__ . '/../core/Model.php';

class UserAddress extends Model {
    protected $table = 'user_addresses';
    protected $db = 'grocery';
    protected $fillable = [
        'user_id', 'address_type', 'first_name', 'last_name', 'phone',
        'address_line1', 'address_line2', 'city', 'state', 'postal_code', 'country', 'is_default'
    ];

    public function getByUserId($userId) {
        return $this->findAll(['user_id' => $userId]);
    }
}
