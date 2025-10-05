<?php
require_once __DIR__ . '/../db/conexion.php';
require_once __DIR__ . '/../functions/historial.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../index.php");
    exit;
}

// Asegurar sesión
if (session_status() === PHP_SESSION_NONE) session_start();

// Solo admin puede ejecutar
if (!isset($_SESSION['usuario']['rol']) || strtolower($_SESSION['usuario']['rol']) !== 'administrador') {
    header("Location: ../index.php?page=components/usuarios/usuarios&error=forbidden");
    exit;
}

// Recoger y sanear entradas
$accion = $_POST['accion'] ?? '';
$id = $_POST['id'] ?? null;
$id = is_numeric($id) ? (int)$id : null;

if (empty($id)) {
    echo "ID inválido.";
    exit;
}

// Función para registrar en historial
function registrarHistorial($actor_id, $accion, $descripcion, $conn) {
    registrar_historial($conn, $actor_id, $accion, $descripcion);
}

// Acciones según tipo
if ($accion === 'update') {
    // Campos del formulario
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $apellido = isset($_POST['apellido']) ? trim($_POST['apellido']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $tipo_documento = isset($_POST['tipo_documento']) ? trim($_POST['tipo_documento']) : '';
    $numero_documento = isset($_POST['numero_documento']) ? trim($_POST['numero_documento']) : '';
    $telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : '';

    // Validaciones
    if (empty($nombre) || empty($apellido)) {
        echo "Nombre y apellido son obligatorios.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Email inválido.";
        exit;
    }

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
        $actor_id = $_SESSION['usuario']['Id_usuario'] ?? null;
        $descripcion = "Usuario editado: {$nombre} {$apellido} (ID: {$id})";
        registrarHistorial($actor_id, "Editó un usuario", $descripcion, $conn);

        $stmt->close();
        $_SESSION['alert_update'] = "Usuario actualizado correctamente.";
        header("Location: ../index.php?page=components/usuarios/usuarios");
        exit;
    } else {
        $error_msg = $stmt->error ?: $conn->error;
        echo "Error al actualizar usuario: " . htmlspecialchars($error_msg);
        $stmt->close();
        exit;
    }

} elseif ($accion === 'toggle') {
    // Cambiar estado (habilitar/deshabilitar)
    $sql = "UPDATE usuarios SET estado = IF(estado=1, 0, 1) WHERE Id_usuario = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "Error en la preparación de la consulta: " . $conn->error;
        exit;
    }
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $actor_id = $_SESSION['usuario']['Id_usuario'] ?? null;
        $descripcion = "Se cambió el estado del usuario con ID: {$id}";
        registrarHistorial($actor_id, "Cambio de estado", $descripcion, $conn);

        $stmt->close();
        $_SESSION['alert_toggle'] = "Estado del usuario actualizado correctamente.";
        header("Location: ../index.php?page=components/usuarios/usuarios");
        exit;
    } else {
        $error_msg = $stmt->error ?: $conn->error;
        echo "Error al cambiar estado: " . htmlspecialchars($error_msg);
        $stmt->close();
        exit;
    }
} else {
    echo "Acción no válida.";
    exit;
}
?>
