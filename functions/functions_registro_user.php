<?php
session_start();
require_once '../db/conexion.php';
require_once '../functions/historial.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // 1. Obtener y validar datos del formulario
    $nombre       = trim($_POST['nombre'] ?? '');
    $apellidos    = trim($_POST['apellidos'] ?? '');
    $telefono     = trim($_POST['telefono'] ?? '');
    $tipo_doc     = trim($_POST['tipo_documento'] ?? '');
    $documento    = trim($_POST['documento'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $contrasena   = $_POST['contrasena'] ?? '';
    $confirmar    = $_POST['confirmar_contrasena'] ?? '';

    if ($contrasena !== $confirmar) {
        header("Location: registro_user.php?error=Las+contraseñas+no+coinciden");
        exit;
    }

    // 2. Encriptar contraseña y definir rol
    $contrasena_hash = password_hash($contrasena, PASSWORD_BCRYPT);
    $rol = 'Usuario';

    // 3. Insertar en tabla usuarios
    $stmt = $conn->prepare("
        INSERT INTO usuarios (
            nombre, apellido, N_Telefono, T_Documento, N_Documento, Email, Contraseña, confirmarcontraseña, Rol
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    if (!$stmt) {
        die("Error al preparar consulta: " . $conn->error);
    }

    $stmt->bind_param("sssssssss", $nombre, $apellidos, $telefono, $tipo_doc, $documento, $email, $contrasena_hash, $confirmar, $rol);

    if ($stmt->execute()) {
        // 4. Insertar también en aprendices (relacionado con Id_usuario)
        $id_usuario_nuevo = $conn->insert_id;

        $stmt_ap = $conn->prepare("INSERT INTO aprendices (Id_usuario, nombre, apellido, T_documento, N_Documento, N_Telefono, Email) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt_ap->bind_param("issssss", $id_usuario_nuevo, $nombre, $apellidos, $tipo_doc, $documento, $telefono, $email);
        $stmt_ap->execute();

        // 5. Guardar en historial si hay un usuario logueado
        if (isset($_SESSION['usuario']['id'])) {
            $usuario_id = $_SESSION['usuario']['id'];
            $descripcion = "Se registró el usuario $nombre $apellidos con documento $documento.";
            registrar_historial($conn, $usuario_id, 'Registro de usuario', $descripcion);
        }

        // 6. Redirigir con éxito
        header("Location: ../index.php?page=components/principales/welcome&success=Registro+exitoso");
        exit;
    } else {
        die("❌ Error al ejecutar consulta: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: registro_user.php?error=Acceso+no+permitido");
    exit;
}
