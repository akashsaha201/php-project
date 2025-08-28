<?php
require_once 'Product.php';

class PhysicalProduct extends Product {
    private $shipping_cost;
    private $weight;

    public function __construct(PDO $conn, $name, $price, $category_id, $email, $shipping_cost, $weight, $id = null) {
        parent::__construct($conn, $name, $price, $category_id, $email, $id);
        $this->shipping_cost = $shipping_cost;
        $this->weight = $weight;
    }

    // Getters and Setters
    public function getShippingCost() { return $this->shipping_cost; }
    public function setShippingCost($shipping_cost) { $this->shipping_cost = $shipping_cost; }

    public function getWeight() { return $this->weight; }
    public function setWeight($weight) { $this->weight = $weight; }

    // Insert physical product
    public function addProduct() {
        $sql = "INSERT INTO products_with_email 
                (name, email, price, category_id, product_type, shipping_cost, weight) 
                VALUES (:name, :email, :price, :category_id, :product_type, :shipping_cost, :weight)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name' => $this->getName(),
            ':email' => $this->getEmail(),
            ':price' => $this->getPrice(),
            ':category_id' => $this->getCategoryId(),
            ':product_type' => 'physical',
            ':shipping_cost' => $this->getShippingCost(),
            ':weight' => $this->getWeight()
        ]);
    }

    // Update physical product
    public function updateProduct() {
        if (!$this->getId()) return false;
        $sql = "UPDATE products_with_email 
                SET name=:name, email=:email, price=:price, category_id=:category_id, product_type=:product_type,
                    shipping_cost=:shipping_cost, weight=:weight 
                WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name' => $this->getName(),
            ':email' => $this->getEmail(),
            ':price' => $this->getPrice(),
            ':category_id' => $this->getCategoryId(),
            ':product_type' => 'physical',
            ':shipping_cost' => $this->getShippingCost(),
            ':weight' => $this->getWeight(),
            ':id' => $this->getId()
        ]);
    }
}
