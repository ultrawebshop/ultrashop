<?php
session_start();

// Check if the user is logged in and their role
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'Admin';
$isLoggedIn = isset($_SESSION['user_id']);
$userFirstName = isset($_SESSION['firstname']) ? $_SESSION['firstname'] : ''; // Assuming you store the first name in the session

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

// Verwerk het formulier voor het toevoegen van een product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    // Verwijder het product
    $productId = $_POST['product_id'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id = :id");
    $stmt->execute([':id' => $productId]);

    // Redirect naar dezelfde pagina zodat de lijst wordt herladen
    header("Location: beheerproducten.php");
    exit;
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $uploadDir = "uploads/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Zorg dat de uploads-map bestaat
    }

    $imagePath = $uploadDir . basename($_FILES['image']['name']);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
        // Voeg product toe aan de database
        $stmt = $conn->prepare("INSERT INTO products (name, brand, price, description, category, image_path) VALUES (:name, :brand, :price, :description, :category, :image)");
        $stmt->execute([
            ':name' => $name,
            ':brand' => $brand,
            ':price' => $price,
            ':description' => $description,
            ':category' => $category,
            ':image' => $imagePath,
        ]);
        $successMessage = "Product succesvol toegevoegd!";

        // Redirect na het toevoegen van het product om dubbele invoer te voorkomen
        header("Location: beheerproducten.php");
        exit; // Zorg ervoor dat de verdere uitvoering stopt
    } else {
        $errorMessage = "Er is een probleem opgetreden bij het uploaden van de afbeelding.";
    }
}

// Controleer of er een filter is geselecteerd
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';

// Haal producten op op basis van de gekozen categorie
$query = "SELECT * FROM products";
if ($categoryFilter) {
    $query .= " WHERE category = :category";
}

$stmt = $conn->prepare($query);

if ($categoryFilter) {
    $stmt->bindParam(':category', $categoryFilter);
}

$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Haal categorieën op voor de dropdown
$categories = ['Vape', 'Horloge', 'AirPods'];
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beheer Producten</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            color: #ffffff;
        }
        .welcome-area {
            display: flex;
            align-items: center;
            justify-content: flex-end; /* Dit zorgt ervoor dat de "Welkom, User" rechts komt te staan */
            flex-grow: 1;
        }

        .navbar-dark .navbar-nav .nav-link {
            color: #ffffff;
        }
        .navbar-dark .navbar-brand {
            color: #ffffff;
        }
        .container {
            background-color: #1e1e1e;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }
        .form-control, .form-select {
            background-color: #2c2c2c;
            color: #ffffff;
            border: 1px solid #444;
        }
        .form-control:focus, .form-select:focus {
            background-color: #333;
            color: #ffffff;
            border-color: #555;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .alert {
            border-radius: 5px;
        }
        .product-table {
            margin-top: 30px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">UltraShop</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="vapes.php">Vapes</a></li>
                <li class="nav-item"><a class="nav-link" href="horloges.php">Horloges</a></li>
                <li class="nav-item"><a class="nav-link" href="airpods.php">AirPods</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                <?php if ($isAdmin): ?>
                    <li class="nav-item"><a class="nav-link" href="beheerproducten.php">Beheer Producten</a></li>
                    <li class="nav-item"><a class="nav-link" href="beheerbestellingen.php">Beheer Bestellingen</a></li>
                    <li class="nav-item"><a class="nav-link" href="beheergebruikers.php">Beheer Gebruikers</a></li>
                <?php endif; ?>

                <?php if (!$isLoggedIn): ?>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="register.php">Registreren</a></li>
                <?php endif; ?>
            </ul>

            <!-- Verplaats de welcome-area naar de rechterkant -->
            <div class="welcome-area">
                <?php if ($isLoggedIn): ?>
                    <span class="nav-link text-light me-2">Welkom, <?php echo htmlspecialchars($userFirstName); ?></span>
                    <div class="profile-icon" id="profileIcon" onclick="toggleDropdown()">
                        <span>User</span>
                    </div>
                    <div class="dropdown-menu profile-dropdown" id="profileDropdown" style="display: none;">
                        <a class="dropdown-item" href="logout.php">Uitloggen</a>
                        <a class="dropdown-item" href="account.php">Account Beheren</a>
                        <a class="dropdown-item" href="bestellingen.php">Bestellingen</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h1 class="text-center">Beheer Producten</h1>

    <?php if (isset($successMessage)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($successMessage); ?></div>
    <?php endif; ?>
    <?php if (isset($errorMessage)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($errorMessage); ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Naam</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="brand" class="form-label">Merk</label>
            <input type="text" class="form-control" id="brand" name="brand" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Prijs</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Beschrijving</label>
            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">Categorie</label>
            <select class="form-select" id="category" name="category" required>
                <option value="Vape">Vape</option>
                <option value="Horloge">Horloge</option>
                <option value="AirPods">AirPods</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Afbeelding</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-primary">Product Toevoegen</button>
    </form>

    <!-- Filter voor categorie -->
    <form method="GET" action="" class="mt-4 mb-4">
        <label for="category" class="form-label">Filter op Categorie</label>
        <select class="form-select" id="category" name="category">
            <option value="">Alle</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category ?>" <?= $category === $categoryFilter ? 'selected' : '' ?>>
                    <?= ucfirst($category) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-primary mt-2">Filteren</button>
    </form>

    <!-- Producten Tabel -->
    <table class="table table-dark table-striped product-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Naam</th>
            <th>Beschrijving</th>
            <th>Prijs</th>
            <th>Categorie</th>
            <th>Acties</th>
        </tr>
        </thead>
        <tbody>
        <?php if (count($products) > 0): ?>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['id']) ?></td>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= htmlspecialchars($product['description']) ?></td>
                    <td>&euro; <?= number_format($product['price'], 2) ?></td>
                    <td><?= ucfirst(htmlspecialchars($product['category'])) ?></td>
                    <td>
                        <!-- Bewerken knop -->
                        <a href="edit_product.php?id=<?= $product['id'] ?>" class="btn btn-warning btn-sm">Bewerken</a>

                        <!-- Verwijderen knop -->
                        <form method="POST" action="beheerproducten.php" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Weet je zeker dat je dit product wilt verwijderen?')">Verwijderen</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center">Geen producten gevonden</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
