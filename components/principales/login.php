<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si hay sesión activa → redirigir al welcome
if (isset($_SESSION['usuario'])) {
    header("Location: /proyecto-sena/index.php?page=components/principales/welcome");
    exit;
}

// Incluir archivo de idioma (debe devolver un array con lang_code y translations)
$lang_data = require __DIR__ . '/../../functions/lang.php'; 
$lang_code = $lang_data['lang_code'] ?? 'es';
$translations = $lang_data['translations'] ?? [];
?>
<!DOCTYPE html>
<html lang="<?= $lang_code ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $translations['login_title'] ?? 'Login' ?></title>
    <link rel="stylesheet" href="/proyecto-sena/assets/css/login.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container-login"> 
        <div class="left-panel"> 
            <div class="wave-top"></div>
            <h1 class="welcome-text"><?= $translations['welcome'] ?? 'BIENVENIDO' ?></h1>
            <div class="wave-bottom"></div>
        </div>

        <div class="right-panel">
            <div class="login-container">
                <h2 class="login-title"><?= $translations['login'] ?? 'INICIA SESIÓN' ?></h2>
                <p class="login-subtitle"><?= $translations['insert_email_password'] ?? 'Ingrese el email y la contraseña' ?></p>

                <div class="user-icon">
                    <img src="/proyecto-sena/assets/img/logo-inicio.png" alt="User Icon">
                </div>

                <!-- Alertas con SweetAlert2 -->
                <?php if (isset($_GET['status'])): ?>
                <script>
                    <?php if ($_GET['status'] == 'correo'): ?>
                        Swal.fire('<?= $translations['email_not_found'] ?? 'Correo no registrado' ?>',
                                  '<?= $translations['email_not_found_desc'] ?? 'El correo ingresado no se encuentra en la base de datos.' ?>',
                                  'warning');
                    <?php elseif ($_GET['status'] == 'contrasena'): ?>
                        Swal.fire('<?= $translations['incorrect_password'] ?? 'Contraseña incorrecta' ?>',
                                  '<?= $translations['incorrect_password_desc'] ?? 'La contraseña no coincide.' ?>',
                                  'error');
                    <?php elseif ($_GET['status'] == 'vacio'): ?>
                        Swal.fire('<?= $translations['empty_fields'] ?? 'Campos vacíos' ?>',
                                  '<?= $translations['fill_all_fields'] ?? 'Por favor completa todos los campos.' ?>',
                                  'info');
                    <?php elseif ($_GET['status'] == 'otro_dispositivo'): ?>
                        Swal.fire('¡Sesión cerrada!',
                                  'Tu cuenta inició sesión en otro dispositivo. Por seguridad, se cerró esta sesión.',
                                  'warning');
                    <?php elseif ($_GET['status'] == 'deshabilitado'): ?>
                        Swal.fire('Usuario deshabilitado',
                                  'Tu cuenta se encuentra deshabilitada. Contacta con el administrador para reactivar tu acceso.',
                                  'error');
                    <?php endif; ?>

                    // Eliminar el parámetro 'status' de la URL sin recargar
                    if (window.history.replaceState) {
                        const url = new URL(window.location);
                        url.searchParams.delete('status');
                        window.history.replaceState({}, document.title, url);
                    }
                </script>
                <?php endif; ?>

                <!-- Mensajes de logout -->
                <?php if (isset($_GET['logout'])): ?>
                    <?php if ($_GET['logout'] == 1): ?>
                        <div style="display: flex; background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px; align-items: center; justify-content: center; gap: 10px;">
                            <img src="/proyecto-sena/assets/img/alert.png" alt="img-alert" style="width: 25px; height: 25px;">
                            <?= $translations['logout_success'] ?? 'Sesión cerrada correctamente.' ?>
                        </div>
                    <?php elseif ($_GET['logout'] == 'inactividad'): ?>
                        <div style="display: flex; background-color: #fff3cd; color: #856404; padding: 10px; border-radius: 5px; margin-bottom: 15px; align-items: center; justify-content: center; gap: 10px;">
                            <img src="/proyecto-sena/assets/img/alert.png" alt="img-alert" style="width: 25px; height: 25px;">
                            <?= $translations['logout_inactivity'] ?? 'Tu sesión se cerró automáticamente por inactividad.' ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <form action="/proyecto-sena/functions/functions_procesar_login.php" method="POST">
                    <div class="form-group">
                        <label class="form-label"><?= $translations['email'] ?? 'Email' ?></label>
                        <input type="email" name="email" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><?= $translations['password'] ?? 'Contraseña' ?></label>
                        <input type="password" name="contraseña" class="form-input" required>
                    </div>

                    <a href="/proyecto-sena/components/principales/forgot_password.php"><?= $translations['forgot_password'] ?? '¿Olvidaste tu contraseña?' ?></a>

                    <div class="form-actions">
                        <button type="submit" class="continue-button"><?= $translations['submit'] ?? 'Ingresar' ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
