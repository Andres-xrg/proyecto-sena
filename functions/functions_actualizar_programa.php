<?php
require_once __DIR__ . '/../db/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_programa'] ?? null;
    $nombre = $_POST['programa'] ?? '';
    $tipo = $_POST['tipo_programa'] ?? '';

    if (!$id || empty($nombre) || empty($tipo)) {
        // Redirección si hay error en los datos
        header("Location: ../index.php?page=components/principales/programas_formacion&actualizado=0");
        exit;
    }

    $stmt = $conn->prepare("UPDATE programas_formacion SET nombre_programa = ?, tipo_programa = ? WHERE id_programa = ?");
    $stmt->bind_param("ssi", $nombre, $tipo, $id);

    if ($stmt->execute()) {
        header("Location: ../index.php?page=components/principales/programas_formacion&actualizado=1");
    } else {
        header("Location: ../index.php?page=components/principales/programas_formacion&actualizado=0");
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../index.php?page=components/principales/programas_formacion");
    exit;
}
