<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UltraShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/styles.css">
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
                        <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="cart.php">
                                <span class="d-inline-flex align-items-center">
                                    <i class="bi bi-cart3"></i> Winkelwagen
                                </span>
                                <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle">
                                    <?php
                                    session_start();
                                    echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
                                    ?>
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>

<main class="container my-5">
    <div class="text-center">
        <h2>Onze Producten</h2>
        <p>Bekijk onze exclusieve collectie van AirPods!</p>
    </div>
    <div class="d-flex justify-content-center">
        <div class="card bg-dark text-white" style="width: 18rem;">
            <img src="assets/airpods4.jpg" class="card-img-top" alt="AirPods 4">
            <div class="card-body text-center">
                <h5 class="card-title">AirPods 4</h5>
                <p class="card-text">De nieuwste generatie AirPods met geavanceerde functies.</p>
                <a href="airpods4.php" class="btn btn-primary">Bekijk</a>
            </div>
        </div>
    </div>
</main>

<footer class="bg-dark text-white py-3 text-center">
    <p>&copy; 2024 UltraShop. Alle rechten voorbehouden.</p>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>