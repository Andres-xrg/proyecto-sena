<?php

function registrar_historial($conn, $usuario_id, $accion, $descripcion = '') {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

    // Si no hay ID, registrar como anónimo
    if (empty($usuario_id)) {
        $stmt = $conn->prepare("
            INSERT INTO historial_usuarios (usuario_id, accion, descripcion, ip_usuario)
            VALUES (NULL, ?, ?, ?)
        ");
        $stmt->bind_param("sss", $accion, $descripcion, $ip);
        $stmt->execute();
        $stmt->close();
        return;
    }

    // Verificar que el usuario existe en la tabla 'usuarios'
    $check = $conn->prepare("SELECT 1 FROM usuarios WHERE Id_usuario = ?");
    $check->bind_param("i", $usuario_id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $stmt = $conn->prepare("
            INSERT INTO historial_usuarios (usuario_id, accion, descripcion, ip_usuario)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param("isss", $usuario_id, $accion, $descripcion, $ip);
        $stmt->execute();
        $stmt->close();
    }

    $check->close();
}
