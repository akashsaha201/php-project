<?php

class Cart extends Controller {
    private $productRepo;

    public function __construct() {
        if(!isLoggedIn()) {
            redirect('users/login');
        }
        elseif(isAdmin()) {
            redirect('products');
        }
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    // Show cart
    public function index() {
        $cartItems = $_SESSION['cart'];
        $data = [
            'title' => 'Your Cart',
            'items' => $cartItems,
            'total' => $this->getCartTotal()
        ];
        $this->view('cart/index', $data);
    }

    // Add product
    public function add($productId) {
        $this->productRepo = new ProductRepository();
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        if ($quantity < 1) $quantity = 1;

        $product = $this->productRepo->getById($productId);

        if ($product) {
            // Prevent exceeding stock
            $availableStock = $product->getQuantity();
            $currentQty = $_SESSION['cart'][$productId]['quantity'] ?? 0;
            if ($currentQty + $quantity > $availableStock) {
                $quantity = $availableStock - $currentQty;
            }

            if ($quantity > 0) {
                if (isset($_SESSION['cart'][$productId])) {
                    $_SESSION['cart'][$productId]['quantity'] += $quantity;
                } else {
                    $_SESSION['cart'][$productId] = [
                        'id' => $product->getId(),
                        'name' => $product->getName(),
                        'price' => $product->getPrice(),
                        'quantity' => $quantity
                    ];
                }
            }
        }
        redirect('cart');
    }

    // Update quantity
    public function update($productId, $action) {
        $this->productRepo = new ProductRepository();
        if (isset($_SESSION['cart'][$productId])) {
            $product = $this->productRepo->getById($productId);
            if ($product) {
                $availableStock = $product->getQuantity();

                if ($action === 'increase') {
                    if ($_SESSION['cart'][$productId]['quantity'] < $availableStock) {
                        $_SESSION['cart'][$productId]['quantity']++;
                    }
                    else {
                    flash('cart_error', 'Maximum stock limit reached for ' . $product->getName(), 'alert alert-warning');
                    }
                } elseif ($action === 'decrease') {
                    $_SESSION['cart'][$productId]['quantity']--;
                    if ($_SESSION['cart'][$productId]['quantity'] <= 0) {
                        unset($_SESSION['cart'][$productId]);
                    }
                }
            }
        }
        redirect('cart');
    }

    // Remove product
    public function remove($productId) {
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }
        redirect('cart');
    }

    // Clear cart
    public function clear() {
        $_SESSION['cart'] = [];
        redirect('cart');
    }

    // Helper
    private function getCartTotal() {
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += (float)$item['price'] * (int)$item['quantity'];
        }
        return $total;
    }
}
