<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/proyecto-sena/assets/css/header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/proyecto-sena/assets/css/header-secundario.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto</title>
</head>
<body>

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

<!-- Fondo oscuro que se activa con el menú hamburguesa -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const sidebarToggle = document.getElementById("sidebarToggle");
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("sidebarOverlay");

    if (sidebarToggle && sidebar && overlay) {
      // Abrir el sidebar
      sidebarToggle.addEventListener("click", () => {
        sidebar.classList.toggle("sidebar-open");
        overlay.classList.toggle("active");
      });

      // Cerrar el sidebar al hacer clic en el overlay
      overlay.addEventListener("click", () => {
        sidebar.classList.remove("sidebar-open");
        overlay.classList.remove("active");
      });
    }
  });
</script>
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
</body>
</html>
