<?php
session_start();

// Controleer of de gebruiker is ingelogd en een admin is
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit;
}

// Databaseverbinding instellen
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ultrashop";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Databaseverbinding mislukt: " . $e->getMessage());
}

// Haal het product op basis van ID
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->bindParam(':id', $productId);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        die("Product niet gevonden!");
    }
} else {
    die("Geen product ID opgegeven!");
}

// Verwerk het formulier voor het bewerken van het product
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $uploadDir = "uploads/";

    // Als er een nieuwe afbeelding is geüpload
    if ($_FILES['image']['name']) {
        // Verwijder de oude afbeelding van de server
        if (file_exists($product['image_path'])) {
            unlink($product['image_path']);
        }

        $imagePath = $uploadDir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
    } else {
        // Gebruik de oude afbeelding als er geen nieuwe is
        $imagePath = $product['image_path'];
    }

    // Update het product in de database
    $stmt = $conn->prepare("UPDATE products SET name = :name, brand = :brand, price = :price, description = :description, category = :category, image_path = :image WHERE id = :id");
    $stmt->execute([
        ':name' => $name,
        ':brand' => $brand,
        ':price' => $price,
        ':description' => $description,
        ':category' => $category,
        ':image' => $imagePath,
        ':id' => $productId
    ]);

    $successMessage = "Product succesvol bijgewerkt!";
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bewerk Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">
<div class="container mt-5">
    <h1>Bewerk Product</h1>

    <?php if (isset($successMessage)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($successMessage); ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Naam</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="brand" class="form-label">Merk</label>
            <input type="text" class="form-control" id="brand" name="brand" value="<?php echo htmlspecialchars($product['brand']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Prijs</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Beschrijving</label>
            <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($product['description']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">Categorie</label>
            <select class="form-select" id="category" name="category" required>
                <option value="Vape" <?php echo $product['category'] === 'Vape' ? 'selected' : ''; ?>>Vape</option>
                <option value="Horloge" <?php echo $product['category'] === 'Horloge' ? 'selected' : ''; ?>>Horloge</option>
                <option value="AirPods" <?php echo $product['category'] === 'AirPods' ? 'selected' : ''; ?>>AirPods</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Afbeelding</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*">
            <?php if ($product['image_path']): ?>
                <img src="<?php echo $product['image_path']; ?>" alt="Product Afbeelding" class="img-fluid mt-2" style="max-width: 200px;">
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary">Product Bijwerken</button>
    </form>

    <a href="beheerproducten.php" class="btn btn-secondary mt-3">Terug naar Producten Beheer</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

