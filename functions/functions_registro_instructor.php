<?php
require_once '../db/conexion.php';
require_once '../functions/historial.php';
session_start();

if (
    isset($_POST['nombre'], $_POST['apellido'], $_POST['ficha'], $_POST['tipoDocumento'],
          $_POST['numeroDocumento'], $_POST['instructor'], $_POST['telefono'], $_POST['Email'])
) {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $tipoDocumento = strtoupper(trim($_POST['tipoDocumento'] ?? '')); // <- aquí tomamos el valor puro
    $numeroDocumento = trim($_POST['numeroDocumento']);
    $tipoInstructor = trim($_POST['instructor']);
    $telefono = trim($_POST['telefono']);
    $Email = trim($_POST['Email']);
    $fecha_inicio = !empty($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : null;
    $fecha_fin = !empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null;

    $ficha = trim($_POST['ficha']);
    $ficha = ($ficha !== '' && is_numeric($ficha)) ? (int)$ficha : null;

    // 💥 Validación estricta
    if (!in_array($tipoDocumento, ['CC', 'CE'])) {
        die("Error: Tipo de documento inválido.");
    }

    if (!in_array($tipoInstructor, ['contratista', 'planta'])) {
        die("Error: Tipo de instructor inválido.");
    }

    if ($tipoInstructor !== 'contratista') {
        $fecha_inicio = null;
        $fecha_fin = null;
    }

    // Insertar en la tabla
    $query = "INSERT INTO instructores 
        (nombre, apellido, Ficha, T_documento, N_Documento, Tipo_instructor, N_Telefono, email, fecha_inicio_contrato, fecha_fin_contrato) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Error al preparar la consulta: " . $conn->error);
    }

    $stmt->bind_param(
        "sssissssss",
        $nombre,
        $apellido,
        $ficha,
        $tipoDocumento,       
        $numeroDocumento,
        $tipoInstructor,
        $telefono,
        $Email,
        $fecha_inicio,
        $fecha_fin
    );

    if ($stmt->execute()) {
        $usuario_id = $_SESSION['usuario']['id'] ?? null;
        $descripcion = "Se registró el instructor $nombre $apellido, Documento: $numeroDocumento, Ficha: $ficha";
        registrar_historial($conn, $usuario_id, 'Registro de instructor', $descripcion);

        header("Location: ../index.php?page=components/instructores/instructores&success=creado");
        exit;
    } else {
        die("Error al registrar: " . $stmt->error);
    }
} else {
    header("Location: ../index.php?page=components/instructores/registro_instructor&error=campos-incompletos");
    exit;
}
