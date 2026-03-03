<?php
/**
 * Order Controller
 * Customer order history and order detail
 */

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../helpers/Session.php';

class OrderController extends Controller {
    private $orderModel;

    public function __construct() {
        parent::__construct();
        $this->orderModel = new Order();
    }

    private function requireUser() {
        if (!Session::isLoggedIn()) {
            Session::setFlash('error', 'Please login to view your orders.');
            $this->redirect('/login');
        }
        return (int) Session::get('user_id');
    }

    /**
     * List user's orders
     */
    public function index() {
        $userId = $this->requireUser();
        $orders = $this->orderModel->getUserOrders($userId, 50);

        $this->view('orders/index', [
            'title' => 'My Orders - ADI ARI Fresh',
            'orders' => $orders
        ]);
    }

    /**
     * Order detail
     * @param int $id Order ID
     */
    public function show($id) {
        $userId = $this->requireUser();
        $order = $this->orderModel->getOrderWithItems($id);

        if (!$order) {
            Session::setFlash('error', 'Order not found.');
            $this->redirect('/orders');
        }

        if ((int) $order['user_id'] !== $userId) {
            Session::setFlash('error', 'Access denied.');
            $this->redirect('/orders');
        }

        $this->view('orders/show', [
            'title' => 'Order ' . htmlspecialchars($order['order_number']) . ' - ADI ARI Fresh',
            'order' => $order
        ]);
    }
}
