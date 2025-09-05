<?php

class OrderRepository {
    private $db;

    public function __construct() {
        $this->db = new Database();
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

    public function createOrder(Order $order) {
        try {
            $this->db->beginTransaction();

            $orderId = $this->insertOrder($order);
            $order->setId($orderId);

            foreach ($order->getItems() as $item) {
                $this->insertOrderItem($orderId, $item);
            }

            $this->db->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function markOrderSuccessful($orderId) {
        $this->db->beginTransaction();
        try {
            // update status
            $this->updateStatus($orderId, 'successful');

            // get items & update stock
            $this->db->query("SELECT * FROM order_items WHERE order_id = :id");
            $this->db->bind(':id', $orderId);
            $items = $this->db->resultSet();

            foreach ($items as $row) {
                $this->db->query("UPDATE products SET quantity = quantity - :qty WHERE id = :pid");
                $this->db->bind(':qty', $row['quantity']);
                $this->db->bind(':pid', $row['product_id']);
                $this->db->execute();
            }

            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function markOrderFailed($orderId) {
        return $this->updateStatus($orderId, 'failed');
    }

    // Fetch order only if belongs to user
    public function getByIdForUser($id, $userId) {
        $this->db->query("SELECT * FROM orders WHERE id = :id AND user_id = :user_id");
        $this->db->bind(':id', $id);
        $this->db->bind(':user_id', $userId);
        $row = $this->db->single();
        if (!$row) return null;

        $order = $this->mapOrder($row);

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
}
