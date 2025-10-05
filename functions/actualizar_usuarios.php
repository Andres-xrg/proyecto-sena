<?php
require_once __DIR__ . '/../db/conexion.php';
require_once __DIR__ . '/../functions/historial.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../index.php");
    exit;
}

// Asegurar sesión
if (session_status() === PHP_SESSION_NONE) session_start();

// Solo admin puede actualizar
if (!isset($_SESSION['usuario']['rol']) || strtolower($_SESSION['usuario']['rol']) !== 'administrador') {
    header("Location: ../index.php?page=components/usuarios/usuarios&error=forbidden");
    exit;
}

// Recoger y sanear entradas
$id = $_POST['id'] ?? null;
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$apellido = isset($_POST['apellido']) ? trim($_POST['apellido']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$tipo_documento = isset($_POST['tipo_documento']) ? trim($_POST['tipo_documento']) : '';
$numero_documento = isset($_POST['numero_documento']) ? trim($_POST['numero_documento']) : '';
$telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : '';

// Validaciones básicas
if (empty($id) || !is_numeric($id)) {
    echo "ID inválido.";
    exit;
}
$id = (int)$id;

if (empty($nombre) || empty($apellido)) {
    echo "Nombre y apellido son obligatorios.";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Email inválido.";
    exit;
}

// Preparar UPDATE solo con los campos necesarios
$sql = "UPDATE usuarios
        SET nombre = ?, apellido = ?, Email = ?, T_Documento = ?, N_Documento = ?, N_Telefono = ?
        WHERE Id_usuario = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo "Error en la preparación de la consulta: " . $conn->error;
    exit;
}

$stmt->bind_param(
    "ssssssi",
    $nombre,
    $apellido,
    $email,
    $tipo_documento,
    $numero_documento,
    $telefono,
    $id
);

if ($stmt->execute()) {
    // Registrar en historial
    $actor_id = $_SESSION['usuario']['Id_usuario'] ?? null;
    $accion = "Editó un usuario";
    $descripcion = "Usuario editado: {$nombre} {$apellido} (ID: {$id})";
    registrar_historial($conn, $actor_id, $accion, $descripcion);

    $stmt->close();
    $_SESSION['alert_update'] = "Usuario actualizado correctamente.";
    // Forzar recarga para evitar cache
    header("Location: ../index.php?page=components/usuarios/usuarios&t=" . time());
    exit;
} else {
    $error_msg = $stmt->error ?: $conn->error;
    echo "Error al actualizar usuario: " . htmlspecialchars($error_msg);
    $stmt->close();
    exit;
}
?>
