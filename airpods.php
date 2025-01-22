<?php
session_start();

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

// Zoekfunctie
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Haal alle producten op uit de categorie "AirPods" met de zoekterm (indien aanwezig)
$query = "SELECT * FROM products WHERE category = 'AirPods' AND name LIKE :searchTerm";
$stmt = $conn->prepare($query);
$stmt->execute([':searchTerm' => '%' . $searchTerm . '%']);
$airpods = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if the user is logged in and their role
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'Admin';
$isLoggedIn = isset($_SESSION['user_id']);
$userFirstName = isset($_SESSION['firstname']) ? $_SESSION['firstname'] : ''; // Assuming you store the first name in the session
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AirPods</title>
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
        .product-card {
            background-color: #2c2c2c;
            color: #ffffff;
            border: 1px solid #444;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        .product-card img {
            max-width: 100%;
            height: auto;
        }
        .product-card h5 {
            font-size: 1.2rem;
        }
        .product-card p {
            font-size: 1rem;
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
    <h1 class="text-center">AirPods</h1>

    <!-- Zoekbalk -->
    <form method="GET" action="airpods.php" class="mb-4">
        <div class="input-group">
            <input type="text" class="form-control" name="search" value="<?= htmlspecialchars($searchTerm) ?>" placeholder="Zoek op naam...">
            <button class="btn btn-primary" type="submit">Zoeken</button>
        </div>
    </form>

    <!-- Producten -->
    <div class="row">
        <?php if (count($airpods) > 0): ?>
            <?php foreach ($airpods as $airpod): ?>
                <div class="col-md-4 mb-4">
                    <div class="product-card">
                        <a href="product_detail.php?id=<?= $airpod['id'] ?>">
                            <img src="<?= htmlspecialchars($airpod['image_path']) ?>" alt="<?= htmlspecialchars($airpod['name']) ?>">
                            <h5><?= htmlspecialchars($airpod['name']) ?></h5>
                            <p>&euro; <?= number_format($airpod['price'], 2) ?></p>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <p class="text-center">Geen AirPods gevonden voor jouw zoekopdracht.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

