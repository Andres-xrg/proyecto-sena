<?php
require_once '../db/conexion.php';
require_once '../functions/historial.php';
session_start();

// Inicializar variables
$estado = "";
$mensaje = "";

if (
    isset($_POST['nombre'], $_POST['apellido'], $_POST['tipoDocumento'],
          $_POST['numeroDocumento'], $_POST['instructor'], $_POST['telefono'], 
          $_POST['Email'], $_POST['contraseña'], $_POST['rol_instructor'],
          $_POST['fecha_inicio'], $_POST['fecha_fin'])
) {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $tipoDocumento = strtoupper(trim($_POST['tipoDocumento'] ?? '')); 
    $numeroDocumento = trim($_POST['numeroDocumento']);
    $tipoInstructor = trim($_POST['instructor']); 
    $rolInstructor = trim($_POST['rol_instructor']); 
    $telefono = trim($_POST['telefono']);
    $Email = trim($_POST['Email']);
    $contraseña = trim($_POST['contraseña']);
    $fecha_inicio = !empty($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : null;
    $fecha_fin = !empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null;

    $errores = [];

    // ✅ Validaciones básicas
    $tiposPermitidos = ['CC','CE','TI','PAS'];
    if (!in_array($tipoDocumento, $tiposPermitidos)) {
        $errores[] = "Tipo de documento inválido.";
    }
    if (!in_array($tipoInstructor, ['contratista', 'planta'])) {
        $errores[] = "Tipo de instructor inválido.";
    }
    if (!in_array($rolInstructor, ['clave', 'transversal', 'tecnico'])) {
        $errores[] = "Rol del instructor inválido.";
    }

    // ✅ Validación de negativos (número documento y teléfono)
    if (!ctype_digit($numeroDocumento) || (int)$numeroDocumento < 0) {
        $errores[] = "El número de documento no puede ser negativo ni contener caracteres no numéricos.";
    }
    if (!ctype_digit($telefono) || (int)$telefono < 0) {
        $errores[] = "El número de teléfono no puede ser negativo ni contener caracteres no numéricos.";
    }

    // ✅ Validación de fechas (si es contratista)
    if ($tipoInstructor === 'contratista') {
        if (empty($fecha_inicio) || empty($fecha_fin)) {
            $errores[] = "Debe especificar fechas de contrato para un instructor contratista.";
        } elseif (strtotime($fecha_inicio) > strtotime($fecha_fin)) {
            $errores[] = "La fecha de inicio no puede ser mayor que la fecha de fin del contrato.";
        } elseif (strtotime(date('Y-m-d')) > strtotime($fecha_fin)) {
            $errores[] = "El contrato ya ha vencido. El instructor será deshabilitado automáticamente.";
        }
    }

    // ✅ Validar correo duplicado
    $stmtEmail = $conn->prepare("SELECT Id_usuario FROM usuarios WHERE Email = ?");
    $stmtEmail->bind_param("s", $Email);
    $stmtEmail->execute();
    $stmtEmail->store_result();
    if ($stmtEmail->num_rows > 0) {
        $errores[] = "El correo ya ha sido registrado anteriormente.";
    }
    $stmtEmail->close();

    // ✅ Validar documento duplicado
    $stmtDoc = $conn->prepare("SELECT Id_usuario FROM usuarios WHERE N_Documento = ?");
    $stmtDoc->bind_param("s", $numeroDocumento);
    $stmtDoc->execute();
    $stmtDoc->store_result();
    if ($stmtDoc->num_rows > 0) {
        $errores[] = "El número de documento ya ha sido registrado anteriormente.";
    }
    $stmtDoc->close();

    // 🚨 Si hay errores, redirigir con SweetAlert
    if (!empty($errores)) {
        $mensajes = implode('|', $errores);
        header("Location: ../index.php?page=components/registros/registro_instructor&estado=error&mensajes=" . urlencode($mensajes));
        exit;
    }

    // Si no es contratista, limpiar fechas
    if ($tipoInstructor !== 'contratista') {
        $fecha_inicio = null;
        $fecha_fin = null;
    }

    // ✅ Encriptar contraseña
    $hash = password_hash($contraseña, PASSWORD_DEFAULT);

    // Insertar en usuarios
    $queryUser = "INSERT INTO usuarios 
        (nombre, apellido, Email, T_Documento, N_Documento, N_Telefono, rol, Contraseña) 
        VALUES (?, ?, ?, ?, ?, ?, 'instructor', ?)";
    $stmtUser = $conn->prepare($queryUser);

    if ($stmtUser) {
        $stmtUser->bind_param("sssssss", $nombre, $apellido, $Email, $tipoDocumento, $numeroDocumento, $telefono, $hash);
        if ($stmtUser->execute()) {
            $idUsuario = $stmtUser->insert_id;

            // Insertar en instructores
            $queryInstructor = "INSERT INTO instructores 
                (nombre, apellido, T_documento, N_Documento, Tipo_instructor, N_Telefono, Email, fecha_inicio_contrato, fecha_fin_contrato, Contraseña, Id_usuario, rol_instructor) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmtInstructor = $conn->prepare($queryInstructor);

            if ($stmtInstructor) {
                $stmtInstructor->bind_param(
                    "ssssssssssss",
                    $nombre,
                    $apellido,
                    $tipoDocumento,
                    $numeroDocumento,
                    $tipoInstructor,
                    $telefono,
                    $Email,
                    $fecha_inicio,
                    $fecha_fin,
                    $hash,
                    $idUsuario,
                    $rolInstructor
                );

                if ($stmtInstructor->execute()) {
                    $usuario_id = $_SESSION['usuario']['id'] ?? null;
                    $descripcion = "Se registró el instructor $nombre $apellido, Documento: $numeroDocumento, Rol: $rolInstructor";
                    registrar_historial($conn, $usuario_id, 'Registro de instructor', $descripcion);

                     header("Location: ../index.php?page=components/instructores/instructores&success=creado");
                    exit;
                } else {
                    $mensaje = "Error al registrar instructor: " . $stmtInstructor->error;
                }
            } else {
                $mensaje = "Error al preparar instructor: " . $conn->error;
            }
        } else {
            $mensaje = "Error al registrar usuario: " . $stmtUser->error;
        }
    } else {
        $mensaje = "Error al preparar usuario: " . $conn->error;
    }
} else {
    $mensaje = "Campos incompletos. Verifica la información.";
}

if (!empty($mensaje)) {
    header("Location: ../index.php?page=components/registros/registro_instructor&estado=error&mensajes=" . urlencode($mensaje));
    exit;
}
