<?php
session_start();

// Controleer of er een ordernummer en orderdetails zijn opgeslagen in de sessie
$orderNumber = isset($_SESSION['order_number']) ? $_SESSION['order_number'] : null;
$orderDetails = isset($_SESSION['order_details']) ? $_SESSION['order_details'] : [];
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bevestiging - UltraShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">
<header class="py-4">
    <div class="container">
        <h1 class="text-center">Bevestiging van je bestelling</h1>
    </div>
</header>

<main class="container my-5">
    <?php if ($orderNumber): ?>
        <h4>Dank je wel voor je bestelling!</h4>
        <p>Je ordernummer is: <strong><?= htmlspecialchars($orderNumber); ?></strong></p>

        <h5>Bestelling:</h5>
        <ul class="list-group">
            <?php foreach ($orderDetails as $item): ?>
                <li class="list-group-item bg-dark text-white">
                    <?= htmlspecialchars($item['name']); ?> - Aantal: <?= intval($item['quantity']); ?> - Totaal: €<?= number_format($item['total'], 2); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Er is iets misgegaan. Geen ordernummer gevonden.</p>
    <?php endif; ?>
</main>

<footer class="bg-dark text-white py-3 text-center">
    <p>© 2024 UltraShop. Alle rechten voorbehouden.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>