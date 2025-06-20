<?php
session_start();
require_once '../db/conexion.php';

$id_usuario = $_SESSION['usuario_id'] ?? null;

if (!$id_usuario) {
    header("Location: ../components/principales/login.php");
    exit;
}

$actual = $_POST['actual'] ?? '';
$nueva = $_POST['nueva'] ?? '';
$confirmar = $_POST['confirmar'] ?? '';

if ($nueva !== $confirmar) {
    echo "❌ Las contraseñas nuevas no coinciden.";
    exit;
}

// Verifica la contraseña actual
$stmt = $conn->prepare("SELECT `Contraseña` FROM usuarios WHERE Id_usuario = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$res = $stmt->get_result();
$usuario = $res->fetch_assoc();

if (!password_verify($actual, $usuario['Contraseña'])) {
    echo "❌ La contraseña actual es incorrecta.";
    exit;
}

// Actualiza la contraseña
$nueva_hash = password_hash($nueva, PASSWORD_DEFAULT);
$update = $conn->prepare("UPDATE usuarios SET `Contraseña` = ? WHERE Id_usuario = ?");
$update->bind_param("si", $nueva_hash, $id_usuario);
$update->execute();

// Registra en historial
$accion = "Cambio de contraseña (perfil)";
$descripcion = "El usuario cambió su contraseña desde su cuenta.";
$ip = $_SERVER['REMOTE_ADDR'];

$log = $conn->prepare("INSERT INTO historial (usuario_id, accion, descripcion, ip_usuario) VALUES (?, ?, ?, ?)");
$log->bind_param("isss", $id_usuario, $accion, $descripcion, $ip);
$log->execute();

header("Location: ../components/principales/login.php?status=clave_cambiada");
exit;
