<?php
require_once __DIR__ . '/../db/conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    session_start(); // Asegura la sesión activa

    $id = $_POST["id"];
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $email = $_POST["email"];
    $tipo_documento = $_POST["tipo_documento"];
    $numero_documento = $_POST["numero_documento"];
    $telefono = $_POST["telefono"];

    // Validar ficha (puede ser null)
    $ficha = isset($_POST["ficha"]) && is_numeric($_POST["ficha"]) ? (int)$_POST["ficha"] : null;

    $sql = "UPDATE instructores 
            SET nombre=?, apellido=?, Email=?, T_documento=?, N_documento=?, N_Telefono=?, Ficha=? 
            WHERE Id_instructor=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssii", $nombre, $apellido, $email, $tipo_documento, $numero_documento, $telefono, $ficha, $id);

    if ($stmt->execute()) {
        // Registrar en historial_usuarios
        $usuario_id = $_SESSION['usuario_id'] ?? null;
        if ($usuario_id !== null) {
            $accion = "Editó al instructor: $nombre $apellido (ID: $id)";
            $historialSQL = "INSERT INTO historial_usuarios (usuario_id, accion) VALUES (?, ?)";
            $historialStmt = $conn->prepare($historialSQL);
            $historialStmt->bind_param("is", $usuario_id, $accion);
            $historialStmt->execute();
        }

        // Redirección con éxito
        header("Location: ../index.php?page=components/instructores/instructores&success=editado");
        exit;
    } else {
        echo "❌ Error al actualizar: " . $stmt->error;
    }
}
?>
