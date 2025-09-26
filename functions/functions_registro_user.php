<?php
session_start();
require_once '../db/conexion.php';
require_once '../functions/historial.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre       = trim($_POST['nombre'] ?? '');
    $apellidos    = trim($_POST['apellidos'] ?? '');
    $telefono     = trim($_POST['telefono'] ?? '');
    $tipo_doc     = trim($_POST['tipo_documento'] ?? '');
    $documento    = trim($_POST['documento'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $contrasena   = $_POST['contrasena'] ?? '';
    $confirmar    = $_POST['confirmar_contrasena'] ?? '';

    // Validar contraseñas
    if ($contrasena !== $confirmar) {
        $mensaje = "Las contraseñas no coinciden.";
        $estado = "error";
    } else {
        $contrasena_hash = password_hash($contrasena, PASSWORD_BCRYPT);
        $rol = 'Usuario';

        $stmt = $conn->prepare("
            INSERT INTO usuarios (
                nombre, apellido, N_Telefono, T_Documento, N_Documento, Email, Contraseña, Rol
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        if ($stmt) {
            $stmt->bind_param("ssssssss", $nombre, $apellidos, $telefono, $tipo_doc, $documento, $email, $contrasena_hash, $rol);

            if ($stmt->execute()) {
                $id_usuario_nuevo = $conn->insert_id;

                $stmt_ap = $conn->prepare("
                    INSERT INTO aprendices (Id_usuario, nombre, apellido, T_documento, N_Documento, N_Telefono, Email)
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt_ap->bind_param("issssss", $id_usuario_nuevo, $nombre, $apellidos, $tipo_doc, $documento, $telefono, $email);
                $stmt_ap->execute();

                if (isset($_SESSION['usuario']['id'])) {
                    $usuario_id = $_SESSION['usuario']['id'];
                    $descripcion = "Se registró el usuario $nombre $apellidos con documento $documento.";
                    registrar_historial($conn, $usuario_id, 'Registro de usuario', $descripcion);
                }

                $mensaje = "Usuario registrado correctamente.";
                $estado = "success";
            } else {
                $mensaje = "Error al registrar usuario: " . $stmt->error;
                $estado = "error";
            }
        } else {
            $mensaje = "Error al preparar consulta: " . $conn->error;
            $estado = "error";
        }
    }
} else {
    $mensaje = "Acceso no permitido.";
    $estado = "error";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro Usuario</title>
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
            window.location.href = "../index.php?page=components/principales/welcome";
        <?php else : ?>
            window.history.back();
        <?php endif; ?>
    });
</script>
</body>
</html>
