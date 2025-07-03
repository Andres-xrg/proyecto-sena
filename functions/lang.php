<?php
// Iniciar sesión si no está activa
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Cambiar idioma si viene por GET
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

// Usar idioma de la sesión o español por defecto
$lang = $_SESSION['lang'] ?? 'es';

// Cargar archivo de idioma
$lang_file = __DIR__ . '/../lang/' . $lang . '.php';

if (file_exists($lang_file)) {
    $translations = include($lang_file);
} else {
    $translations = include(__DIR__ . '/../lang/es.php'); // fallback
}
