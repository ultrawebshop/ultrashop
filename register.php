<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $address = $_POST['address'];
    $snapchat_username = $_POST['snapchat_username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, address, snapchat_username, email, password, role) VALUES (?, ?, ?, ?, ?, ?, 'Klant')");
    $stmt->execute([$firstname, $lastname, $address, $snapchat_username, $email, $password]);

    header("Location: login.php");
    exit;
}

// Check if the user is logged in and their role
session_start();
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'Admin';
$isLoggedIn = isset($_SESSION['user_id']);
$userFirstName = isset($_SESSION['first_name']) ? $_SESSION['first_name'] : ''; // Assuming you store the first name in the session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registreren</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #343a40; /* Dark background */
            color: #ffffff; /* White text */
        }

        .navbar-light .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.8); /* Light links */
        }

        .navbar-light .navbar-brand {
            color: #ffffff; /* White brand name */
        }

        .form-control {
            background-color: #495057; /* Dark input background */
            color: #ffffff; /* White text in input */
            border: 1px solid #6c757d; /* Dark border */
        }

        .form-control:focus {
            background-color: #495057;
            color: #ffffff;
            border-color: #80bdff; /* Blue focus border */
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
        }

        .btn-primary {
            background-color: #007bff; /* Bootstrap primary color */
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3; /* Darker on hover */
        }
    </style>
</head>
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
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>

                <?php if (!$isLoggedIn): // Voeg deze conditie toe ?>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="register.php">Registreren</a></li>
                <?php endif; ?>

            </ul>
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
<body>
<div class="container mt-5">
    <h2 class="text-center">Registreren</h2>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="firstname" class="form-label">Voornaam</label>
            <input type="text" class="form-control" id="firstname" name="firstname" required>
        </div>
        <div class="mb-3">
            <label for="lastname" class="form-label">Achternaam</label>
            <input type="text" class="form-control" id="lastname" name="lastname" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Adres</label>
            <input type="text" class="form-control" id="address" name="address" required>
        </div>
        <div class="mb-3">
            <label for="snapchat_username" class="form-label">Snapchat Gebruikersnaam</label>
            <input type="text" class="form-control" id="snapchat_username" name="snapchat_username">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Wachtwoord</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Registreren</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>