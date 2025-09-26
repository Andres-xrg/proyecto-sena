<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../db/conexion.php';
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

// 🔒 Verificar si el usuario está logueado y es admin
$rol = strtolower($_SESSION['usuario']['rol'] ?? '');
if (!in_array($rol, ['administrador', 'admin'])) {
    echo json_encode(['success' => false, 'message' => 'No tienes permisos para editar observaciones.']);
    exit;
}

$id    = $_POST['id']    ?? null;
$texto = $_POST['texto'] ?? null;

if (!$id || !$texto) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
    exit;
}

try {
    $stmt = $conn->prepare("UPDATE observaciones_aprendiz SET observacion = ? WHERE id = ?");
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Error en prepare: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param("si", $texto, $id);
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Observación editada correctamente';
        $response['updated_html'] = nl2br(htmlspecialchars($texto));
    } else {
        $response['message'] = 'No se pudo actualizar la observación';
    }
    $stmt->close();
} catch (Exception $e) {
    $response['message'] = 'Excepción: ' . $e->getMessage();
}

echo json_encode($response);
