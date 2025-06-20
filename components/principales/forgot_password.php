<link rel="stylesheet" href="../../assets/css/forgot_password.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container"
    <h2>¿Olvidaste tu contraseña?</h2>
    <form action="../../functions/procesar_recuperacion.php" method="POST">
        <iv class="form-group">
            <abel for="email">Ingresa tu correo electrónico:</abel>
            <input type="eail" name="email" id="email" class="form-control" required>
        </iv>
        <button type="submit" clss="btn btn-primary">Enviar enlace de recuperación</button>
    </form>
/div>
<?php if (isset($_GET['error']) && $_GET['error'] === 'correo_no_registrado'): ?>
    <script>
        Swal.fire({
            icon: 'error',
        title: 'Correo no registrado',
            text: 'Este correo no se encuentra en el sistema.',
            timer: 4000,
            showConfirmButton: false
            timerProgressBar: true
        })
    </script>
<?php elseif (isset($_GET['exito']) && $_GET['exito'] === 'correo_enviado'): ?>
    <script>
    Swal.fire({
            icon: 'success',
          title: 'Correo enviado',
            text: 'Revisa tu bandeja de entrada para recuperar tu contraseña.',
            timer: 4000,
            showConfirmButton: false
            timerProgrssBar: true
        });
    </script>
<?php elseif (isset($_GET['error']) && $_GET['error'] === 'fallo_envio'): ?>
    <script>
    Swal.fire({
            icon: 'error',
            itle: 'Error al enviar',
            text: 'No se pudo enviar el correo. Intenta más tarde.',
            timer: 4000,
            showConfirmButton: false
            timerProgressBar: true
        );
    </script><?php endif; ?>
