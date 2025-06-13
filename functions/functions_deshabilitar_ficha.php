<?php
require_once '../db/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $estado = $_POST['estado'];

    $sql = "UPDATE fichas SET Estado_ficha = ? WHERE Id_ficha = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $nuevo_estado, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'nuevo_estado' => $nuevo_estado]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
