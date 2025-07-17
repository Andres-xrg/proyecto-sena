<?php
ob_start();

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/../functions/lang.php';

// Páginas públicas (no requieren sesión)
$publicas = [
    'components/principales/login',
    'components/principales/registro',
    'components/principales/welcome',
    'components/principales/forgot_password',
    'components/principales/reset_password'
];

// Determinar la página solicitada
$page = $_GET['page'] ?? 'components/principales/welcome';
$pagePath = __DIR__ . '/../' . $page . '.php';

// Páginas sin header/footer
$sinHeaderFooter = [
    'components/principales/login',
    'components/principales/registro',
    'components/principales/forgot_password',
    'components/principales/reset_password'
];

// Seguridad: si no está logueado y no es una página pública, redirige
if (!in_array($page, $publicas) && !isset($_SESSION['usuario'])) {
    header("Location: /proyecto-sena/index.php?page=components/principales/login");
    exit();
}

// Si la ruta no existe, mostrar welcome como fallback
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
    <style>body { visibility: hidden; }</style>
</head>
<body>

<?php
// Mostrar el header correspondiente
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
// Mostrar footer si corresponde
if (!in_array($page, $sinHeaderFooter)) {
    include __DIR__ . '/footer.php';
}
?>

<script>
    window.addEventListener('DOMContentLoaded', () => {
        document.body.style.visibility = 'visible';
    });
</script>

</body>
</html>

<?php ob_end_flush(); ?>
