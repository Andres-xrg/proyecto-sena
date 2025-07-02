<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'db/conexion.php';
require_once 'functions/historial.php';

if (isset($_SESSION['usuario']['id'])) {
    $usuario_id = $_SESSION['usuario']['id'];
    registrar_historial($conn, $usuario_id, 'Logout', 'El usuario cerró sesión.');
}

session_destroy();
?>

<script>
window.location.href = '/proyecto-sena/index.php?page=components/principales/login&logout=1';
</script>