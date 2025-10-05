<?php
session_start();
require_once __DIR__ . '/../db/conexion.php';
require_once __DIR__ . '/../functions/historial.php';

header('Content-Type: application/json');

// Estado por defecto
$response = ['valid' => true, 'logout_manual' => false];

// Si no hay usuario logueado
if (!isset($_SESSION['usuario'])) {
    $response['valid'] = false;
    echo json_encode($response);
    exit;
}

// Revisar session_id en DB
$sql = "SELECT session_id FROM usuarios WHERE Id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['usuario']['id']);
$stmt->execute();
$result = $stmt->get_result();
$usuario_db = $result->fetch_assoc();

// Si session_id no coincide, cerramos sesión
if (!$usuario_db || $usuario_db['session_id'] !== session_id()) {
    $response['valid'] = false;
    $response['logout_manual'] = isset($_SESSION['logout_manual']) ? $_SESSION['logout_manual'] : false;

    // Limpiar sesión en esta pestaña
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();

    echo json_encode($response);
    exit;
}

// Todo bien, sesión válida
echo json_encode($response);
