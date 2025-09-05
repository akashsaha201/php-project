<?php
class PhysicalProductRepository
{
    private $db;
    private $productRepo;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function insert(PhysicalProduct $physical)
    {
        $this->db->query("INSERT INTO physical_products (product_id, weight, shipping_cost)
                          VALUES (:product_id, :weight, :shipping_cost)");
        $this->db->bind(':product_id', $physical->getId());
        $this->db->bind(':weight', $physical->getWeight());
        $this->db->bind(':shipping_cost', $physical->getShippingCost());
        $this->db->execute();
    }

    public function update(PhysicalProduct $product): bool
    {
        $this->productRepo = new ProductRepository();
        // Update products first
        $this->productRepo->update($product);

        // Update physical_products
        $this->db->query("UPDATE physical_products 
                          SET weight = :weight, shipping_cost = :shipping_cost
                          WHERE product_id = :product_id");
        $this->db->bind(':product_id', $product->getId());
        $this->db->bind(':weight', $product->getWeight());
        $this->db->bind(':shipping_cost', $product->getShippingCost());
        return $this->db->execute();
    }
    public function getById(int $id)
    {
        $this->db->query("SELECT p.*, ph.weight, ph.shipping_cost, c.type AS category_type
                      FROM products p
                      JOIN categories c ON p.category_id = c.id
                      JOIN physical_products ph ON p.id = ph.product_id
                      WHERE p.id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

}
