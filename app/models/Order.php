<?php


class Order {
    private $id;
    private $userId;
    private $totalAmount;
    private $status;
    private $createdAt;
    private $items = []; 

    //Getters
    public function getId() {
        return $this->id;
    }
    public function getUserId() {
        return $this->userId;
    }
    public function getTotalAmount() {
        return $this->totalAmount;
    }
    public function getStatus() {
        return $this->status;
    }
    public function getCreatedAt() {
        return $this->createdAt;
    }
    public function getItems() {
        return $this->items;
    }
    
    // Setters
    public function setId($id) {
        $this->id = $id;
    }
    public function setUserId($userId) {
        $this->userId = $userId;
    }
    public function setTotalAmount($amount) {
        $this->totalAmount = $amount;
    }
    public function setStatus($status) {
        $this->status = $status;
    }
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }
    public function setItems(array $items) {
        $this->items = $items;
    }
    public function addItem(OrderItem $item) {
        $this->items[] = $item;
    }
}
