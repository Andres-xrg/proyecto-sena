<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Si usas Composer

function enviarCorreoRecuperacion($correoDestino, $token) {
    $mail = new PHPMailer(true);

    try {
        $mail->CharSet = 'UTF-8'; // ✅ Soporte para caracteres especiales

        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'edwinandresrangelgomez8@gmail.com';
        $mail->Password = 'bzoiqvhjbtvtjpcz'; // contraseña de aplicación
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Remitente y destinatario
        $mail->setFrom('edwinandresrangelgomez8@gmail.com', 'Soporte Técnico');
        $mail->addAddress($correoDestino);

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Recuperación de contraseña';

        $enlace = "http://localhost/proyecto-sena/components/principales/reset_password.php?token=" . urlencode($token);

        $mensaje = "
            <div style='font-family: Arial, sans-serif; padding: 20px; background-color: #f8f8f8; border-radius: 10px;'>
                <h2 style='color: #333;'>Recuperación de contraseña</h2>
                <p style='font-size: 16px;'>Haz clic en el siguiente enlace para restablecer tu contraseña:</p>
                <a href='$enlace' style='display: inline-block; padding: 10px 20px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px;'>Restablecer contraseña</a>
                <p style='margin-top: 20px; font-size: 14px;'>Este enlace expirará dentro de 1 hora.</p>
            </div>
        ";

        $mail->Body = $mensaje;
        $mail->send();
        return true;

    } catch (Exception $e) {
        echo "❌ Error al enviar el correo: " . $mail->ErrorInfo;
        exit;
    }
}
?>
