<?php
require_once '../db/conexion.php';
require_once 'correo_recuperacion.php'; // 🔹 Asegúrate de tener este archivo

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['email'])) {
    $email = trim($_POST['email']);

    // Buscar si el correo existe
    $stmt = $conn->prepare("SELECT Id_usuario FROM usuarios WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();
        $id_usuario = $usuario['Id_usuario'];

        // Generar token único y fecha de expiración (ej. 1 hora)
        $token = bin2hex(random_bytes(32));
        $expiracion = date("Y-m-d H:i:s", strtotime('+1 hour'));

        // Guardar token en la base de datos
        $update = $conn->prepare("UPDATE usuarios SET token_recuperacion = ?, token_expiracion = ? WHERE Id_usuario = ?");
        $update->bind_param("ssi", $token, $expiracion, $id_usuario);
        $update->execute();

        // ✅ Enviar el correo real usando PHPMailer
        if (enviarCorreoRecuperacion($email, $token)) {
            header("Location: ../components/principales/login.php?status=correo_enviado");
            exit;
        } else {
            echo "❌ Error al enviar el correo. Intenta más tarde.";
        }

    } else {
        // Usuario no existe
        header("Location: ../components/principales/forgot_password.php?error=no_existe");
        exit;
    }
}
?>
