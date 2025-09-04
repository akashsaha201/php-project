<?php

class OrderRepository {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    // Create a new order with its items (transaction-safe).
    public function createOrder(Order $order) {
        try {
            $this->db->beginTransaction();

            // Insert order
            $orderId = $this->insertOrder($order);
            $order->setId($orderId);

            // Insert order items + update stock
            foreach ($order->getItems() as $item) {
                // Insert Order Item
                $this->insertOrderItem($orderId, $item);

                // Update stock
                $this->updateStock($item);
            }

            $this->db->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function createFailedOrder(Order $order) {
        try {
            $this->db->beginTransaction();

            // Insert order
            $orderId = $this->insertOrder($order);
            $order->setId($orderId);

            // Insert order items
            foreach ($order->getItems() as $item) {
                // Insert order item
                $this->insertOrderItem($orderId, $item);
            }

            $this->db->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }


    // Get an order by ID (with items).
    public function getById($id) {
        $this->db->query("SELECT * FROM orders WHERE id = :id");
        $this->db->bind(':id', $id);
        $orderRow = $this->db->single();

        if (!$orderRow) return null;

        $order = $this->mapOrder($orderRow);

        // Fetch items
        $this->db->query("SELECT oi.*, p.name as product_name 
                        FROM order_items oi 
                        JOIN products p ON oi.product_id = p.id 
                        WHERE oi.order_id = :id");
        $this->db->bind(':id', $id);
        $items = $this->db->resultSet();

        foreach ($items as $row) {
            $order->addItem($this->mapOrderItem($row));
        }

        return $order;
    }

    // Get all orders for a specific user
    public function getAllByUser($userId) {
        $this->db->query("SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC");
        $this->db->bind(":user_id", $userId);
        $results = $this->db->resultSet();

        $orders = [];
        foreach ($results as $row) {
            $orders[] = $this->mapOrder($row);
        }
        return $orders;
    }

    // Update order status.
    public function updateStatus($id, $status) {
        $this->db->query("UPDATE orders SET status = :status WHERE id = :id");
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Map DB row to Order entity.
    private function mapOrder($row) {
        $order = new Order();
        $order->setId($row['id']);
        $order->setUserId($row['user_id']);
        $order->setTotalAmount($row['total_amount']);
        $order->setStatus($row['status']);
        $order->setCreatedAt($row['created_at']);
        return $order;
    }

    // Map DB row to OrderItem entity.
    private function mapOrderItem($row) {
        $item = new OrderItem();
        $item->setId($row['id']);
        $item->setOrderId($row['order_id']);
        $item->setProductId($row['product_id']);
        $item->setProductName($row['product_name']);
        $item->setQuantity($row['quantity']);
        $item->setPrice($row['price']);

        return $item;
    }

    private function insertOrder(Order $order) {
        $this->db->query("INSERT INTO orders (user_id, total_amount, status, created_at) 
                        VALUES (:user_id, :total_amount, :status, NOW())");
        $this->db->bind(':user_id', $order->getUserId());
        $this->db->bind(':total_amount', $order->getTotalAmount());
        $this->db->bind(':status', $order->getStatus());
        $this->db->execute();
        return $this->db->lastInsertId();
    }

    private function insertOrderItem($orderId, OrderItem $item) {
        $this->db->query("INSERT INTO order_items (order_id, product_id, quantity, price) 
                        VALUES (:order_id, :product_id, :quantity, :price)");
        $this->db->bind(':order_id', $orderId);
        $this->db->bind(':product_id', $item->getProductId());
        $this->db->bind(':quantity', $item->getQuantity());
        $this->db->bind(':price', $item->getPrice());
        $this->db->execute();
    }

    private function updateStock(OrderItem $item) {
        $this->db->query("UPDATE products SET quantity = quantity - :qty WHERE id = :id");
        $this->db->bind(':qty', $item->getQuantity());
        $this->db->bind(':id', $item->getProductId());
        $this->db->execute();
    }
}
