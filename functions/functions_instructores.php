<?php
if (!ACCESO_PERMITIDO){
    header("Location: /proyecto-sena/components/principales/login.php");
}
session_start();
require_once('../db/conexion.php');
require_once('../functions/historial.php');

// Verificar conexión
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Verificar si se hizo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $accion = $_POST['accion'] ?? null;

    if ($id && in_array($accion, ['Habilitar', 'Deshabilitar'])) {
        // Determinar nuevo estado
        $estado = $accion === 'Habilitar' ? 'Activo' : 'Inactivo';

        // Actualizar en la base de datos
        $stmt = $conn->prepare("UPDATE instructores SET Tipo_instructor = ? WHERE Id_instructor = ?");
        if ($stmt) {
            $stmt->bind_param("si", $estado, $id);
            if ($stmt->execute()) {
                // 🟩 Obtener nombre del instructor para registrar en el historial
                $res = $conn->query("SELECT nombre, apellido FROM instructores WHERE Id_instructor = $id");
                $instructor = $res->fetch_assoc();
                $nombreInstructor = $instructor ? $instructor['nombre'] . ' ' . $instructor['apellido'] : 'Desconocido';

                // 🟩 Verificar sesión activa antes de registrar
                if (isset($_SESSION['usuario']['id'])) {
                    $usuario_id = $_SESSION['usuario']['id'];
                    $accion_historial = $accion === 'Habilitar' ? 'Habilitó instructor' : 'Deshabilitó instructor';
                    $descripcion = "$accion_historial: $nombreInstructor (ID $id)";
                    registrar_historial($conn, $usuario_id, $accion_historial, $descripcion);
                }

                // Redirigir de vuelta con mensaje
                header("Location: ../index.php?page=components/instructores/instructores&success=estado-cambiado");
                exit;
            } else {
                echo "❌ Error al ejecutar la consulta: " . $stmt->error;
                exit;
            }
        } else {
            echo "❌ Error al preparar la consulta: " . $conn->error;
            exit;
        }
    } else {
        echo "❌ Datos inválidos enviados.";
        exit;
    }
} else {
    echo "❌ Método no permitido.";
    exit;
}
