<?php 
class DigitalProduct extends Product {
    private $file_size;
    private $download_link;

    public function __construct($id, $name, $email, $price, $quantity, $category_id, $file_size, $download_link) {
        parent::__construct($name, $email, $price, $quantity, $category_id, $id);
        $this->file_size = $file_size;
        $this->download_link = $download_link;
    }

    public function getFileSize() { return $this->file_size; }
    public function getDownloadLink() { return $this->download_link; }
}

