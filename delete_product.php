<?php
require 'db.php';
require 'Product.php';

$id = $_GET['id'] ?? null;

if ($id) {

    $productData = Product::getById($conn, $id);

    if ($productData) {
        
        $product = new Product(
            $conn,
            $productData['name'],
            $productData['price'],
            $productData['category_id'],
            $productData['email'],
            $productData['id']   
        );

        $product->deleteProduct();
    }
}

header("Location: index.php");
exit;
