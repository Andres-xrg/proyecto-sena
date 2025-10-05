<?php
// Iniciar sesión si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../functions/historial.php';

// Tipo de logout: manual o por inactividad
$tipo = isset($_GET['inactividad']) ? 'inactividad' : 'manual';

// Si hay usuario logueado, limpiar session_id en DB y registrar historial
if (isset($_SESSION['usuario']['id'])) {
    $usuario_id = $_SESSION['usuario']['id'];

    // Limpiar session_id en DB
    $sql_update = "UPDATE usuarios SET session_id = NULL WHERE Id_usuario = ?";
    if ($stmt_update = $conn->prepare($sql_update)) {
        $stmt_update->bind_param("i", $usuario_id);
        $stmt_update->execute();
        $stmt_update->close();
    }

    // Registrar historial con detalle
    if ($tipo === 'inactividad') {
        registrar_historial($conn, $usuario_id, 'Logout por inactividad', 'El usuario fue desconectado automáticamente por inactividad.');
    } else {
        registrar_historial($conn, $usuario_id, 'Logout manual', 'El usuario cerró sesión manualmente.');
    }
}

// Cerrar conexión DB de forma segura
if (isset($conn)) {
    $conn->close();
}

// Limpiar variables de sesión
$_SESSION = [];

// Borrar cookie de sesión
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destruir sesión
session_destroy();

// 🔹 Avisar a otras pestañas que hubo un logout (con timestamp único)
echo "<script>
    localStorage.setItem('deslogueado_manual', Date.now().toString());
    window.location.href = '/proyecto-sena/components/principales/login.php?logout=" . ($tipo === 'inactividad' ? 'inactividad' : '1') . "';
</script>";

exit();
