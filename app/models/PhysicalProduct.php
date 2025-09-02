<?php
class PhysicalProduct extends Product {
    private $weight;
    private $shipping_cost;

    public function __construct($id, $name, $email, $price, $quantity, $category_id, $weight, $shipping_cost) {
        parent::__construct($name, $email, $price, $quantity, $category_id, $id);
        $this->weight = $weight;
        $this->shipping_cost = $shipping_cost;
    }

    public function getWeight() { return $this->weight; }
    public function getShippingCost() { return $this->shipping_cost; }
}

