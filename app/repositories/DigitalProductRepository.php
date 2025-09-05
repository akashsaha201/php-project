<?php
class DigitalProductRepository
{
    private $db;
    private $productRepo;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function insert(DigitalProduct $digital)
    {
        $this->db->query("INSERT INTO digital_products (product_id, file_size, download_link)
                          VALUES (:product_id, :file_size, :download_link)");
        $this->db->bind(':product_id', $digital->getId());
        $this->db->bind(':file_size', $digital->getFileSize());
        $this->db->bind(':download_link', $digital->getDownloadLink());
        $this->db->execute();
    }

    public function update(DigitalProduct $product): bool
    {
        $this->productRepo = new ProductRepository();
        // Update products first
        $this->productRepo->update($product);

        // Update digital_products
        $this->db->query("UPDATE digital_products 
                          SET file_size = :file_size, download_link = :download_link
                          WHERE product_id = :product_id");
        $this->db->bind(':product_id', $product->getId());
        $this->db->bind(':file_size', $product->getFileSize());
        $this->db->bind(':download_link', $product->getDownloadLink());
        return $this->db->execute();
    }

    public function getById(int $id)
    {
        $this->db->query("SELECT p.*, d.file_size, d.download_link, c.type AS category_type
                      FROM products p
                      JOIN categories c ON p.category_id = c.id
                      JOIN digital_products d ON p.id = d.product_id
                      WHERE p.id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

}
