<?php
require_once '../db/conexion.php';
require_once 'correo_recuperacion.php'; // PHPMailer
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['email'])) {
    $email = trim($_POST['email']);

    // Verificar si el correo existe
    $stmt = $conn->prepare("SELECT Id_usuario FROM usuarios WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();
        $id_usuario = $usuario['Id_usuario'];

        // Crear token y expiración
        $token = bin2hex(random_bytes(32));
        $expiracion = date("Y-m-d H:i:s", strtotime('+1 hour'));

        // Guardar token y expiración
        $update = $conn->prepare("UPDATE usuarios SET token_recuperacion = ?, token_expiracion = ? WHERE Id_usuario = ?");
        $update->bind_param("ssi", $token, $expiracion, $id_usuario);
        $update->execute();

        // Enviar correo
        if (enviarCorreoRecuperacion($email, $token)) {
            header("Location: ../components/principales/forgot_password.php?exito=correo_enviado");
            exit;
        } else {
            // Si el envío falla, puedes mostrar un error más adelante con ?error=fallo_envio
            header("Location: ../components/principales/forgot_password.php?error=fallo_envio");
            exit;
        }
    } else {
        // Correo no registrado
        header("Location: ../components/principales/forgot_password.php?error=correo_no_registrado");
        exit;
    }
}
?>
