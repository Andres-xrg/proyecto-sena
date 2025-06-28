<?php
require_once '../db/conexion.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $nuevo_estado = $_POST['estado'] ?? null;

    if ($id && in_array($nuevo_estado, ['Activo', 'Inactivo'])) {
        $sql = "UPDATE fichas SET Estado_ficha = ? WHERE Id_ficha = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $nuevo_estado, $id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'nuevo_estado' => $nuevo_estado]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Datos inválidos']);
    }

    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
}
