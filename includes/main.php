<?php
ob_start(); // Previene que se envíe HTML antes de validar sesión

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Cabeceras para evitar caché
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

// Traducciones
require_once __DIR__ . '/../functions/lang.php';

// Páginas públicas
$publicas = [
    'components/principales/login',
    'components/principales/registro',
    'components/principales/welcome'
];

// Página actual
$page = $_GET['page'] ?? 'components/principales/welcome';
$pagePath = __DIR__ . '/../' . $page . '.php';

// Redirigir al login si no hay sesión y la página no es pública
if (!in_array($page, $publicas) && !isset($_SESSION['usuario'])) {
    header("Location: /proyecto-sena/components/principales/login.php");
    exit();
}

// Páginas sin header/footer
$sinHeaderFooter = ['components/principales/ver_historial'];
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $translations['home'] ?? 'Proyecto Formativo' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Oculta todo hasta que esté cargado -->
    <style>
        body {
            visibility: hidden;
        }
    </style>

    <?php if (!in_array($page, $sinHeaderFooter)): ?>
        <link rel="stylesheet" href="/proyecto-sena/assets/css/header.css">
    <?php endif; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<?php
// Mostrar header si corresponde
if (!in_array($page, $sinHeaderFooter) && $page !== 'components/principales/login') {
    if (isset($_SESSION['usuario']) && $_SESSION['usuario']) {
        include __DIR__ . '/header.php';
    } else {
        include __DIR__ . '/header-secundario.php';
    }
}
?>

<main>
    <?php
    // Mostrar contenido de la página
    if (file_exists($pagePath)) {
        include $pagePath;
    } else {
        echo "<p style='color:red; text-align:center;'>La página solicitada no existe.</p>";
    }
    ?>
</main>

<!-- Mostrar el contenido una vez cargado -->
<script>
    window.addEventListener('DOMContentLoaded', () => {
        document.body.style.visibility = 'visible';
    });
</script>

</body>
</html>

<?php ob_end_flush(); ?>
