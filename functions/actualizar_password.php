<?php

require_once '../db/conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST['token'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmar = $_POST['confirmar'] ?? '';

    // Verifica que las contraseñas coincidan
    if ($password !== $confirmar) {
        echo "❌ Las contraseñas no coinciden.";
        exit;
    }

    // Buscar al usuario por token válido
    $stmt = $conn->prepare("SELECT Id_usuario FROM usuarios WHERE token_recuperacion = ? AND token_expiracion > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $usuario = $res->fetch_assoc();
        $id_usuario = $usuario['Id_usuario'];

        // Hashear la nueva contraseña
        $nueva_clave = password_hash($password, PASSWORD_DEFAULT);

        // Actualizar contraseña y limpiar el token
        $update = $conn->prepare("UPDATE usuarios SET `Contraseña` = ?, token_recuperacion = NULL, token_expiracion = NULL WHERE Id_usuario = ?");
        $update->bind_param("si", $nueva_clave, $id_usuario);
        $update->execute();

        // Agregar registro al historial_usuarios
        $accion = "Cambio de contraseña";
        $descripcion = "El usuario recuperó su contraseña mediante el enlace enviado al correo.";
        $ip_usuario = $_SERVER['REMOTE_ADDR'];

        $insert_historial = $conn->prepare("INSERT INTO historial_usuarios (usuario_id, accion, descripcion, ip_usuario) VALUES (?, ?, ?, ?)");
        $insert_historial->bind_param("isss", $id_usuario, $accion, $descripcion, $ip_usuario);
        $insert_historial->execute();

        // Redirigir al login
        header("Location: ../components/principales/login.php?status=actualizada");
        exit;

    } else {
        echo "❌ Token inválido o expirado.";
    }
}
?>
