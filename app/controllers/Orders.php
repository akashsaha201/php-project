<?php

class Orders extends Controller {
    private $orderRepo;

    public function __construct() {
        if(!isLoggedIn()) {
            redirect('users/login');
        }
        elseif(isAdmin()) {
            redirect('products');
        }

        $this->orderRepo = new OrderRepository();
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
        $order = $this->orderRepo->getByIdForUser($id, $_SESSION['user_id']);

        if (!$order) {
            flash('order_invalid', 'Order not found', 'alert alert-danger');
            redirect('orders');
        }

        $data = [
            'title' => 'Order Details',
            'order' => $order
        ];
        $this->view('orders/show', $data);
    }


    
    // Checkout page 
    public function checkout() {
        if (empty($_SESSION['cart'])) {
            flash('cart_error', 'Your cart is empty', 'alert alert-warning');
            redirect('cart');
        }

        // Create order
        $order = new Order();
        $order->setUserId($_SESSION['user_id']);
        $order->setStatus('pending');

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

        $orderId = $this->orderRepo->createOrder($order);

        \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

        $lineItems = [];
        foreach ($_SESSION['cart'] as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => ['name' => $item['name']],
                    'unit_amount' => $item['price'] * 100,
                ],
                'quantity' => $item['quantity'],
            ];
        }

        $checkoutSession = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => URLROOT . "/orders/checkoutSuccess?session_id={CHECKOUT_SESSION_ID}",
            'cancel_url'  => URLROOT . "/orders/checkoutCancel?session_id={CHECKOUT_SESSION_ID}",
            'metadata' => [
                'order_id' => $orderId
            ]
        ]);

        header("Location: " . $checkoutSession->url);
        exit;
    }


    // Checkout Success
    public function checkoutSuccess() {
        if (!isset($_GET['session_id'])) {
            redirect('cart');
        }

        \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);
        $session = \Stripe\Checkout\Session::retrieve($_GET['session_id']);

        $orderId = $session->metadata->order_id;

        if ($session->payment_status === 'paid') {
            try {
                $this->orderRepo->markOrderSuccessful($orderId);
                
                // Get order & user
                $order = $this->orderRepo->getByIdForUser($orderId, $_SESSION['user_id']);
                $userRepo = new UserRepository();
                $user = $userRepo->findById($_SESSION['user_id']);

                // Send email
                Mailer::sendOrderConfirmation($user->getEmail(), $user->getUsername(), $order);

                unset($_SESSION['cart']);
                flash('order_success', 'Payment successful! Your order has been placed.', 'alert alert-success');
                redirect("orders/show/" . $orderId);
            } catch (Exception $e) {
                flash('order_error', 'Failed to update order: ' . $e->getMessage(), 'alert alert-danger');
                redirect('cart');
            }
        }
    }


    // Checkout Failed
    public function checkoutCancel() {
        if (!isset($_GET['session_id'])) {
            redirect('cart');
        }

        \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);
        $session = \Stripe\Checkout\Session::retrieve($_GET['session_id']);

        $orderId = $session->metadata->order_id;

        try {
            $this->orderRepo->markOrderFailed($orderId);
            flash('order_error', 'Payment was cancelled.', 'alert alert-danger');
            redirect("orders/show/" . $orderId);
        } catch (Exception $e) {
            flash('order_error', 'Failed to update order: ' . $e->getMessage(), 'alert alert-danger');
            redirect('cart');
        }
    }

}
