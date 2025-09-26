<?php
require '../vendor/autoload.php'; // Si usas Composer
use SendGrid\Mail\Mail;

function enviarCorreoRecuperacion($correoDestino, $token) {
    // Enlace de restablecimiento
    $enlace = "http://localhost/proyecto-sena/components/principales/reset_password.php?token=" . urlencode($token);

    // Crear objeto de correo
    $email = new Mail();
    $email->setFrom("edwinandresrangelgomez8@gmail.com", "Soporte Técnico"); // remitente verificado en SendGrid
    $email->setSubject("Recuperación de contraseña");
    $email->addTo($correoDestino);

    // Contenido HTML del correo
    $mensaje = "
        <div style='font-family: Arial, sans-serif; padding: 20px; background-color: #f8f8f8; border-radius: 10px;'>
            <h2 style='color: #333;'>Recuperación de contraseña</h2>
            <p style='font-size: 16px;'>Haz clic en el siguiente enlace para restablecer tu contraseña:</p>
            <a href='$enlace' style='display: inline-block; padding: 10px 20px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px;'>Restablecer contraseña</a>
            <p style='margin-top: 20px; font-size: 14px;'>Este enlace expirará dentro de 5 minutos.</p>
        </div>
    ";

    $email->addContent("text/html", $mensaje);

    // Instanciar SendGrid con tu API Key

    try {
        $response = $sendgrid->send($email);

        if ($response->statusCode() == 202) {
            return true; // ✅ correo aceptado por SendGrid
        } else {
            echo "❌ Error al enviar. Código: " . $response->statusCode();
            return false;
        }
    } catch (Exception $e) {
        echo "❌ Excepción atrapada: " . $e->getMessage();
        return false;
    }
}
?>
