<?php
// Iniciar sesión si no está activa
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Cargar traducciones
require_once __DIR__ . '/../functions/lang.php';

// Definir páginas públicas (que no requieren login)
$publicas = [
    'components/principales/login',
    'components/principales/registro',
    'components/principales/welcome'
];

// Obtener la página actual desde la URL (por defecto 'welcome')
$page = $_GET['page'] ?? 'components/principales/welcome';
$pagePath = __DIR__ . '/../' . $page . '.php';

// Redirigir al login si no hay sesión y la página no es pública
if (!in_array($page, $publicas) && !isset($_SESSION['usuario'])) {
    header("Location: /proyecto-sena/components/principales/login.php");
    exit();
}

// Páginas que no deben mostrar el header/footer
$sinHeaderFooter = ['components/principales/ver_historial'];
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $translations['home'] ?? 'Proyecto Formativo' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS condicional -->
    <?php if (!in_array($page, $sinHeaderFooter)): ?>
        <link rel="stylesheet" href="/proyecto-sena/assets/css/header.css">
    <?php endif; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<?php
// Mostrar el header si la página lo permite
if (!in_array($page, $sinHeaderFooter)) {
    if ($page !== 'components/principales/login') {
        if (isset($_SESSION['usuario'])) {
            include __DIR__ . '/header.php'; // Header principal si está logueado
        } else {
            include __DIR__ . '/header-secundario.php'; // Header público si no
        }
    }
}
?>

<main>
    <?php
    // Incluir el contenido de la página si existe
    if (file_exists($pagePath)) {
        include $pagePath;
    } else {
        echo "<p style='color:red; text-align:center;'>La página solicitada no existe.</p>";
    }
    ?>
</main>

</body>
</html>
