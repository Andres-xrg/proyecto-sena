<?php
require_once __DIR__ . '/../db/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_programa'] ?? null;
    $nombre = trim($_POST['programa'] ?? '');
    $tipo = trim($_POST['tipo_programa'] ?? '');

    // Validaciones
    if (!$id || empty($nombre) || empty($tipo)) {
        header("Location: ../index.php?page=components/principales/programas_formacion&actualizado=0&error=" . urlencode("Datos incompletos."));
        exit;
    }

    // Validar que el nombre solo tenga letras y espacios
    if (!preg_match("/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/", $nombre)) {
        header("Location: ../index.php?page=components/principales/programas_formacion&actualizado=0&error=" . urlencode("El nombre del programa solo puede contener letras."));
        exit;
    }

    // Validar tipo de programa
    if (!in_array($tipo, ['tecnico', 'tecnologo'])) {
        header("Location: ../index.php?page=components/principales/programas_formacion&actualizado=0&error=" . urlencode("Tipo de programa inválido."));
        exit;
    }

    $stmt = $conn->prepare("UPDATE programas_formacion SET nombre_programa = ?, tipo_programa = ? WHERE Id_programa = ?");
    $stmt->bind_param("ssi", $nombre, $tipo, $id);

    if ($stmt->execute()) {
        header("Location: ../index.php?page=components/principales/programas_formacion&actualizado=1");
    } else {
        header("Location: ../index.php?page=components/principales/programas_formacion&actualizado=0&error=" . urlencode("Error al actualizar el programa."));
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../index.php?page=components/principales/programas_formacion");
    exit;
}
