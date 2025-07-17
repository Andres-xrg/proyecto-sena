<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/proyecto-sena/assets/css/header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto</title>
</head>
<body>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="/proyecto-sena/assets/css/sidebar-secundario.css">

<!-- Botón para abrir/cerrar sidebar -->
<button class="sidebar-toggle" id="sidebarToggle">
    <i class="fas fa-bars"></i>
</button>

<!-- Sidebar para usuarios NO logueados -->
<div class="sidebar" id="sidebar">
    <!-- Header del sidebar -->
    <div class="sidebar-header">
        <div class="logos-container">
            <img src="/proyecto-sena/assets/img/JKE.png" alt="JKE Logo" class="logo-jke" />
            <img src="/proyecto-sena/assets/img/logo-sena.png" alt="SENA Logo" class="logo-sena" />
        </div>
        
      <h2 class="titulo-sidebar"><?= $translations['welcome_guest'] ?? 'Bienvenido' ?></h2>
        <p class="subtitle-sidebar"><?= $translations['login_to_access'] ?? 'Inicia sesión para acceder' ?></p>

    </div>

    <!-- Utilidades del sidebar para no logueados -->
    <div class="sidebar-utilities">

        <!-- Modo oscuro -->
            <div class="utility-item style-switcher" id="modoOscuroBtn" title="<?= $translations['dark_mode'] ?? 'Cambiar tema' ?>">
                <i class="fas fa-moon"></i>
                <span><?= $translations['dark_mode'] ?? 'Modo Oscuro' ?></span>
            </div>

        <!-- Botón de traducción -->
        <div class="utility-item global">
            <form id="langForm" method="GET" action="" style="display: flex; align-items: center; width: 100%;">
                <input type="hidden" name="lang" value="<?= ($_SESSION['lang'] ?? 'es') === 'es' ? 'en' : 'es' ?>">
                <button type="submit" style="background: none; border: none; color: white; cursor: pointer; display: flex; align-items: center; width: 100%;">
                    <i class="fas fa-globe" style="width: 20px; margin-right: 15px; font-size: 16px;"></i>
                    <span style="font-size: 14px;"><?= ($_SESSION['lang'] ?? 'es') === 'es' ? 'English' : 'Español' ?></span>
                </button>
            </form>
        </div>

        <!-- Iniciar sesión -->
            <a href="/proyecto-sena/components/principales/login.php" class="utility-item login-btn" title="<?= $translations['login'] ?? 'Iniciar sesión' ?>">
                <i class="fas fa-arrow-right-to-bracket"></i>
                <span><?= $translations['login'] ?? 'Iniciar sesión' ?></span>
            </a>

    </div>
</div>

<script src="/proyecto-sena/assets/js/sidebar-secundario.js"></script>
</body>
</html>
