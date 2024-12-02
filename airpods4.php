<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['cart'][] = [
        'name' => 'AirPods 4',
        'price' => 49.99,
        'quantity' => $_POST['quantity']
    ];
    header('Location: cart.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AirPods 4 - UltraShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">
<header class="py-4">
    <div class="container">
        <h1 class="display-4 text-center text-light">Welkom bij UltraShop</h1>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">UltraShop</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="cart.php">
                                <span class="d-inline-flex align-items-center">
                                    <i class="bi bi-cart3"></i> Winkelwagen
                                </span>
                                <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle">
                                    <?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>

<div class="container py-5">
    <div class="row">
        <div class="col-md-6">
            <img src="assets/airpods4.jpg" class="img-fluid" alt="AirPods 4">
        </div>
        <div class="col-md-6">
            <h2>AirPods 4</h2>
            <p>De nieuwste generatie AirPods met verbeterde geluidskwaliteit, active noise cancellation en lange batterijduur.</p>
            <p><strong>Prijs:</strong> €49,99</p>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="quantity" class="form-label">Aantal</label>
                    <input type="number" id="quantity" name="quantity" value="1" min="1" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Toevoegen aan winkelwagen</button>
                <a href="cart.php" class="btn btn-success">Bestel meteen</a>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


