<?php
require_once __DIR__ . '/../db/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['programa'] ?? '';
    $tipo = $_POST['tipo_programa'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';

    // Validación básica
    if (empty($nombre) || empty($tipo) || empty($descripcion)) {
        header("Location: ../index.php?page=components/programas/programas_formacion&creado=0");
        exit;
    }

    // Insertar en la base de datos
    $stmt = $conn->prepare("INSERT INTO programas_formacion (nombre_programa, tipo_programa, descripcion) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nombre, $tipo, $descripcion);

    if ($stmt->execute()) {
        header("Location: ../index.php?page=components/principales/programas_formacion&creado=1");
    } else {
        header("Location: ../index.php?page=components/principales/programas_formacion&creado=0");
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../index.php?page=components/principales/programas_formacion");
    exit;
}
