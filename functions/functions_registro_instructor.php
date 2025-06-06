<?php
include('../db/conexion.php');

// Verificar conexión a la base de datos
if (!$conn) {
    echo "<script>
        alert('Error de conexión a la base de datos: " . mysqli_connect_error() . "');
        window.history.back();
    </script>";
    exit();
}

// Verificar si se envió el formulario
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
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $ficha = trim($_POST['ficha']);
    $tipoDocumento = trim($_POST['tipoDocumento']);
    $numeroDocumento = trim($_POST['numeroDocumento']);
    $tipoInstructor = trim($_POST['instructor']);
    $telefono = trim($_POST['telefono']);
    $Email = trim($_POST['Email']);

    $query = "INSERT INTO instructores 
        (nombre, apellido, Ficha, T_documento, N_documento, Tipo_instructor, N_Telefono, Email) 
        VALUES 
        ('$nombre', '$apellido', '$ficha', '$tipoDocumento', '$numeroDocumento', '$tipoInstructor', '$telefono', '$Email')";

    $insert = mysqli_query($conn, $query);

    if ($insert) {
        header("Location: ../index.php?page=components/principales/welcome&success=Registro+exitoso");
        exit();
    } else {
        echo "<script>
            alert('Error al enviar los datos: " . mysqli_error($conn) . "');
            window.history.back();
        </script>";
    }
} else {
    header("Location: ../index.php?page=components/registros/registro_instructor&error=Faltan+campos");
    exit();
}
?>