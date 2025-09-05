<?php

class CategoryRepository {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Get all categories
    public function getAll() {
        $this->db->query("SELECT * FROM categories ORDER BY name ASC");
        return $this->db->resultSet();
    }

    // Get categories by type (digital or physical)
    public function getByType($type) {
        $this->db->query("SELECT * FROM categories WHERE type = :type ORDER BY name ASC");
        $this->db->bind(':type', $type);
        return $this->db->resultSet();
    }
}
