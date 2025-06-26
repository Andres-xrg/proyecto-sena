<?php
// session_start();
require_once __DIR__ . '/../functions/lang.php'; // o ajusta la ruta según tu estructura
?>
   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <div class="header-container">
        <img src="/proyecto-sena/assets/img/JKE.png" alt="JKE Logo" class="logo-jke" />

    <h1 class="titulo-header">
        Bienvenido<?php echo isset($_SESSION['usuario']['nombre']) ? ', ' . htmlspecialchars($_SESSION['usuario']['nombre']) : ''; ?>
    </h1>


        <img src="/proyecto-sena/assets/img/logo-sena.png" alt="SENA Logo" class="logo-sena" />
    </div>

    <nav class="nav-header">
        <a href="index.php?page=components/principales/welcome">Inicio</a>
        <a href="index.php?page=components/principales/programas_formacion">Programas de formación</a>
        <a href="index.php?page=components/instructores/instructores">Instructores</a>
        <a href="index.php?page=components/registros/registro_user">Registro Usuarios</a>
        
        <a href="index.php?page=components/principales/ver_historial"  class="history">
            <i class="fas fa-history"></i>
        </a>
    </nav>

<div class="global">
    <form id="langForm" method="GET">
        <input type="hidden" name="lang" value="<?= ($_SESSION['lang'] ?? 'es') === 'es' ? 'en' : 'es' ?>">
        <button type="submit" style="background: none; border: none; color: inherit; cursor: pointer;">
            <i class="fas fa-globe"></i>
            <?= ($_SESSION['lang'] ?? 'es') === 'es' ? '' : '' ?>
        </button>
    </form>
</div>

    <div class="style-switcher">
        <div class="day-night s-icon" id="modoOscuroBtn">
            <i class="fas fa-moon"></i>
        </div>
    </div>
    <div class="login-icon">
        <a href="index.php?page=components/principales/logout" title="Cerrar sesión">
            <i class="fas fa-right-from-bracket"></i>
        </a>
    </div>


    <script src="assets/js/main.js"></script>