<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../functions/historial.php';

if (isset($_SESSION['usuario']['id'])) {
    $usuario_id = $_SESSION['usuario']['id'];
    registrar_historial($conn, $usuario_id, 'Logout', 'El usuario cerró sesión.');
}

// Limpiar todas las variables de sesión
$_SESSION = [];

// Borrar cookie de sesión si existe
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destruir sesión
session_destroy();

// Redirigir al login
header("Location: /proyecto-sena/index.php?page=components/principales/login&logout=1");
exit();
?>
