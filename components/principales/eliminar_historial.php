<?php
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../functions/historial.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['ids'])) {
    $ids = $_POST['ids'];

    // Eliminar registros
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $tipos = str_repeat('i', count($ids));

    $stmt = $conn->prepare("DELETE FROM historial_usuarios WHERE id IN ($placeholders)");
    $stmt->bind_param($tipos, ...$ids);
    $stmt->execute();

    // Registrar historial de eliminación (opcional)
    if (isset($_SESSION['usuario']['id'])) {
        $usuario_id = $_SESSION['usuario']['id'];
        registrar_historial($conn, $usuario_id, 'delete', 'Eliminó registros del historial: IDs [' . implode(', ', $ids) . ']');
    }

    header("Location: ../../index.php?page=components/principales/ver_historial&success=eliminados");
    exit;
} else {
    header("Location: ../../index.php?page=components/principales/ver_historial&error=sin-seleccion");
    exit;
}
