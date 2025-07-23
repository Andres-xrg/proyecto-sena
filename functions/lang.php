<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

$lang_code = $_SESSION['lang'] ?? 'es';

$lang_file = __DIR__ . '/../lang/' . $lang_code . '.php';

if (file_exists($lang_file)) {
    $translations = include($lang_file);
} else {
    $translations = include(__DIR__ . '/../lang/es.php'); // fallback
    $lang_code = 'es';
}

return [
    'lang_code' => $lang_code,
    'translations' => $translations
];
