<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Si usas Composer

function enviarCorreoRecuperacion($correoDestino, $token) {
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        // ✅ Correo y contraseña de aplicación
        $mail->Username = 'edwinandresrangelgomez8@gmail.com';
        $mail->Password = 'bzoiqvhjbtvtjpcz'; // sin espacios

        $mail->SMTPSecure = 'tls'; // o 'ssl'
        $mail->Port = 587;

        // ✅ Remitente y destinatario
        $mail->setFrom('edwinandresrangelgomez8@gmail.com', 'Soporte Técnico');
        $mail->addAddress($correoDestino);

        // ✅ Contenido del mensaje
        $mail->isHTML(true);
        $mail->Subject = 'Recuperación de contraseña';

        // ✅ Enlace correcto
        $enlace = "http://localhost/proyecto-sena/components/principales/reset_password.php?token=" . urlencode($token);

        $mail->Body = "
            <h2>Recuperacion de contraseña/h2>
            <p>Haz clic en el siguiente enlace para restablecer tu contraseña:</p>
            <a href='$enlace'>$enlace</a>
            <p>Este enlace expirará dentro de 1 hora.</p>
        ";

        $mail->send();
        return true;

    } catch (Exception $e) {
        echo "❌ Error al enviar el correo: " . $mail->ErrorInfo;
        exit;
    }
}
?>
