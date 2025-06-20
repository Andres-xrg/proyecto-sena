<link rel="stylesheet" href="../../assets/css/forgot_password.css">
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
