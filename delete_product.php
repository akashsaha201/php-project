<?php
// ---------------------------
// Required files
// ---------------------------
require 'db.php';       // Database connection
require 'Product.php';  // Product class

// ---------------------------
// Get Product ID from URL
// ---------------------------
$id = $_GET['id'] ?? null;

if ($id) {
    // Fetch product data by ID
    $productData = Product::getById($conn, $id);

    if ($productData) {
        // Create Product object with fetched data
        $product = new Product(
            $conn,
            $productData['name'],
            $productData['price'],
            $productData['category_id'],
            $productData['email'],
            $productData['id'] 
        );

        // Delete the product from DB
        $product->deleteProduct();
    }
}

// ---------------------------
// Redirect back to home page
// ---------------------------
header("Location: index.php");
exit;
