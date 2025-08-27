<?php
class Product
{
    public $id;
    public $name;
    public $price;
    public $category_id;
    public $email;
    public $conn;

     public function __construct(PDO $conn, $name, $price, $category_id, $email, $id = null) {
        $this->conn = $conn;
        $this->id = $id; 
        $this->name = $name;
        $this->price = $price;
        $this->category_id = $category_id;
        $this->email = $email;
    }

     public function addProduct() {
        $sql = "INSERT INTO products_with_email (name, email, price, category_id) 
                VALUES (:name, :email, :price, :category_id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':name' => $this->name,
            ':email' => $this->email,
            ':price' => $this->price,
            ':category_id' => $this->category_id ?: null
        ]);
    }

    public function updateProduct() {
        if (!$this->id) return false;
        $sql = "UPDATE products_with_email 
                SET name=:name, email=:email, price=:price, category_id=:category_id 
                WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name' => $this->name,
            ':email' => $this->email,
            ':price' => $this->price,
            ':category_id' => $this->category_id ?: null,
            ':id' => $this->id
        ]);
    }

    public function deleteProduct() {
    if (!$this->id) return false; 
    $stmt = $this->conn->prepare("DELETE FROM products_with_email WHERE id = :id");
    return $stmt->execute([':id' => $this->id]);
    }

    public static function getById(PDO $conn, $id) {
        $stmt = $conn->prepare("SELECT * FROM products_with_email WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getAll(PDO $conn) {
        $sql = "SELECT p.id, p.name, p.email, p.price, c.name AS category 
                FROM products_with_email p
                LEFT JOIN categories c ON p.category_id = c.id";
        return $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>