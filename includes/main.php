<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

ob_start();

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Cargar traducciones y código de idioma
$lang_data = require_once __DIR__ . '/../functions/lang.php';
$lang = $lang_data['lang_code']; // 'es' o 'en'
$translations = $lang_data['translations']; // array de traducciones

// Páginas públicas
$publicas = [
    'components/principales/login',
    'components/principales/registro',
    'components/principales/welcome',
    'components/principales/forgot_password',
    'components/principales/reset_password'
];

// Página solicitada
$page = $_GET['page'] ?? 'components/principales/welcome';
$page = str_replace('..', '', $page); 
$pagePath = __DIR__ . '/../' . $page . '.php';

// Páginas sin header/footer
$sinHeaderFooter = [
    'components/principales/login',
    'components/principales/registro',
    'components/principales/forgot_password',
    'components/principales/reset_password'
];

// Seguridad
if (!in_array($page, $publicas) && !isset($_SESSION['usuario'])) {
    header("Location: /proyecto-sena/index.php?page=components/principales/login");
    exit();
}

// Fallback si no existe la ruta
if (!file_exists($pagePath)) {
    $page = 'components/principales/welcome';
    $pagePath = __DIR__ . '/../' . $page . '.php';
}
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $translations['home'] ?? 'Proyecto Formativo' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/proyecto-sena/assets/css/header.css">
    <link rel="stylesheet" href="/proyecto-sena/assets/css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>body { visibility: hidden; }</style>
</head>
<body>

<?php
// Header dinámico
if (!in_array($page, $sinHeaderFooter)) {
    if (isset($_SESSION['usuario'])) {
        include __DIR__ . '/header.php';
    } else {
        include __DIR__ . '/header-secundario.php';
    }
}
?>

<main>
    <?php include $pagePath; ?>
</main>

<?php
if (!in_array($page, $sinHeaderFooter)) {
    include __DIR__ . '/footer.php';
}
?>

<script>
// Hacer visible el body
window.addEventListener('DOMContentLoaded', () => {
    document.body.style.visibility = 'visible';
});
</script>

<?php if (isset($_SESSION['usuario'])): ?>
<script>
document.addEventListener("DOMContentLoaded", () => {
    let alertaMostrada = false; 
    let sessionInterval = null;
    let heartbeatInterval = null;
    let inactividadTimer;

    const TIEMPO_INACTIVIDAD = 1 * 60 * 1000; // 1 minuto
    const TAB_ID = Date.now() + "_" + Math.random(); 
    sessionStorage.setItem("tab_id", TAB_ID);

    const cerrarSesion = (mensaje) => {
        if (alertaMostrada) return;
        alertaMostrada = true;

        if (sessionInterval) clearInterval(sessionInterval);
        if (heartbeatInterval) clearInterval(heartbeatInterval);

        // 🔹 Llamar logout_inactividad.php en el servidor antes de redirigir
        fetch("/proyecto-sena/functions/logout_inactividad.php", { method: "POST" })
            .finally(() => {
                Swal.fire({
                    icon: 'warning',
                    title: '¡Sesión cerrada!',
                    text: mensaje,
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    window.location.href = "/proyecto-sena/index.php?page=components/principales/login";
                });
            });
    };

    // -------------------------------
    // Verificación de sesión activa
    // -------------------------------
    const checkSession = () => {
        if (alertaMostrada) return;
        fetch("/proyecto-sena/functions/check_session.php")
            .then(response => response.json())
            .then(data => {
                if (!data.valid) {
                    let mensaje = 'Tu cuenta inició sesión en otro dispositivo o en otra pestaña. Por seguridad, esta sesión se cerró.';
                    if (data.logout_manual) {
                        mensaje = 'Has cerrado sesión en otra pestaña.';
                    }
                    cerrarSesion(mensaje);
                }
            })
            .catch(err => console.error("Error al revisar sesión:", err));
    };
    sessionInterval = setInterval(checkSession, 10000);

    // -------------------------------
    // Heartbeat para mantener sesión activa
    // -------------------------------
    const heartbeat = () => {
        if (alertaMostrada) return;
        fetch("/proyecto-sena/functions/heartbeat.php")
            .then(res => res.json())
            .then(data => {
                if (!data.active) {
                    cerrarSesion("Tu sesión expiró por inactividad.");
                } else {
                    console.log("✅ Heartbeat OK", data);
                }
            })
            .catch(err => console.error("Error en heartbeat:", err));
    };
    heartbeatInterval = setInterval(heartbeat, 30000); // cada 30s
    heartbeat(); // primera llamada inmediata

    // -------------------------------
    // Manejo de inactividad (AFK)
    // -------------------------------
    const reiniciarInactividad = () => {
        clearTimeout(inactividadTimer);
        inactividadTimer = setTimeout(() => {
            cerrarSesion("Tu sesión se cerró automáticamente por falta de interacción.");
        }, TIEMPO_INACTIVIDAD);
    };
    ["click", "mousemove", "keydown", "scroll", "touchstart"].forEach(event => {
        document.addEventListener(event, reiniciarInactividad);
    });
    reiniciarInactividad();

    // -------------------------------
    // Logout al cerrar pestaña
    // -------------------------------
    window.addEventListener("beforeunload", () => {
        localStorage.setItem("logout_tab", TAB_ID);
    });

    // -------------------------------
    // Escuchar eventos de otras pestañas
    // -------------------------------
    window.addEventListener("storage", (event) => {
        if (event.key === "logout_tab" && event.newValue !== TAB_ID) {
            cerrarSesion("Has cerrado sesión en otra pestaña.");
        }
        if (event.key === "deslogueado_manual") {
            cerrarSesion("Has cerrado sesión manualmente.");
        }
    });
});
</script>
<?php endif; ?>

</body>
</html>
<?php ob_end_flush(); ?>
