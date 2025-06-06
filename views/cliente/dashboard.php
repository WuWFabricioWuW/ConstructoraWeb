<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /Fabri_HTML/ConstructoraWeb/views/auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h1>Bienvenido, <?php echo $_SESSION['username']; ?></h1>
    <a href="/Fabri_HTML/ConstructoraWeb/controllers/AuthController.php?action=logout">Cerrar Sesión</a>
</body>
</html>