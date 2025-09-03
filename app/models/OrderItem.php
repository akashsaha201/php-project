<?php

class OrderItem {
    private $id;
    private $orderId;
    private $productId;
    private $productName;
    private $quantity;
    private $price;

    // Getters
    public function getId() {
        return $this->id;
    }
    public function getOrderId() {
        return $this->orderId;
    }
    public function getProductId() {
        return $this->productId;
    }
    public function getProductName() {
        return $this->productName;
    }
    public function getQuantity() {
        return $this->quantity;
    }
    public function getPrice() {
        return $this->price;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }
    public function setOrderId($orderId) {
        $this->orderId = $orderId;
    }
    public function setProductId($productId) {
        $this->productId = $productId;
    }
    public function setProductName($productName) {
        $this->productName = $productName;
    }
    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }
    public function setPrice($price) {
        $this->price = $price;
    }
}
