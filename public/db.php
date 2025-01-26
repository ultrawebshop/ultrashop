<?php
// Database connection setup (db.php)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ultrashop";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Sample Data
$sampleProducts = [
    ["Vape", "Electronic cigarette device", 20.00, 50],
    ["AirPods", "Wireless earbuds", 199.00, 30],
    ["Horloge", "Stylish wrist watch", 150.00, 20]
];

foreach ($sampleProducts as $product) {
    $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock) VALUES (?, ?, ?, ?)");
    $stmt->execute($product);
}
