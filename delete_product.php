<?php
require 'db.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $conn->prepare("DELETE FROM products_with_email WHERE id = :id");
    $stmt->execute([':id' => $id]);
}

header("Location: index.php");
exit;
