<?php
function registrar_historial($conn, $usuario_id, $accion, $descripcion = '') {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

    if ($usuario_id === null) {
        $stmt = $conn->prepare("
            INSERT INTO historial_usuarios (usuario_id, accion, descripcion, ip_usuario)
            VALUES (NULL, ?, ?, ?)
        ");
        $stmt->bind_param("sss", $accion, $descripcion, $ip);
    } else {
        $stmt = $conn->prepare("
            INSERT INTO historial_usuarios (usuario_id, accion, descripcion, ip_usuario)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param("isss", $usuario_id, $accion, $descripcion, $ip);
    }

    $stmt->execute();
    $stmt->close();
}
