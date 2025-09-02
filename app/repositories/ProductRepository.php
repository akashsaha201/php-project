<?php
class ProductRepository
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function insert(Product $product): int
    {
        $this->db->query("INSERT INTO products (name, email, price, quantity, category_id) 
                          VALUES (:name, :email, :price, :quantity, :category_id)");
        $this->db->bind(':name', $product->getName());
        $this->db->bind(':email', $product->getEmail());
        $this->db->bind(':price', $product->getPrice());
        $this->db->bind(':quantity', $product->getQuantity());
        $this->db->bind(':category_id', $product->getCategoryId());
        $this->db->execute();

        return $this->db->lastInsertId();
    }

    public function update(Product $product): bool
    {
        $this->db->query("UPDATE products SET name = :name, email = :email, 
                          price = :price, quantity = :quantity, category_id = :category_id
                          WHERE id = :id");
        $this->db->bind(':id', $product->getId());
        $this->db->bind(':name', $product->getName());
        $this->db->bind(':email', $product->getEmail());
        $this->db->bind(':price', $product->getPrice());
        $this->db->bind(':quantity', $product->getQuantity());
        $this->db->bind(':category_id', $product->getCategoryId());

        return $this->db->execute();
    }

    public function delete(int $id): bool
    {
        $this->db->query("DELETE FROM products WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
    public function getAll(): array
    {
        $this->db->query("
        SELECT 
            p.id, 
            p.name, 
            p.email, 
            p.price, 
            p.quantity,
            c.name AS category_name, 
            c.type AS category_type,
            d.file_size, 
            d.download_link,
            ph.weight, 
            ph.shipping_cost
        FROM products p
        JOIN categories c ON p.category_id = c.id
        LEFT JOIN digital_products d ON p.id = d.product_id
        LEFT JOIN physical_products ph ON p.id = ph.product_id
        ORDER BY p.name
    ");
        return $this->db->resultSet();
    }


    public function getById(int $id): ?Product
    {
        $this->db->query("SELECT * FROM products WHERE id = :id");
        $this->db->bind(':id', $id);
        $row = $this->db->single();

        if (!$row) {
            return null;
        }

        return new Product(
            $row['name'],
            $row['email'],
            $row['price'],
            $row['quantity'],
            $row['category_id'],
            $row['id']
        );
    }

    public function getProductType($id)
    {
        // check digital_products first
        $this->db->query("SELECT product_id FROM digital_products WHERE product_id = :id");
        $this->db->bind(':id', $id);
        $digital = $this->db->single();

        if ($digital) {
            return 'digital';
        }

        // check physical_products
        $this->db->query("SELECT product_id FROM physical_products WHERE product_id = :id");
        $this->db->bind(':id', $id);
        $physical = $this->db->single();

        if ($physical) {
            return 'physical';
        }

        return null;
    }

}
