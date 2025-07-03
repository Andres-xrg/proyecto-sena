<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="../../assets/css/forgot_password.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <h2>¿Olvidaste tu contraseña?</h2>
        
        <form action="../../functions/procesar_recuperacion.php" method="POST">
            <div class="form-group">
                <label for="email">Ingresa tu correo electrónico:</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Enviar enlace de recuperación</button>
        </form>
    </div>

    <!-- Scripts de SweetAlert para mensajes -->
    <?php if (isset($_GET['error']) && $_GET['error'] === 'correo_no_registrado'): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Correo no registrado',
                text: 'Este correo no se encuentra en el sistema.',
                timer: 4000,
                showConfirmButton: false,
                timerProgressBar: true
            });
        </script>
    <?php elseif (isset($_GET['exito']) && $_GET['exito'] === 'correo_enviado'): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Correo enviado',
                text: 'Revisa tu bandeja de entrada para recuperar tu contraseña.',
                timer: 4000,
                showConfirmButton: false,
                timerProgressBar: true
            });
        </script>
    <?php elseif (isset($_GET['error']) && $_GET['error'] === 'fallo_envio'): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error al enviar',
                text: 'No se pudo enviar el correo. Intenta más tarde.',
                timer: 4000,
                showConfirmButton: false,
                timerProgressBar: true
            });
        </script>
    <?php endif; ?>
</body>
</html>
