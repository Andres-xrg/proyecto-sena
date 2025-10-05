<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

require_once __DIR__ . '/../db/conexion.php';

// ✅ Verificar conexión
if (!isset($conn) || !$conn instanceof mysqli) {
    echo json_encode([
        "active" => false,
        "error" => "No se encontró conexión a la BD",
        "timestamp" => time()
    ]);
    exit;
}

if ($conn->connect_errno) {
    echo json_encode([
        "active" => false,
        "error" => "Error de conexión a la BD: " . $conn->connect_error,
        "timestamp" => time()
    ]);
    exit;
}

// ✅ Verificar sesión
if (empty($_SESSION['usuario']['id'])) {
    echo json_encode([
        "active" => false,
        "error" => "No hay usuario en sesión",
        "timestamp" => time()
    ]);
    exit;
}

$usuario_id = (int) $_SESSION['usuario']['id'];
$session_id = session_id();
$ahora = date("Y-m-d H:i:s");

// ✅ Usar los nombres correctos de las columnas
$sql = "UPDATE usuarios SET session_id = ?, last_activity = ? WHERE Id_usuario = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "active" => false,
        "error" => "Prepare failed: " . $conn->error,
        "sql" => $sql,
        "timestamp" => time()
    ]);
    exit;
}

$stmt->bind_param("ssi", $session_id, $ahora, $usuario_id);

if (!$stmt->execute()) {
    echo json_encode([
        "active" => false,
        "error" => "Execute failed: " . $stmt->error,
        "timestamp" => time()
    ]);
    exit;
}

// ✅ Éxito
echo json_encode([
    "active" => true,
    "usuario" => $_SESSION['usuario'],
    "timestamp" => time()
]);
exit;
