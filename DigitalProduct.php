<?php
require_once 'Product.php';

class DigitalProduct extends Product
{
    private $file_size;
    private $download_link;

    public function __construct(PDO $conn, $name, $price, $category_id, $email, $file_size, $download_link, $id = null)
    {
        parent::__construct($conn, $name, $price, $category_id, $email, $id);
        $this->file_size = $file_size;
        $this->download_link = $download_link;
    }

    // Getters and Setters
    public function getFileSize()
    {
        return $this->file_size;
    }
    public function setFileSize($file_size)
    {
        $this->file_size = $file_size;
    }

    public function getDownloadLink()
    {
        return $this->download_link;
    }
    public function setDownloadLink($download_link)
    {
        $this->download_link = $download_link;
    }

    // Insert digital product
    public function addProduct()
    {
        $sql = "INSERT INTO products_with_email 
            (name, email, price, category_id, product_type, file_size, download_link) 
            VALUES (:name, :email, :price, :category_id, :product_type, :file_size, :download_link)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name' => $this->getName(),
            ':email' => $this->getEmail(),
            ':price' => $this->getPrice(),
            ':category_id' => $this->getCategoryId(),
            ':product_type' => 'digital',
            ':file_size' => $this->getFileSize(),
            ':download_link' => $this->getDownloadLink()
        ]);
    }


    // Update digital product
    public function updateProduct()
    {
        if (!$this->getId())
            return false;
        $sql = "UPDATE products_with_email 
            SET name=:name, email=:email, price=:price, category_id=:category_id, 
                product_type=:product_type, file_size=:file_size, download_link=:download_link 
            WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name' => $this->getName(),
            ':email' => $this->getEmail(),
            ':price' => $this->getPrice(),
            ':category_id' => $this->getCategoryId(),
            ':product_type' => 'digital',
            ':file_size' => $this->getFileSize(),
            ':download_link' => $this->getDownloadLink(),
            ':id' => $this->getId()
        ]);
    }

}
