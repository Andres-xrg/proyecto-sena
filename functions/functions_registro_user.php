<?php
require_once __DIR__ . '/../db/conexion.php';
require_once __DIR__ . '/historial.php';

function registrar_usuario($conn, $data, $usuario_id = 0) {
    $nombre       = trim($data['nombre'] ?? '');
    $apellidos    = trim($data['apellidos'] ?? '');
    $telefono     = trim($data['telefono'] ?? '');
    $tipo_doc     = trim($data['tipo_documento'] ?? '');
    $documento    = trim($data['documento'] ?? '');
    $email        = trim($data['email'] ?? '');
    $contrasena   = $data['contrasena'] ?? '';
    $confirmar    = $data['confirmar_contrasena'] ?? '';

    $errores = [];

    // Validar contraseñas
    if ($contrasena !== $confirmar) {
        $errores[] = "Las contraseñas no coinciden.";
    }

    // 🚨 Validar documento y teléfono que no sean negativos ni letras
    if (!ctype_digit($documento) || intval($documento) < 0) {
        $errores[] = "El número de documento no puede ser negativo ni contener letras.";
    }
    if (!ctype_digit($telefono) || intval($telefono) < 0) {
        $errores[] = "El número de teléfono no puede ser negativo ni contener letras.";
    }

    // Validar correo duplicado
    $stmt = $conn->prepare("SELECT 1 FROM usuarios WHERE Email = ?");
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $res->num_rows > 0) {
            $errores[] = "El correo ya está registrado.";
        }
        $stmt->close();
    }

    // Validar documento duplicado
    $stmt = $conn->prepare("SELECT 1 FROM usuarios WHERE N_Documento = ?");
    if ($stmt) {
        $stmt->bind_param("s", $documento);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $res->num_rows > 0) {
            $errores[] = "El número de documento ya está registrado.";
        }
        $stmt->close();
    }

    // Si hay errores, devolverlos
    if (!empty($errores)) {
        return [
            "estado" => "error",
            "mensaje" => implode("<br>", $errores)
        ];
    }

    // Hash de contraseña
    $contrasena_hash = password_hash($contrasena, PASSWORD_BCRYPT);
    $rol = 'Usuario';

    // Insertar usuario
    $stmt = $conn->prepare("
        INSERT INTO usuarios (
            nombre, apellido, N_Telefono, T_Documento, N_Documento, Email, Contraseña, Rol
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    if (!$stmt) {
        return [
            "estado" => "error",
            "mensaje" => "Error al preparar consulta: " . $conn->error
        ];
    }

    $stmt->bind_param("ssssssss", 
        $nombre, $apellidos, $telefono, $tipo_doc, $documento, $email, $contrasena_hash, $rol
    );

    if ($stmt->execute()) {
        $id_usuario_nuevo = $conn->insert_id;
        $stmt->close();

        // Registrar historial si hay usuario en sesión
        if ($usuario_id > 0) {
            $descripcion = "Se registró el usuario $nombre $apellidos con documento $documento.";
            registrar_historial($conn, $usuario_id, 'Registro de usuario', $descripcion);
        }

        return [
            "estado" => "success",
            "mensaje" => "Usuario registrado correctamente."
        ];
    } else {
        $error = $stmt->error;
        $stmt->close();
        return [
            "estado" => "error",
            "mensaje" => "Error al registrar usuario: " . $error
        ];
    }
}
