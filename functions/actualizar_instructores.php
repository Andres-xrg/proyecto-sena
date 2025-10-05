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
    $tipo_instructor = $_POST["tipo_instructor"];
    $rol_instructor = $_POST["rol_instructor"];
    $fecha_inicio = !empty($_POST["fecha_inicio_contrato"]) ? $_POST["fecha_inicio_contrato"] : null;
    $fecha_fin = !empty($_POST["fecha_fin_contrato"]) ? $_POST["fecha_fin_contrato"] : null;

    // Validar ficha (puede ser null)
    $ficha = isset($_POST["ficha"]) && is_numeric($_POST["ficha"]) ? (int)$_POST["ficha"] : null;

    $sql = "UPDATE instructores 
            SET nombre=?, apellido=?, Email=?, T_documento=?, N_documento=?, N_Telefono=?, Tipo_instructor=?, rol_instructor=?, fecha_inicio_contrato=?, fecha_fin_contrato=?, Ficha=? 
            WHERE Id_instructor=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssssssssii", 
        $nombre, 
        $apellido, 
        $email, 
        $tipo_documento, 
        $numero_documento, 
        $telefono, 
        $tipo_instructor, 
        $rol_instructor, 
        $fecha_inicio, 
        $fecha_fin, 
        $ficha, 
        $id
    );

    if ($stmt->execute()) {
        // Habilitar o deshabilitar automáticamente en usuarios si es contratista
        if ($tipo_instructor === 'contratista' && !empty($fecha_fin)) {
            $hoy = date('Y-m-d');
            $estado = ($fecha_fin >= $hoy) ? 1 : 0; // 1 = activo, 0 = inactivo

            $estadoStmt = $conn->prepare("UPDATE usuarios SET estado=? WHERE Email=?");
            $estadoStmt->bind_param("is", $estado, $email);
            $estadoStmt->execute();
            $estadoStmt->close();
        }

        // Registrar en historial_usuarios
        $usuario_id = $_SESSION['usuario_id'] ?? null;
        if ($usuario_id !== null) {
            $accion = "Editó al instructor: $nombre $apellido (ID: $id)";
            $historialSQL = "INSERT INTO historial_usuarios (usuario_id, accion) VALUES (?, ?)";
            $historialStmt = $conn->prepare($historialSQL);
            $historialStmt->bind_param("is", $usuario_id, $accion);
            $historialStmt->execute();
            $historialStmt->close();
        }

        // Redirección con éxito
        header("Location: ../index.php?page=components/instructores/instructores&success=editado");
        exit;
    } else {
        echo "Error al actualizar: " . $stmt->error;
    }

    $stmt->close();
}
?>
