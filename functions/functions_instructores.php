<?php
session_start();
require_once('../db/conexion.php');
require_once('../functions/historial.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idInstructor = $_POST['id'] ?? null;
    $accion = $_POST['accion'] ?? null;

    if ($idInstructor && in_array($accion, ['Habilitar', 'Deshabilitar'])) {
        $estado = $accion === 'Habilitar' ? 1 : 0;

        // Obtener Email del instructor
        $res = $conn->query("SELECT nombre, apellido, Email FROM instructores WHERE Id_instructor = $idInstructor");
        if ($res && $res->num_rows > 0) {
            $instructor = $res->fetch_assoc();
            $email = $instructor['Email'];
            $nombreInstructor = $instructor['nombre'] . ' ' . $instructor['apellido'];

            // Actualizar estado en usuarios usando Email
            $stmt = $conn->prepare("UPDATE usuarios SET estado = ? WHERE Email = ?");
            if ($stmt) {
                $stmt->bind_param("is", $estado, $email);
                if ($stmt->execute()) {
                    // Registrar historial
                    if (isset($_SESSION['usuario']['id'])) {
                        $usuario_id = $_SESSION['usuario']['id'];
                        $accion_historial = $accion === 'Habilitar' ? 'Habilitó instructor' : 'Deshabilitó instructor';
                        $descripcion = "$accion_historial: $nombreInstructor (ID $idInstructor)";
                        registrar_historial($conn, $usuario_id, $accion_historial, $descripcion);
                    }

                    header("Location: ../index.php?page=components/instructores/instructores&success=estado-cambiado");
                    exit;
                } else {
                    echo "❌ Error al ejecutar la consulta: " . $stmt->error;
                }
            } else {
                echo "❌ Error al preparar la consulta: " . $conn->error;
            }
        } else {
            echo "❌ Instructor no encontrado.";
        }
    } else {
        echo "❌ Datos inválidos enviados.";
    }
} else {
    echo "❌ Método no permitido.";
}
?>
