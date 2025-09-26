<?php
require_once '../db/conexion.php';
require_once '../functions/historial.php';
session_start();

if (
    isset($_POST['nombre'], $_POST['apellido'], $_POST['tipoDocumento'],
          $_POST['numeroDocumento'], $_POST['instructor'], $_POST['telefono'], 
          $_POST['Email'], $_POST['contraseña'], $_POST['rol_instructor'])
) {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $tipoDocumento = strtoupper(trim($_POST['tipoDocumento'] ?? '')); 
    $numeroDocumento = trim($_POST['numeroDocumento']);
    $tipoInstructor = trim($_POST['instructor']); // contratista o planta
    $rolInstructor = trim($_POST['rol_instructor']); // clave, transversal, tecnico
    $telefono = trim($_POST['telefono']);
    $Email = trim($_POST['Email']);
    $contraseña = trim($_POST['contraseña']);
    $fecha_inicio = !empty($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : null;
    $fecha_fin = !empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null;

    // ✅ Validación de tipo de documento
    $tiposPermitidos = ['CC','CE','TI','PAS'];
    if (!in_array($tipoDocumento, $tiposPermitidos)) {
        $mensaje = "Tipo de documento inválido.";
        $estado = "error";
    } 
    // ✅ Validación de tipo de instructor
    elseif (!in_array($tipoInstructor, ['contratista', 'planta'])) {
        $mensaje = "Tipo de instructor inválido.";
        $estado = "error";
    } 
    // ✅ Validación de rol del instructor
    elseif (!in_array($rolInstructor, ['clave', 'transversal', 'tecnico'])) {
        $mensaje = "Rol del instructor inválido.";
        $estado = "error";
    } 
    else {
        // Si no es contratista, limpiar fechas
        if ($tipoInstructor !== 'contratista') {
            $fecha_inicio = null;
            $fecha_fin = null;
        }

        // ✅ Encriptar contraseña
        $hash = password_hash($contraseña, PASSWORD_DEFAULT);

        // Paso 1: Insertar en usuarios
        $queryUser = "INSERT INTO usuarios 
            (nombre, apellido, Email, T_Documento, N_Documento, N_Telefono, rol, Contraseña) 
            VALUES (?, ?, ?, ?, ?, ?, 'instructor', ?)";

        $stmtUser = $conn->prepare($queryUser);
        if ($stmtUser) {
            $stmtUser->bind_param("sssssss", $nombre, $apellido, $Email, $tipoDocumento, $numeroDocumento, $telefono, $hash);

            if ($stmtUser->execute()) {
                $idUsuario = $stmtUser->insert_id;

                // Paso 2: Insertar en instructores **con rol**
                $queryInstructor = "INSERT INTO instructores 
                    (nombre, apellido, T_documento, N_Documento, Tipo_instructor, N_Telefono, Email, fecha_inicio_contrato, fecha_fin_contrato, Contraseña, Id_usuario, rol_instructor) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $stmtInstructor = $conn->prepare($queryInstructor);
                if ($stmtInstructor) {
                    $stmtInstructor->bind_param(
                        "sssissssssss",
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

                        $mensaje = "El instructor fue registrado correctamente.";
                        $estado = "success";
                    } else {
                        $mensaje = "Error al registrar instructor: " . $stmtInstructor->error;
                        $estado = "error";
                    }
                } else {
                    $mensaje = "Error al preparar instructor: " . $conn->error;
                    $estado = "error";
                }
            } else {
                $mensaje = "Error al registrar usuario: " . $stmtUser->error;
                $estado = "error";
            }
        } else {
            $mensaje = "Error al preparar usuario: " . $conn->error;
            $estado = "error";
        }
    }
} else {
    $mensaje = "Campos incompletos. Verifica la información.";
    $estado = "error";
}

// ✅ Mostrar alerta con SweetAlert2 y redirigir
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro Instructor</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<script>
    Swal.fire({
        icon: '<?= $estado ?>',
        title: '<?= ($estado === "success") ? "Éxito" : "Error" ?>',
        text: '<?= $mensaje ?>',
        confirmButtonText: 'OK'
    }).then(() => {
        <?php if ($estado === "success") : ?>
            window.location.href = "../index.php?page=components/instructores/instructores";
        <?php else : ?>
            window.history.back();
        <?php endif; ?>
    });
</script>
</body>
</html>
