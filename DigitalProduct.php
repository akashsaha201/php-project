<?php
require_once 'Product.php';

/**
 * Class DigitalProduct
 * Represents a digital product (subclass of Product).
 */
class DigitalProduct extends Product
{
    // ---------------------------
    // Properties (specific to digital products)
    // ---------------------------
    private $file_size;
    private $download_link;

    // ---------------------------
    // Constructor
    // ---------------------------
    public function __construct(PDO $conn, $name, $price, $category_id, $email, $file_size, $download_link, $id = null)
    {
        // Call parent constructor (common product fields)
        parent::__construct($conn, $name, $price, $category_id, $email, $id);

        // Assign digital-specific fields
        $this->file_size = $file_size;
        $this->download_link = $download_link;
    }

    // ---------------------------
    // Getters & Setters
    // ---------------------------
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

    // ---------------------------
    // Insert new digital product
    // ---------------------------
    public function addProduct()
    {
        $sql = "INSERT INTO products_with_email 
                (name, email, price, category_id, product_type, file_size, download_link) 
                VALUES (:name, :email, :price, :category_id, :product_type, :file_size, :download_link)";
        
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':name'         => $this->getName(),
            ':email'        => $this->getEmail(),
            ':price'        => $this->getPrice(),
            ':category_id'  => $this->getCategoryId(),
            ':product_type' => 'digital',
            ':file_size'    => $this->getFileSize(),
            ':download_link'=> $this->getDownloadLink()
        ]);
    }

    // ---------------------------
    // Update existing digital product
    // ---------------------------
    public function updateProduct()
    {
        // Ensure product has an ID before updating
        if (!$this->getId()) {
            return false;
        }

        $sql = "UPDATE products_with_email 
                SET name=:name, email=:email, price=:price, category_id=:category_id, 
                    product_type=:product_type, file_size=:file_size, download_link=:download_link 
                WHERE id=:id";
        
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':name'         => $this->getName(),
            ':email'        => $this->getEmail(),
            ':price'        => $this->getPrice(),
            ':category_id'  => $this->getCategoryId(),
            ':product_type' => 'digital',
            ':file_size'    => $this->getFileSize(),
            ':download_link'=> $this->getDownloadLink(),
            ':id'           => $this->getId()
        ]);
    }
}
