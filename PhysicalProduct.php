<?php
require_once 'Product.php';

/**
 * Class PhysicalProduct
 * Represents a physical product with shipping cost and weight.
 * Extends the base Product class.
 */
class PhysicalProduct extends Product {
    
    // ---------------------------
    // Properties (encapsulated)
    // ---------------------------
    private $shipping_cost;
    private $weight;

    // ---------------------------
    // Constructor
    // ---------------------------
    public function __construct(
        PDO $conn,
        $name,
        $price,
        $category_id,
        $email,
        $shipping_cost,
        $weight,
        $id = null
    ) {
        // Call parent Product constructor
        parent::__construct($conn, $name, $price, $category_id, $email, $id);

        $this->shipping_cost = $shipping_cost;
        $this->weight = $weight;
    }

    // ---------------------------
    // Getters and Setters
    // ---------------------------
    public function getShippingCost() {
        return $this->shipping_cost;
    }

    public function setShippingCost($shipping_cost) {
        $this->shipping_cost = $shipping_cost;
    }

    public function getWeight() {
        return $this->weight;
    }

    public function setWeight($weight) {
        $this->weight = $weight;
    }

    // ---------------------------
    // Insert (CREATE)
    // ---------------------------
    public function addProduct() {
        $sql = "INSERT INTO products_with_email 
                (name, email, price, category_id, product_type, shipping_cost, weight) 
                VALUES (:name, :email, :price, :category_id, :product_type, :shipping_cost, :weight)";
        
        $stmt = $this->conn->prepare($sql);
        
        return $stmt->execute([
            ':name'          => $this->getName(),
            ':email'         => $this->getEmail(),
            ':price'         => $this->getPrice(),
            ':category_id'   => $this->getCategoryId(),
            ':product_type'  => 'physical',   // fixed type
            ':shipping_cost' => $this->getShippingCost(),
            ':weight'        => $this->getWeight()
        ]);
    }

    // ---------------------------
    // Update (EDIT)
    // ---------------------------
    public function updateProduct() {
        if (!$this->getId()) {
            return false; // Cannot update without ID
        }

        $sql = "UPDATE products_with_email 
                SET name = :name, 
                    email = :email, 
                    price = :price, 
                    category_id = :category_id, 
                    product_type = :product_type,
                    shipping_cost = :shipping_cost, 
                    weight = :weight 
                WHERE id = :id";
        
        $stmt = $this->conn->prepare($sql);
        
        return $stmt->execute([
            ':name'          => $this->getName(),
            ':email'         => $this->getEmail(),
            ':price'         => $this->getPrice(),
            ':category_id'   => $this->getCategoryId(),
            ':product_type'  => 'physical',   // fixed type
            ':shipping_cost' => $this->getShippingCost(),
            ':weight'        => $this->getWeight(),
            ':id'            => $this->getId()
        ]);
    }
}
