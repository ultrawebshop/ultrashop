<?php
session_start();

// Database connection setup
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

// Handle login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userInput = $_POST['username'] ?? '';
    $passwordInput = $_POST['password'] ?? '';

    // Query to check if the user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $userInput);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the password is correct
    if ($user && password_verify($passwordInput, $user['password'])) {
        // Store user information in session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['role'] = $user['role'];

        // Redirect to the home page after successful login
        header("Location: index.php");
        exit;
    } else {
        $error = "Ongeldige gebruikersnaam of wachtwoord.";
    }
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
    <title>Inloggen - UltraShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #343a40;
            color: #ffffff;
        }
        .container {
            margin-top: 50px;
            background-color: #495057;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
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
<div class="container">
    <h2 class="text-center">Inloggen</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="username" class="form-label">Gebruikersnaam</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Wachtwoord</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Inloggen</button>
        <p class="mt-3">Nog geen account? <a href="register.php">Registreren</a></p>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>