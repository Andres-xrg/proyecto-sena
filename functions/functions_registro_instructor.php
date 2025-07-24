<?php
require_once '../db/conexion.php';
require_once '../functions/historial.php';
session_start();

// Validar campos obligatorios
if (
    isset($_POST['nombre'], $_POST['apellido'], $_POST['ficha'], $_POST['tipoDocumento'],
          $_POST['numeroDocumento'], $_POST['instructor'], $_POST['telefono'], $_POST['Email'])
) {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $tipoDocumento = trim($_POST['tipoDocumento']);
    $numeroDocumento = trim($_POST['numeroDocumento']);
    $tipoInstructor = trim($_POST['instructor']);
    $telefono = trim($_POST['telefono']);
    $Email = trim($_POST['Email']);

    // Validación y conversión segura del campo ficha
    $ficha = trim($_POST['ficha']);
    if ($ficha === '' || !is_numeric($ficha)) {
        header("Location: ../index.php?page=components/instructores/registro_instructor&error=ficha-invalida");
        exit;
    }
    $ficha = (int)$ficha;

    // Preparar e insertar los datos
    $query = "INSERT INTO instructores (nombre, apellido, Ficha, T_documento, N_Documento, Tipo_instructor, N_Telefono, email)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssiissss", $nombre, $apellido, $ficha, $tipoDocumento, $numeroDocumento, $tipoInstructor, $telefono, $Email);

    if ($stmt->execute()) {
        // Registrar en historial si hay sesión activa
        $usuario_id = $_SESSION['usuario']['id'] ?? null;
        $descripcion = "Se registró el instructor $nombre $apellido, Documento: $numeroDocumento, Ficha: $ficha";
        registrar_historial($conn, $usuario_id, 'Registro de instructor', $descripcion);

        header("Location: ../index.php?page=components/instructores/instructores&success=creado");
        exit;
    } else {
        // Si falla la ejecución, mostrar error
        die("Error al registrar: " . $stmt->error);
    }
} else {
    header("Location: ../index.php?page=components/instructores/registro_instructor&error=campos-incompletos");
    exit;
}
