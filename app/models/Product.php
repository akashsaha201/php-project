<?php
class Product {
    protected $id;
    protected $name;
    protected $email;
    protected $price;
    protected $quantity;
    protected $category_id;

    public function __construct($name, $email, $price, $quantity, $category_id, $id = null) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->category_id = $category_id;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getEmail() { return $this->email; }
    public function getPrice() { return $this->price; }
    public function getQuantity() { return $this->quantity; }
    public function getCategoryId() { return $this->category_id; }

    // Setters
    public function setName($name) { $this->name = $name; }
    public function setEmail($email) { $this->email = $email; }
    public function setPrice($price) { $this->price = $price; }
    public function setQuantity($quantity) { $this->quantity = $quantity; }
    public function setCategoryId($category_id) { $this->category_id = $category_id; }
}
