<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
<link rel="stylesheet" href="/proyecto-sena/assets/css/login.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

</head>
<body>
    <div class="left-panel">
        <div class="wave-top"></div>
        <h1 class="welcome-text">BIENVENIDO</h1>
        <div class="wave-bottom"></div>
    </div>
    
    <div class="right-panel">
        <h2 class="login-title">LOGIN</h2>
        <p class="login-subtitle">Ingrese El email y la contraseña</p>
        
        <div class="user-icon">
            <img src="/proyecto-sena/assets/img/logo-inicio.png" alt="User Icon">
        </div>
            <div class="login-container">
            <?php if (isset($_GET['status']) && $_GET['status'] == 1): ?>
    <p style="color: red; text-align: center;">Correo o contraseña incorrectos. Intenta de nuevo.</p>
<?php endif; ?>
<form action="../functions/procesar_login.php" method="POST">
    <div class="form-group">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-input" required>
    </div>          
            
    <div class="form-group">
        <label class="form-label">Contraseña</label>
        <input type="password" name="contraseña" class="form-input" required>
    </div>          
    
    <div class="form-actions">
        <button type="submit" class="continue-button">Ingresar</button>
        <!-- <a href="registrarse.php" class="register-link">Registrarse</a> -->
    </div>
</form>
        </div>
    </div>
</body>
</html>