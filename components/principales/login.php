<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Consultar si el usuario existe
    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $usuario = $resultado->fetch_assoc();
    $stmt->close();

    if ($usuario && password_verify($password, $usuario['password'])) {
        // Login exitoso
        $_SESSION['usuario'] = $usuario; // Guardar información del usuario en la sesión

        // Verificar si hay productos en el carrito
        if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
            // Si hay productos en el carrito, redirigir al carrito
            header("Location: /Miboleta1/Miboleta/pages/carrito.php");
        } else {
            // Si no hay productos, redirigir al inicio o donde desees
            header("Location: /Miboleta1/Miboleta/pages/index.php");
        }
        exit();
    } else {
        $error = "Credenciales incorrectas.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../assets/css/login.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>

    </style>
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
            <img src="../assets/img/logo-inicio.png" alt="User Icon">
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
        <a href="welcome.php" class="continue-button">Ingresar</a>
        <!-- <a href="registrarse.php" class="register-link">Registrarse</a> -->
    </div>
</form>
        </div>
    </div>
</body>
</html>