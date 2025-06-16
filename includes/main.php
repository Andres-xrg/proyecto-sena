<?php
session_start();

$page = $_GET['page'] ?? 'components/principales/welcome';
$pagePath = __DIR__ . '/../' . $page . '.php';

// Lista de páginas que NO deben cargar ni header ni footer
$sinHeaderFooter = [
    'components/principales/ver_historial'
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Mi Proyecto</title>

    <?php if (!in_array($page, $sinHeaderFooter)): ?>
        <link rel="stylesheet" href="/proyecto-sena/assets/css/header.css" />
    <?php endif; ?>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<body>

<?php
if (!in_array($page, $sinHeaderFooter)) {
    if ($page !== 'components/principales/login') {
        if (isset($_SESSION['usuario'])) {
            include __DIR__ . '/header.php';
        } else {
            include __DIR__ . '/header-secundario.php';
        }
    }
}
?>

<main>
    <?php
    if (file_exists($pagePath)) {
        include $pagePath;
    } else {
        echo "<p style='color:red; text-align:center;'>La página solicitada no existe.</p>";
    }
    ?>
</main>

<?php
// Aquí puedes incluir footer si lo tuvieras, con la misma condición
// if (!in_array($page, $sinHeaderFooter)) {
//     include __DIR__ . '/footer.php';
// }
?>

</body>
</html>
