<?php
// Iniciar sesión si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../db/conexion.php';
require_once __DIR__ . '/historial.php';

// Si hay usuario logueado
if (isset($_SESSION['usuario']['id'])) {
    $usuario_id = $_SESSION['usuario']['id'];

    // Limpiar session_id en DB
    $sql_update = "UPDATE usuarios SET session_id = NULL WHERE Id_usuario = ?";
    if ($stmt_update = $conn->prepare($sql_update)) {
        $stmt_update->bind_param("i", $usuario_id);
        $stmt_update->execute();
        $stmt_update->close();
    }

    // Registrar historial con motivo de inactividad
    registrar_historial($conn, $usuario_id, 'Logout', 'Sesión cerrada por inactividad');
}

// Cerrar conexión segura
if (isset($conn)) {
    $conn->close();
}

// Limpiar variables de sesión
$_SESSION = [];
session_destroy();

// Devolver respuesta en JSON para que lo capture JS
header('Content-Type: application/json');
echo json_encode(['status' => 'ok', 'msg' => 'Sesión cerrada por inactividad']);
exit();
