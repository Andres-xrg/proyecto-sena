<!DOCTYPE html>
 <html lang="es"> 
    <head> 
        <meta charset="UTF-8">
         <link rel="stylesheet" href="/proyecto-sena/assets/css/login.css">
          <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
          <title>Login</title>
         </head>
          <body> 
            <div class="container-login"> <!-- NUEVO CONTENEDOR FLEX --> 
                <div class="left-panel"> 
                    <div class="wave-top"></div>
                     <h1 class="welcome-text">BIENVENIDO</h1>
                      <div class="wave-bottom">
                     </div> </div>
    <div class="right-panel">
        
        <div class="login-container">
            <h2 class="login-title">LOGIN</h2>
            <p class="login-subtitle">Ingrese El email y la contraseña</p>
            
            <div class="user-icon">
                <img src="/proyecto-sena/assets/img/logo-inicio.png" alt="User Icon">
            </div>
            <?php if (isset($_GET['status']) && $_GET['status'] == 1): ?>
                <p style="color: red; text-align: center;     animation: fadeInForm 0.8s ease;">Correo o contraseña incorrectos. Intenta de nuevo.</p>
            <?php endif; ?>
    
            <?php if (isset($_GET['logout']) && $_GET['logout'] == 1): ?>
                <div style="display: flex;background-color: #d4edda;color: #155724;padding: 10px;border-radius: 5px;margin-bottom: 15px;text-align: center;fadeInForm 0.8s ease;align-items: center;justify-content: center;gap: 10px;">
                    <img src="/proyecto-sena/assets/img/alert.png" alt="img-alert" style="witdh: 25px; height:25px; margin-bottom: 1px;"> Sesión cerrada correctamente.
                </div>
            <?php endif; ?>

            <form action="/proyecto-sena/functions/functions_procesar_login.php" method="POST">
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" required>
                </div>          

                <div class="form-group">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="contraseña" class="form-input" required>
                </div>          
                     <a href="/proyecto-sena/components/principales/forgot_password.php">¿Olvidaste tu contraseña?</a>
                <div class="form-actions">
                    <button type="submit" class="continue-button">Ingresar</button>
                </div>
            </form>
        </div>
    </div>
</div>