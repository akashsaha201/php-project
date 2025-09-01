<?php
class Product {
    protected $id;
    protected $name;
    protected $email;
    protected $price;
    protected $category_id;

    public function __construct($name, $email, $price, $category_id, $id = null) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->price = $price;
        $this->category_id = $category_id;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getEmail() { return $this->email; }
    public function getPrice() { return $this->price; }
    public function getCategoryId() { return $this->category_id; }

    // Setters
    public function setName($name) { $this->name = $name; }
    public function setEmail($email) { $this->email = $email; }
    public function setPrice($price) { $this->price = $price; }
    public function setCategoryId($category_id) { $this->category_id = $category_id; }
}
