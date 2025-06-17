<?php
require_once("../../db/conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    $id = intval($_POST["id"]);

    // Verificar si está asignado como jefe de ficha
    $check = $conn->prepare("SELECT * FROM fichas WHERE Jefe_grupo = ?");
    $check->bind_param("i", $id);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        echo "❌ No puedes eliminar este instructor porque está asignado como jefe de ficha.";
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM instructores WHERE Id_instructor = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: ../../index.php?page=components/instructores/instructores&success=eliminado");
        exit;
    } else {
        echo "Error al eliminar: " . $stmt->error;
    }
} else {
    echo "❌ Petición inválida.";
}
?>
