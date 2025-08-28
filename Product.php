<?php
class Product
{
    protected $id;
    protected $name;
    protected $price;
    protected $category_id;
    protected $email;
    protected $conn;

    public function __construct(PDO $conn, $name, $price, $category_id, $email, $id = null) {
        $this->conn = $conn;
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->category_id = $category_id;
        $this->email = $email;
    }

    // Getters and Setters
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getName() { return $this->name; }
    public function setName($name) { $this->name = $name; }

    public function getPrice() { return $this->price; }
    public function setPrice($price) { $this->price = $price; }

    public function getCategoryId() { return $this->category_id; }
    public function setCategoryId($category_id) { $this->category_id = $category_id; }

    public function getEmail() { return $this->email; }
    public function setEmail($email) { $this->email = $email; }

    // Common delete method
    public function deleteProduct() {
        if (!$this->id) return false;
        $stmt = $this->conn->prepare("DELETE FROM products_with_email WHERE id = :id");
        return $stmt->execute([':id' => $this->id]);
    }

    // Common static methods
    public static function getById(PDO $conn, $id) {
        $stmt = $conn->prepare("SELECT * FROM products_with_email WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getAll(PDO $conn) {
        $sql = "SELECT p.*, c.name AS category 
                FROM products_with_email p
                LEFT JOIN categories c ON p.category_id = c.id
                ORDER BY p.id";
        return $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
