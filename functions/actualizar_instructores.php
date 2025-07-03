<?php

require_once __DIR__ . '/../db/conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["id"];
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $email = $_POST["email"];
    $tipo_documento = $_POST["tipo_documento"];
    $numero_documento = $_POST["numero_documento"];
    $telefono = $_POST["telefono"];
    $ficha = $_POST["ficha"] ?? null; // Nueva línea

    $sql = "UPDATE instructores 
            SET nombre=?, apellido=?, Email=?, T_documento=?, N_documento=?, N_Telefono=?, Ficha=? 
            WHERE Id_instructor=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $nombre, $apellido, $email, $tipo_documento, $numero_documento, $telefono, $ficha, $id);

    if ($stmt->execute()) {
        header("Location: ../index.php?page=components/instructores/instructores&success=editado");
        exit;
    } else {
        echo "Error al actualizar: " . $stmt->error;
    }
}
?>