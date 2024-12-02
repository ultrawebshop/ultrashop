<?php
session_start();
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Verwijder item uit winkelwagen
if (isset($_POST['remove'])) {
    $itemToRemove = $_POST['remove'];
    foreach ($cart as $key => $item) {
        if ($item['name'] === $itemToRemove) {
            unset($cart[$key]);
            break;
        }
    }
    $_SESSION['cart'] = array_values($cart);
}

// Update aantal van een item
if (isset($_POST['update_quantity'])) {
    $itemToUpdate = $_POST['item_name'];
    $newQuantity = (int)$_POST['quantity'];

    foreach ($cart as $key => $item) {
        if ($item['name'] === $itemToUpdate) {
            if ($newQuantity > 0) {
                $cart[$key]['quantity'] = $newQuantity;
            } else {
                unset($cart[$key]);
            }
            break;
        }
    }
    $_SESSION['cart'] = array_values($cart);
}

// Afreken logica
if (isset($_POST['checkout'])) {
    $totalAmount = 0;
    $orderNumber = rand(1000, 9999); // Genereer een ordernummer
    $_SESSION['order_number'] = $orderNumber; // Sla het ordernummer op in de sessie
    $_SESSION['order_details'] = []; // Maak een array om bestelgegevens op te slaan

    foreach ($cart as $item) {
        $itemTotal = $item['price'] * $item['quantity'];
        $_SESSION['order_details'][] = [
            'name' => $item['name'],
            'quantity' => $item['quantity'],
            'total' => $itemTotal
        ];
        $totalAmount += $itemTotal;
    }

    // Leeg de winkelwagen
    unset($_SESSION['cart']);

    // Redirect naar bevestiging
    header("Location: confirmation.php");
    exit; // Stop het script
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Winkelwagen - UltraShop</title>
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
                            <a class="nav-link position-relative" href="#">
                                <span class="d-inline-flex align-items-center">
                                    <i class="bi bi-cart3"></i> Winkelwagen
                                </span>
                                <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle">
                                    <?= count($cart); ?>
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
    <h2>Winkelwagen</h2>
    <?php if (empty($cart)): ?>
        <p>Je winkelwagen is leeg.</p>
        <a href="index.php" class="btn btn-primary">Verder winkelen</a>
    <?php else: ?>
        <table class="table table-dark table-striped">
            <thead>
            <tr>
                <th>Product</th>
                <th>Prijs</th>
                <th>Aantal</th>
                <th>Totaal</th>
                <th>Acties</th>
            </tr>
            </thead>
            <tbody>
            <?php $total = 0; ?>
            <?php foreach ($cart as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']); ?></td>
                    <td>€<?= number_format($item['price'], 2); ?></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="item_name" value="<?= htmlspecialchars($item['name']); ?>">
                            <input type="number" name="quantity" value="<?= $item['quantity']; ?>" min="0" style="width: 60px;">
                            <button type="submit" name="update_quantity" class="btn btn-warning btn-sm">Bijwerken</button>
                        </form>
                    </td>
                    <td>€<?= number_format($item['price'] * $item['quantity'], 2); ?></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="remove" value="<?= htmlspecialchars($item['name']); ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Verwijderen</button>
                        </form>
                    </td>
                </tr>
                <?php $total += $item['price'] * $item['quantity']; ?>
            <?php endforeach; ?>
            </tbody>
        </table>
        <h3>Totaal: €<?= number_format($total, 2); ?></h3>
        <form method="post">
            <button type="submit" name="checkout" class="btn btn-success">Afrekenen</button>
        </form>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>