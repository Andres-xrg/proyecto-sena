<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="assets/css/header.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <title>Document</title>
</head>
<body>

    <div class="header-container">
        <img src="/proyecto-sena/assets/img/JKE.png" alt="JKE Logo" class="logo-jke">
        <h1 class="titulo-header">Nombre</h1>
        <img src="/proyecto-sena/assets/img/logo-sena.png" alt="SENA Logo" class="logo-sena">
    </div>

<nav class="nav-header">
        <a href="index.php?page=components/principales/welcome">Inicio</a>
        <a href="index.php?page=components/principales/programas_formacion">Programas de formación</a>
        <a href="index.php?page=components/instructores/instructores">Instructores</a>
        <a href="index.php?page=components/registros/registro_user">Registro Usuarios</a>
    </nav>

    <!-- BOTÓN MODO OSCURO -->
<!-- BOTÓN MODO OSCURO -->
<div class="style-switcher">
    <div class="day-night s-icon" id="modoOscuroBtn">
        <i class="fas fa-moon"></i>
    </div>
</div>

<?php
// Verifica si la sesión ya fue iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- Ícono de login/logout -->
<div class="login-icon">
    <?php if (!isset($_SESSION['usuario'])): ?>
        <!-- Ícono para iniciar sesión -->
        <a href="components/principales/login.php" title="Iniciar sesión">
            <i class="fas fa-arrow-right-to-bracket"></i>
        </a>
    <?php else: ?>
        <!-- Ícono para cerrar sesión o ir al perfil -->
        <a href="index.php?page=components/login/logout" title="Cerrar sesión">
            <i class="fas fa-right-from-bracket"></i>
        </a>
    <?php endif; ?>
</div>
    <script src="assets/js/main.js"></script>
</body>
</html>
