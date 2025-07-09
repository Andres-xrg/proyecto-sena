<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../functions/lang.php';
?>

<?php if (isset($_SESSION['usuario'])): ?>
    <!-- Botón toggle SOLO para móvil -->
    <button class="sidebar-toggle" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar fijo en desktop, desplegable en móvil -->
    <div class="sidebar" id="sidebar">
        <!-- Header del sidebar -->
        <div class="sidebar-header">
            <div class="logos-container">
                <img src="/proyecto-sena/assets/img/JKE.png" alt="JKE Logo" class="logo-jke" />
                <img src="/proyecto-sena/assets/img/logo-sena.png" alt="SENA Logo" class="logo-sena" />
            </div>
            
            <h1 class="titulo-header">
                <?= $translations['welcome'] ?>
                <?= isset($_SESSION['usuario']['nombre']) ? ', ' . htmlspecialchars($_SESSION['usuario']['nombre']) : '' ?>
            </h1>
        </div>

        <!-- Navegación -->
        <nav class="sidebar-nav">
            <a href="index.php?page=components/principales/welcome" class="nav-item">
                <i class="fas fa-home"></i>
                <span><?= $translations['home'] ?></span>
            </a>
            <a href="index.php?page=components/principales/programas_formacion" class="nav-item">
                <i class="fas fa-graduation-cap"></i>
                <span><?= $translations['training_programs'] ?></span>
            </a>
            <a href="index.php?page=components/instructores/instructores" class="nav-item">
                <i class="fas fa-chalkboard-teacher"></i>
                <span><?= $translations['instructors'] ?></span>
            </a>
            <a href="index.php?page=components/registros/registro_user" class="nav-item">
                <i class="fas fa-user-plus"></i>
                <span><?= $translations['register_users'] ?></span>
            </a>
            <a href="./components/principales/ver_historial.php" class="nav-item">
                <i class="fas fa-history"></i>
                <span><?= $translations['history'] ?? 'Historial' ?></span>
            </a>
        </nav>

        <!-- Utilidades -->
        <div class="sidebar-utilities">
            <!-- Cambio de idioma -->
            <div class="utility-item global">
                <form id="langForm" method="GET" style="display: flex; align-items: center; width: 100%;">
                    <input type="hidden" name="lang" value="<?= ($_SESSION['lang'] ?? 'es') === 'es' ? 'en' : 'es' ?>">
                    <button type="submit" style="background: none; border: none; color: white; cursor: pointer; display: flex; align-items: center; width: 100%;">
                        <i class="fas fa-globe" style="width: 20px; margin-right: 15px; font-size: 16px;"></i>
                        <span style="font-size: 14px;"><?= ($_SESSION['lang'] ?? 'es') === 'es' ? 'English' : 'Español' ?></span>
                    </button>
                </form>
            </div>

            <a href="./components/principales/editar_perfil.php" style="text-decoration: none; color: inherit;">
                <div class="utility-item style-switcher" id="modoOscuroBtn" title="<?= $translations['edit_profile'] ?? 'Editar perfil' ?>">
                    <i class="fas fa-user"></i>
                    <span><?= $translations['edit_profile'] ?? 'Editar perfil' ?></span>
                </div>
            </a>

            <!-- Modo oscuro -->
            <div class="utility-item style-switcher" id="modoOscuroBtn" title="<?= $translations['dark_mode'] ?? 'Cambiar tema' ?>">
                <i class="fas fa-moon"></i>
                <span><?= $translations['dark_mode'] ?? 'Modo Oscuro' ?></span>
            </div>

            <!-- Cerrar sesión -->
            <a href="index.php?page=components/principales/logout" class="utility-item logout" title="<?= $translations['logout'] ?? 'Cerrar sesión' ?>">
                <i class="fas fa-right-from-bracket"></i>
                <span><?= $translations['logout'] ?? 'Cerrar Sesión' ?></span>
            </a>
        </div>
    </div>

    <!-- Overlay para cerrar sidebar en móvil -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php endif; ?>
