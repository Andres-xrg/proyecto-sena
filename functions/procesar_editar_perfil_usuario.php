<?php
session_start();
require_once '../db/conexion.php';

$id_usuario         = $_POST['id_usuario'] ?? null;
$nombre             = trim($_POST['nombre'] ?? '');
$apellido           = trim($_POST['apellido'] ?? '');
$email              = trim($_POST['Email'] ?? '');
$telefono           = trim($_POST['N_telefono'] ?? '');
$nueva_contrasena   = trim($_POST['nueva_contrasena'] ?? '');
$confirmar_contrasena = trim($_POST['confirmar_contrasena'] ?? '');

// Validaciones básicas
if (!$id_usuario || !$nombre || !$apellido || !$email || !$telefono) {
    echo "Faltan datos obligatorios.";
    exit;
}

// Validar si el correo ya está en uso por otro usuario
$sql = "SELECT Id_usuario FROM usuarios WHERE Email = ? AND Id_usuario != ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $email, $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    echo "El correo ya está en uso por otro usuario.";
    exit;
}

if (!empty($nueva_contrasena)) {
    // Validar coincidencia de contraseña
    if ($nueva_contrasena !== $confirmar_contrasena) {
        echo "Las contraseñas no coinciden.";
        exit;
    }

    // Encriptar nueva contraseña
    $hash = password_hash($nueva_contrasena, PASSWORD_DEFAULT);

    // Actualizar incluyendo la contraseña
    $sql = "UPDATE usuarios 
            SET nombre = ?, apellido = ?, Email = ?, N_telefono = ?, Contraseña = ?
            WHERE Id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $nombre, $apellido, $email, $telefono, $hash, $id_usuario);
} else {
    // Actualizar sin cambiar contraseña
    $sql = "UPDATE usuarios 
            SET nombre = ?, apellido = ?, Email = ?, N_telefono = ?
            WHERE Id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $nombre, $apellido, $email, $telefono, $id_usuario);
}

if ($stmt->execute()) {
    // Opcional: actualizar la sesión con el nuevo nombre/email
    $_SESSION['usuario']['nombre'] = $nombre;
    $_SESSION['usuario']['email']  = $email;

    // Redirigir con éxito
    header("Location: /proyecto-sena/index.php?page=components/principales/welcome&perfil=actualizado");
    exit;
} else {
    echo "Error al actualizar el perfil. Inténtalo de nuevo.";
}
