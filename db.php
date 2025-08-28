<?php
// ---------------------------
// Database Connection Settings
// ---------------------------
$servername = "localhost";
$username   = "root";
$password   = "Innof!3d";
$database   = "test"; 

try {
    // Create a new PDO connection
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

    // Set PDO to throw exceptions for errors
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Connection successful (you could log or remove this in production)
    // echo "Connected successfully"; 

} catch (PDOException $e) {
    // Connection failed — stop execution and display error
    die("Database connection failed: " . $e->getMessage());
}
?>
