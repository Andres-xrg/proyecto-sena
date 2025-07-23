<?php
session_start();
header('Content-Type: application/json');
require_once '../db/conexion.php';

// Verificar método
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit;
}

// Verificar sesión
if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['id'])) {
    echo json_encode(['success' => false, 'message' => 'Debe iniciar sesión.']);
    exit;
}

$id_aprendiz = $_POST['id_aprendiz'] ?? null;
$observacion = trim($_POST['observacion'] ?? '');

// Validar campos
if (empty($id_aprendiz) || $observacion === '') {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
    exit;
}

$id_usuario = $_SESSION['usuario']['id'];

// Insertar en la base de datos
$stmt = $conn->prepare("INSERT INTO observaciones_aprendiz (id_aprendiz, id_usuario, observacion) VALUES (?, ?, ?)");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta.']);
    exit;
}

$stmt->bind_param("iis", $id_aprendiz, $id_usuario, $observacion);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Observación guardada correctamente.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al guardar en la base de datos.']);
}
exit;
?>
