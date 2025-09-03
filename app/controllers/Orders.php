<?php

class Orders extends Controller {
    private $orderRepo;
    private $productRepo;

    public function __construct() {
        if(!isLoggedIn()) {
            redirect('users/login');
        }
        elseif(isAdmin()) {
            redirect('products');
        }

        $db = new Database;
        $this->orderRepo = new OrderRepository($db);
        $this->productRepo = new ProductRepository($db);
    }

    
    // Show all orders for the logged-in user
    public function index() {
        $orders = $this->orderRepo->getAllByUser($_SESSION['user_id']);
        $data = [
            'title' => 'My Orders',
            'orders' => $orders
        ];
        $this->view('orders/index', $data);
    }

    
    // Show a single order details
    public function show($id) {
        $order = $this->orderRepo->getById($id);

        if (!$order || $order->getUserId() !== $_SESSION['user_id']) {
            flash('order_error', 'Order not found or unauthorized access', 'alert alert-danger');
            redirect('orders');
        }

        $data = [
            'title' => 'Order Details',
            'order' => $order
        ];
        $this->view('orders/show', $data);
    }

    
    // Checkout page (order summary before placing order)
    public function checkout() {
        if (empty($_SESSION['cart'])) {
            flash('cart_error', 'Your cart is empty', 'alert alert-warning');
            redirect('cart');
        }

        $cartItems = $_SESSION['cart'];
        $total = 0;
        foreach ($cartItems as $item) {
            $total += (float)$item['price'] * (int)$item['quantity'];
        }

        $data = [
            'title' => 'Checkout',
            'items' => $cartItems,
            'total' => $total
        ];
        $this->view('orders/checkout', $data);
    }

    
    // Place the order (POST request from checkout)
    public function place() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('cart');
        }

        if (empty($_SESSION['cart'])) {
            flash('cart_error', 'Your cart is empty', 'alert alert-warning');
            redirect('cart');
        }

        $order = new Order();
        $order->setUserId($_SESSION['user_id']);
        $order->setTotalAmount(0);
        $order->setStatus('Pending');

        $total = 0;

        foreach ($_SESSION['cart'] as $cartItem) {
            $item = new OrderItem();
            $item->setProductId($cartItem['id']);
            $item->setQuantity($cartItem['quantity']);
            $item->setPrice($cartItem['price']);
            $order->addItem($item);

            $total += $cartItem['price'] * $cartItem['quantity'];
        }

        $order->setTotalAmount($total);

        try {
            $orderId = $this->orderRepo->createOrder($order);
            unset($_SESSION['cart']); 
            flash('order_success', 'Your order has been placed successfully!', 'alert alert-success');
            redirect('orders/show/' . $orderId);
        } catch (Exception $e) {
            flash('order_error', 'Order failed: ' . $e->getMessage(), 'alert alert-danger');
            redirect('cart');
        }
    }
}
