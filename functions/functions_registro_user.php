<?php
include('../db/conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre       = $_POST['nombre'] ?? '';
    $apellidos    = $_POST['apellidos'] ?? '';
    $telefono     = $_POST['telefono'] ?? '';
    $tipo_doc     = $_POST['tipo_documento'] ?? '';
    $documento    = $_POST['documento'] ?? '';
    $email        = $_POST['email'] ?? '';
    $contrasena   = $_POST['contrasena'] ?? '';
    $confirmar    = $_POST['confirmar_contrasena'] ?? '';

    // Validar campos vacíos
    if (empty($nombre) || empty($apellidos) || empty($telefono) || empty($tipo_doc) || empty($documento) || empty($email) || empty($contrasena) || empty($confirmar)) {
        header("Location: registro_user.php?error=Todos+los+campos+son+obligatorios");
        exit;
    }

    // Confirmar contraseñas
    if ($contrasena !== $confirmar) {
        header("Location: registro_user.php?error=Las+contraseñas+no+coinciden");
        exit;
    }

    // Encriptar contraseña
    $contrasena_hash = password_hash($contrasena, PASSWORD_BCRYPT);

    // Insertar en la base de datos
$stmt = $conn->prepare("INSERT INTO usuarios (nombre, apellido, N_Telefono, T_Documento, N_Documento, Email, Contraseña, confirmarcontraseña, Rol) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssss", $nombre, $apellidos, $telefono, $tipo_doc, $documento, $email, $contrasena_hash, $confirmar, $rol);

    $rol = 'Usuario'; // Asignar rol por defecto
if ($stmt->execute()) {
header("Location: ../index.php?page=components/principales/welcome&success=Registro+exitoso");
exit;
} else {
    header("Location: ../functions/index.php?page=registro_user&error=" . urlencode($stmt->error));
    exit;
}



    $stmt->close();
    $conn->close();
} else {
    header("Location: registro_user.php?error=Acceso+no+permitido");
}
