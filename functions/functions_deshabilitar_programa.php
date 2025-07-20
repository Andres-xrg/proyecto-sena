<?php
require_once __DIR__ . '/../db/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_programa'])) {
    $id = $_POST['id_programa'];

    $stmt = $conn->prepare("UPDATE programas_formacion SET estado = 'inactivo' WHERE Id_programa = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: ../index.php?page=components/principales/programas_formacion&deshabilitado=1");
    } else {
        header("Location: ../index.php?page=components/principales/programas_formacion&deshabilitado=0");
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../index.php?page=components/principales/programas_formacion");
    exit;
}
