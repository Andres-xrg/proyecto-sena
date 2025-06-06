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
    isset($_POST['tipoDocumento']) &&
    isset($_POST['numeroDocumento']) &&
    isset($_POST['telefono']) &&
    isset($_POST['Email'])
) {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $tipoDocumento = trim($_POST['tipoDocumento']);
    $numeroDocumento = trim($_POST['numeroDocumento']);
    $telefono = trim($_POST['telefono']);
    $Email = trim($_POST['Email']);

    $query = "INSERT INTO aprendices 
        (nombre, apellido, T_documento, N_Documento, N_Telefono, Email) 
        VALUES 
        ('$nombre', '$apellido', '$tipoDocumento', '$numeroDocumento', '$telefono', '$Email')";

    $insert = mysqli_query($conn, $query);

    if ($insert) {
        echo "<script>
            alert('Datos enviados correctamente.');
            window.location.href = '../index.php?page=components/principales/welcome&success=Registro+exitoso';
        </script>";
    } else {
        echo "<script>
            alert('Error al enviar los datos: " . mysqli_error($conn) . "');
            window.history.back();
        </script>";
    }
} else {
    echo "<script>
        alert('Por favor, completa todos los campos del formulario.');
        window.history.back();
    </script>";
}
?>