<?php
session_start();

// Verwijder alle sessievariabelen
$_SESSION = [];

// Vernietig de sessie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Vernietig de sessie
session_destroy();

// Redirect naar de homepage of de loginpagina
header("Location: index.php"); // Of "login.php" afhankelijk van waar je naartoe wilt sturen
exit;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uitloggen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">
<div class="container text-center mt-5">
    <h1>U bent met succes uitgelogd</h1>
    <p>U wordt nu doorgestuurd...</p>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
