<?php
require_once __DIR__ . '/../db/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_programa'] ?? null;
    $nuevo_estado = $_POST['nuevo_estado'] ?? null;

    if ($id && in_array($nuevo_estado, ['activo', 'inactivo'])) {
        $stmt = $conn->prepare("UPDATE programas_formacion SET estado = ? WHERE Id_programa = ?");
        $stmt->bind_param("si", $nuevo_estado, $id);

        if ($stmt->execute()) {
            if ($nuevo_estado === 'activo') {
                header("Location: ../index.php?page=components/principales/programas_formacion&habilitado=1");
            } else {
                header("Location: ../index.php?page=components/principales/programas_formacion&deshabilitado=1");
            }
        } else {
            header("Location: ../index.php?page=components/principales/programas_formacion&error=1");
        }
        $stmt->close();
    } else {
        header("Location: ../index.php?page=components/principales/programas_formacion&error=1");
    }
    $conn->close();
}
