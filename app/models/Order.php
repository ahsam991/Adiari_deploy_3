<?php
/**
 * Order Model
 * Handles order creation and management
 */

require_once __DIR__ . '/../core/Model.php';

class Order extends Model {
    protected $table = 'orders';
    protected $db = 'grocery';
    protected $fillable = [
        'order_number', 'user_id', 'subtotal', 'discount_amount',
        'tax_amount', 'shipping_cost', 'total_amount', 'coupon_code',
        'payment_method', 'payment_status', 'shipping_first_name',
        'shipping_last_name', 'shipping_email', 'shipping_phone',
        'shipping_address_line1', 'shipping_address_line2',
        'shipping_city', 'shipping_state', 'shipping_postal_code',
        'shipping_country', 'status', 'customer_notes', 'admin_notes'
    ];

    /**
     * Create new order
     * @param array $orderData Order data
     * @param array $items Order items
     * @return int Order ID
     */
    public function createOrder($orderData, $items) {
        $this->beginTransaction();

        try {
            // Generate order number
            $orderData['order_number'] = $this->generateOrderNumber();

            // Log order data for debugging
            error_log("Order::createOrder - Attempting to create order. Columns: " . json_encode(array_keys($orderData)));
            error_log("Order::createOrder - Order number: " . $orderData['order_number']);
            error_log("Order::createOrder - User ID: " . ($orderData['user_id'] ?? 'N/A'));
            error_log("Order::createOrder - Total: " . ($orderData['total_amount'] ?? 'N/A'));

            // Create order
            $orderId = $this->create($orderData);

            if (!$orderId) {
                error_log("Order::createOrder - create() returned falsy: " . var_export($orderId, true));
                // Try to recover ID if lastInsertId failed but insert succeeded
                $existingOrder = $this->findBy(['order_number' => $orderData['order_number']]);
                if ($existingOrder) {
                    $orderId = $existingOrder['id'];
                    error_log("Order::createOrder - Recovered order ID via order_number: {$orderId}");
                } else {
                    throw new Exception("Failed to create order record. Database insert returned false.");
                }
            }

            error_log("Order::createOrder - Order created with ID: {$orderId}");

            // Create order items
            foreach ($items as $item) {
                $this->createOrderItem($orderId, $item);
            }

            $this->commit();
            error_log("Order::createOrder - Order {$orderId} committed successfully");
            return $orderId;

        } catch (Exception $e) {
            $this->rollback();
            error_log("Order::createOrder - FAILED: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Generate unique order number
     * @return string
     */
    private function generateOrderNumber() {
        $prefix = 'ORD';
        $date = date('Ymd');
        $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        
        return $prefix . '-' . $date . '-' . $random;
    }

    /**
     * Create order item
     * @param int $orderId Order ID
     * @param array $itemData Item data
     * @throws Exception
     */
    private function createOrderItem($orderId, $itemData) {
        // Validate required fields
        if (!isset($itemData['product_id']) || !isset($itemData['quantity']) || !isset($itemData['unit_price'])) {
            throw new Exception("Missing required order item fields");
        }

        $query = "INSERT INTO order_items 
                  (order_id, product_id, product_name, product_sku, quantity, unit_price, total_price)
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        Database::query($query, [
            $orderId,
            $itemData['product_id'],
            $itemData['product_name'] ?? 'Unknown Product',
            $itemData['product_sku'] ?? null,
            $itemData['quantity'],
            $itemData['unit_price'],
            $itemData['total_price'] ?? ($itemData['quantity'] * $itemData['unit_price'])
        ], $this->db);
    }

    /**
     * Get user orders
     * @param int $userId User ID
     * @param int $limit Limit
     * @return array
     */
    public function getUserOrders($userId, $limit = 20) {
        $query = "SELECT * FROM {$this->table}
                  WHERE user_id = ?
                  ORDER BY created_at DESC
                  LIMIT {$limit}";
        
        return Database::fetchAll($query, [$userId], $this->db);
    }

    /**
     * Get order with items
     * @param int $orderId Order ID
     * @return array|false
     */
    public function getOrderWithItems($orderId) {
        $order = $this->find($orderId);

        if ($order) {
            $order['items'] = $this->getOrderItems($orderId);
        }

        return $order;
    }

    /**
     * Get order items
     * @param int $orderId Order ID
     * @return array
     */
    private function getOrderItems($orderId) {
        $query = "SELECT * FROM order_items WHERE order_id = ?";
        return Database::fetchAll($query, [$orderId], $this->db);
    }

    /**
     * Update order status
     * @param int $orderId Order ID
     * @param string $status New status
     * @return bool
     */
    public function updateOrderStatus($orderId, $status) {
        $data = ['status' => $status];

        // Update timestamp based on status
        if ($status === 'confirmed') {
            $data['confirmed_at'] = date('Y-m-d H:i:s');
        } elseif ($status === 'shipped') {
            $data['shipped_at'] = date('Y-m-d H:i:s');
        } elseif ($status === 'delivered') {
            $data['delivered_at'] = date('Y-m-d H:i:s');
        } elseif ($status === 'cancelled') {
            $data['cancelled_at'] = date('Y-m-d H:i:s');
        }

        return $this->update($orderId, $data);
    }

    /**
     * Update payment status
     * @param int $orderId Order ID
     * @param string $status Payment status
     * @param string|null $transactionId Transaction ID
     * @return bool
     */
    public function updatePaymentStatus($orderId, $status, $transactionId = null) {
        $data = ['payment_status' => $status];

        if ($status === 'paid') {
            $data['payment_date'] = date('Y-m-d H:i:s');
        }

        if ($transactionId) {
            $data['transaction_id'] = $transactionId;
        }

        return $this->update($orderId, $data);
    }

    /**
     * Get recent orders
     * @param int $limit Limit
     * @return array
     */
    public function getRecentOrders($limit = 10) {
        $query = "SELECT o.*, u.first_name, u.last_name, u.email
                  FROM {$this->table} o
                  INNER JOIN users u ON o.user_id = u.id
                  ORDER BY o.created_at DESC
                  LIMIT {$limit}";
        
        return Database::fetchAll($query, [], $this->db);
    }

    /**
     * Get pending orders
     * @return array
     */
    public function getPendingOrders() {
        return $this->findAll(['status' => 'pending']);
    }

    /**
     * Get orders by status
     * @param string $status Order status
     * @return array
     */
    public function getOrdersByStatus($status) {
        return $this->findAll(['status' => $status]);
    }

    /**
     * Get today's orders count
     * @return int
     */
    public function getTodayOrdersCount() {
        $query = "SELECT COUNT(*) as count 
                  FROM {$this->table}
                  WHERE DATE(created_at) = CURDATE()";
        
        $result = Database::fetchOne($query, [], $this->db);
        return (int)($result['count'] ?? 0);
    }

    /**
     * Get today's revenue
     * @return float
     */
    public function getTodayRevenue() {
        $query = "SELECT SUM(total_amount) as revenue 
                  FROM {$this->table}
                  WHERE DATE(created_at) = CURDATE() 
                  AND payment_status = 'paid'";
        
        $result = Database::fetchOne($query, [], $this->db);
        return (float)($result['revenue'] ?? 0);
    }

    /**
     * Get daily sales data for charting within a specific date range
     * @param string $startDate (Y-m-d)
     * @param string $endDate (Y-m-d)
     * @return array Array of dates and revenue
     */
    public function getSalesDataCustom($startDate, $endDate) {
        $query = "SELECT DATE(created_at) as sale_date, SUM(total_amount) as daily_revenue
                  FROM {$this->table}
                  WHERE DATE(created_at) >= ? AND DATE(created_at) <= ?
                  AND (payment_status = 'paid' OR status = 'delivered')
                  GROUP BY DATE(created_at)
                  ORDER BY sale_date ASC";
                  
        return Database::fetchAll($query, [$startDate, $endDate], $this->db);
    }
}
