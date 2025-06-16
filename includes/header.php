<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<div class="header-container">
    <img src="/proyecto-sena/assets/img/JKE.png" alt="JKE Logo" class="logo-jke" />

    <h1 class="titulo-header">
        Bienvenido<?php echo isset($_SESSION['nombre']) ? ', ' . htmlspecialchars($_SESSION['nombre']) : ''; ?>
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
    <i class="fas fa-globe"></i> 
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