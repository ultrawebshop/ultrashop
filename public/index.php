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

// Check if the user is logged in and their role
session_start();
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'Admin';
$isLoggedIn = isset($_SESSION['user_id']);
$userFirstName = isset($_SESSION['firstname']) ? $_SESSION['firstname'] : ''; // Assuming you store the first name in the session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UltraShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #343a40;
            color: #ffffff;
        }

        .navbar-dark .navbar-nav .nav-link {
            color: #ffffff;
        }

        .navbar-dark .navbar-brand {
            color: #ffffff;
        }

        .container {
            background-color: #495057;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .text-center {
            color: #ffffff;
            padding: 20px 0; /* Extra space for better visual */
        }

        h1, p {
            color: #ffffff;
        }

        .profile-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #007bff;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            cursor: pointer;
        }

        .profile-dropdown {
            position: absolute;
            right: 0;
            z-index: 1000;
        }

        .navbar-nav {
            margin-right: auto; /* Push items to the left */
        }

        .welcome-area {
            display: flex;
            align-items: center;
            justify-content: flex-end; /* Aligns to the right */
            flex-grow: 1; /* Allow margin on the left */
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .home-logo {
                display: none; /* Hide logo on smaller screens */
            }
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
<div class="container mt-4">
    <div class="text-center">
        <h1>Welkom bij UltraShop</h1>
        <p>De beste plek voor uw Vapes, AirPods en Horloges!</p>
    </div>
</div>
<script>
    function toggleDropdown() {
        const dropdown = document.getElementById("profileDropdown");
        dropdown.style.display = dropdown.style.display === "none" ? "block" : "none";
    }

    // Click outside to close the dropdown
    window.onclick = function(event) {
        const dropdown = document.getElementById("profileDropdown");
        const profileIcon = document.getElementById("profileIcon");
        if (!profileIcon.contains(event.target) && dropdown.style.display === "block") {
            dropdown.style.display = "none";
        }
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>