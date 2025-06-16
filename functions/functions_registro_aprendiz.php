<?php
require_once '../db/conexion.php';
require_once '../functions/historial.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar conexión
if (!$conn) {
    die("<script>
        alert('❌ Error de conexión: " . mysqli_connect_error() . "');
        window.history.back();
    </script>");
}

// Validar que se envíen todos los campos requeridos
if (
    isset($_POST['nombre']) &&
    isset($_POST['apellido']) &&
    isset($_POST['tipoDocumento']) &&
    isset($_POST['numeroDocumento']) &&
    isset($_POST['telefono']) &&
    isset($_POST['Email'])
) {
    // Limpiar datos
    $nombre          = trim($_POST['nombre']);
    $apellido        = trim($_POST['apellido']);
    $tipoDocumento   = trim($_POST['tipoDocumento']);
    $numeroDocumento = trim($_POST['numeroDocumento']);
    $telefono        = trim($_POST['telefono']);
    $email           = trim($_POST['Email']);

    // Preparar consulta segura
    $query = "INSERT INTO aprendices 
        (nombre, apellido, T_documento, N_Documento, N_Telefono, Email) 
        VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die("<script>alert('❌ Error al preparar consulta'); window.history.back();</script>");
    }

    $stmt->bind_param("ssssss", $nombre, $apellido, $tipoDocumento, $numeroDocumento, $telefono, $email);

    if ($stmt->execute()) {
        // ✅ Registrar historial (con sesión o como usuario anónimo)
        $usuario_id = $_SESSION['usuario']['id'] ?? 0;
        $descripcion = "Se registró al aprendiz $nombre $apellido con documento $numeroDocumento.";
        registrar_historial($conn, $usuario_id, 'Registro de aprendiz', $descripcion);

        echo "<script>
            alert('✅ Aprendiz registrado exitosamente.');
            window.location.href = '../index.php?page=components/principales/welcome&success=Registro+exitoso';
        </script>";
    } else {
        echo "<script>
            alert('❌ Error al registrar aprendiz: " . $stmt->error . "');
            window.history.back();
        </script>";
    }

    $stmt->close();
} else {
    echo "<script>
        alert('⚠️ Por favor, completa todos los campos.');
        window.history.back();
    </script>";
}
?>
