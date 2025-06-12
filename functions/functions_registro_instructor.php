<?php
// Mostrar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../db/conexion.php');

// Verificar conexión
if (!$conn) {
    die("❌ Error de conexión a la base de datos: " . mysqli_connect_error());
}

// Verificar si se enviaron todos los campos requeridos
if (
    isset($_POST['nombre']) &&
    isset($_POST['apellido']) &&
    isset($_POST['ficha']) &&
    isset($_POST['tipoDocumento']) &&
    isset($_POST['numeroDocumento']) &&
    isset($_POST['instructor']) &&
    isset($_POST['telefono']) &&
    isset($_POST['Email'])
) {
    // Capturar y limpiar los datos
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $ficha = trim($_POST['ficha']);
    $tipoDocumento = trim($_POST['tipoDocumento']);
    $numeroDocumento = trim($_POST['numeroDocumento']);
    $tipoInstructor = trim($_POST['instructor']);
    $telefono = trim($_POST['telefono']);
    $Email = trim($_POST['Email']);

    // Consulta SQL con placeholders
    $query = "INSERT INTO instructores 
        (nombre, apellido, Ficha, T_documento, N_Documento, Tipo_instructor, N_Telefono, Email) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("ssssssss", $nombre, $apellido, $ficha, $tipoDocumento, $numeroDocumento, $tipoInstructor, $telefono, $Email);

        if ($stmt->execute()) {
            // Redirige si todo salió bien
            header("Location: ../index.php?page=components/instructores/instructores&success=Registro+exitoso");
            exit();
        } else {
            // Mostrar error de ejecución
            die("❌ Error al ejecutar la consulta: " . $stmt->error);
        }

        $stmt->close();
    } else {
        // Error al preparar la consulta
        die("❌ Error al preparar la consulta: " . $conn->error);
    }
} else {
    die("❌ Faltan campos obligatorios en el formulario.");
}
?>