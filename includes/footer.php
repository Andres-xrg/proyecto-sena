<?php
$config = include(__DIR__ . '/../functions/lang.php');
$translations = $config['translations'] ?? [];
$year = date('Y');
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($config['lang_code'] ?? 'es') ?>">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./assets/css/footer.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <footer class="footer">
        <?= $translations['footer_text'] ?? 'Texto por defecto' ?> <?= $year ?>
    </footer>
</body>
</html>
