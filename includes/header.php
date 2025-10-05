<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../functions/lang.php';
?>

<?php if (isset($_SESSION['usuario'])): ?>
<!-- FONT AWESOME -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- BOTÓN HAMBURGUESA -->
<button class="sidebar-toggle" id="sidebarToggle">
    <i class="fas fa-bars"></i>
</button>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logos-container">
            <img src="/proyecto-sena/assets/img/JKE.png" alt="Logo JKE" class="logo-jke">
            <img src="/proyecto-sena/assets/img/logo-sena.png" alt="Logo SENA" class="logo-sena">
        </div>
        <h1 class="titulo-header">
            <?= $translations['welcome'] ?>
            <?= isset($_SESSION['usuario']['nombre']) ? ', ' . htmlspecialchars($_SESSION['usuario']['nombre']) : '' ?>
        </h1>
        <!-- Mostrar rol debajo del nombre -->
        <?php if (isset($_SESSION['usuario']['rol'])): ?>
            <p class="user-role" style="margin: 5px 0 0 0; font-size: 14px; color: #ccc;">
                <?= ucfirst(strtolower($_SESSION['usuario']['rol'])) ?>
            </p>
        <?php endif; ?>
    </div>

    <nav class="sidebar-nav">
        <a href="index.php?page=components/principales/welcome" class="nav-item">
            <i class="fas fa-home"></i><span><?= $translations['home'] ?></span>
        </a>
        <a href="index.php?page=components/principales/programas_formacion" class="nav-item">
            <i class="fas fa-graduation-cap"></i><span><?= $translations['training_programs'] ?></span>
        </a>
        <a href="index.php?page=components/instructores/instructores" class="nav-item">
            <i class="fas fa-chalkboard-teacher"></i><span><?= $translations['instructors'] ?></span>
        </a>

        <?php if (strtolower($_SESSION['usuario']['rol']) === 'administrador'): ?>
            <a href="index.php?page=components/usuarios/usuarios" class="nav-item">
                <i class="fas fa-users"></i><span><?= $translations['users'] ?></span>
            </a>
            <a href="index.php?page=components/registros/registro_user" class="nav-item">
                <i class="fas fa-user-plus"></i><span><?= $translations['register_users'] ?></span>
            </a>
            <a href="./components/principales/ver_historial.php" class="nav-item">
                <i class="fas fa-history"></i><span><?= $translations['history'] ?? 'Historial' ?></span>
            </a>
        <?php endif; ?>
    </nav>

    <div class="sidebar-utilities">
        <a href="index.php?page=components/principales/editar_perfil" class="utility-item">
            <i class="fas fa-user"></i><span><?= $translations['edit_profile'] ?? 'Editar perfil' ?></span>
        </a>

        <!-- BOTÓN DE CAMBIO DE IDIOMA -->
        <?php
            $currentUrl = $_SERVER['REQUEST_URI'];
            $currentUrl = preg_replace('/([&?])lang=[^&]*/', '', $currentUrl);
            $separator = (strpos($currentUrl, '?') !== false) ? '&' : '?';
            $targetUrl = $currentUrl . $separator . "lang=" . (($_SESSION['lang'] ?? 'es') === 'es' ? 'en' : 'es');
        ?>
        <div class="utility-item global">
            <a href="<?= htmlspecialchars($targetUrl) ?>" style="display: flex; align-items: center; width: 100%; color: white; text-decoration: none; cursor: pointer;">
                <i class="fas fa-globe" style="width: 20px; margin-right: 15px; font-size: 16px;"></i>
                <span style="font-size: 14px;"><?= ($_SESSION['lang'] ?? 'es') === 'es' ? 'English' : 'Español' ?></span>
            </a>
        </div>

        <div class="utility-item" id="modoOscuroBtn">
            <i class="fas fa-moon"></i><span><?= $translations['dark_mode'] ?? 'Modo Oscuro' ?></span>
        </div>

        <a href="index.php?page=components/principales/logout" class="utility-item logout">
            <i class="fas fa-right-from-bracket"></i><span><?= $translations['logout'] ?? 'Cerrar sesión' ?></span>
        </a>
    </div>
</div>

<!-- OVERLAY MÓVIL -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- SCRIPT TOGGLE DEL SIDEBAR -->
<script>
document.addEventListener("DOMContentLoaded", () => {
    const sidebarToggle = document.getElementById("sidebarToggle");
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("sidebarOverlay");

    sidebarToggle.addEventListener("click", () => {
        sidebar.classList.toggle("sidebar-open");
        overlay.classList.toggle("active");
    });

    overlay.addEventListener("click", () => {
        sidebar.classList.remove("sidebar-open");
        overlay.classList.remove("active");
    });
});
</script>

<!-- SCRIPT MODO OSCURO -->
<script>
document.addEventListener("DOMContentLoaded", () => {
  const body = document.body;
  const themeToggleBtn = document.getElementById("modoOscuroBtn");
  const themeIcon = themeToggleBtn ? themeToggleBtn.querySelector("i") : null;

  const applyTheme = (theme) => {
    if (theme === "dark") {
      body.classList.add("dark");
      if (themeIcon) {
        themeIcon.classList.remove("fa-moon");
        themeIcon.classList.add("fa-sun");
      }
    } else {
      body.classList.remove("dark");
      if (themeIcon) {
        themeIcon.classList.remove("fa-sun");
        themeIcon.classList.add("fa-moon");
      }
    }
  };

  const savedTheme = localStorage.getItem("theme");
  if (savedTheme) {
    applyTheme(savedTheme);
  } else {
    if (window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches) {
      applyTheme("dark");
    } else {
      applyTheme("light");
    }
  }

  if (themeToggleBtn) {
    themeToggleBtn.addEventListener("click", () => {
      const isDark = body.classList.contains("dark");
      const newTheme = isDark ? "light" : "dark";
      applyTheme(newTheme);
      localStorage.setItem("theme", newTheme);
    });
  }

  window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change", (e) => {
    if (!localStorage.getItem("theme")) {
      applyTheme(e.matches ? "dark" : "light");
    }
  });
});
</script>
<?php endif; ?>
