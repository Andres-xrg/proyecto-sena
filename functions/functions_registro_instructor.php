<?php
if (!ACCESO_PERMITIDO){
    header("Location: /proyecto-sena/components/principales/login.php");
}
require_once '../db/conexion.php';
require_once '../functions/historial.php';
session_start();

// Validar campos
if (
    isset($_POST['nombre'], $_POST['apellido'], $_POST['ficha'], $_POST['tipoDocumento'],
          $_POST['numeroDocumento'], $_POST['instructor'], $_POST['telefono'], $_POST['Email'])
) {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $ficha = trim($_POST['ficha']);
    $tipoDocumento = trim($_POST['tipoDocumento']);
    $numeroDocumento = trim($_POST['numeroDocumento']);
    $tipoInstructor = trim($_POST['instructor']);
    $telefono = trim($_POST['telefono']);
    $Email = trim($_POST['Email']);

    $query = "INSERT INTO instructores (nombre, apellido, Ficha, T_documento, N_Documento, Tipo_instructor, N_Telefono, Email)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssss", $nombre, $apellido, $ficha, $tipoDocumento, $numeroDocumento, $tipoInstructor, $telefono, $Email);

    if ($stmt->execute()) {
        $usuario_id = $_SESSION['usuario']['id'] ?? null;
        $descripcion = "Se registró el instructor $nombre $apellido, Documento: $numeroDocumento, Ficha: $ficha";
        registrar_historial($conn, $usuario_id, 'Registro de instructor', $descripcion);

        header("Location: ../index.php?page=components/instructores/instructores&success=Registro+exitoso");
        exit;
    } else {
        die("Error al registrar: " . $stmt->error);
    }
}
