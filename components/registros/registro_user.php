<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!defined('ACCESO_PERMITIDO')){
    header("Location: /proyecto-sena/components/principales/login.php");
    exit();
}

require_once 'functions/lang.php'; 
require_once __DIR__ . '/../../db/conexion.php'; 
require_once __DIR__ . '/../../functions/functions_registro_user.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Variables de alerta
if (!isset($_SESSION['flash'])) {
    $_SESSION['flash'] = [];
}

// Procesar registro si envían POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $resultado = registrar_usuario($conn, $_POST, $_SESSION['usuario']['id'] ?? 0);

    if ($resultado['estado'] === 'error') {
        $_SESSION['flash']['error'] = $resultado['mensaje'];
    } else {
        $_SESSION['flash']['success'] = $resultado['mensaje'];
    }

    // Redirigir para evitar reenvío de formulario y mostrar alertas
    header("Location: index.php?page=components/registros/registro_user");
    exit();
}
?>

<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'es' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $translations['register_users'] ?> - SENA</title>
    <link rel="stylesheet" href="assets/css/registro_user.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div class="main-container">
    <div class="form-section">
        <div class="form-header">
            <img src="assets/img/back-arrow.png" alt="Regresar" class="back-arrow" onclick="goBack()">
        </div>
        
        <div class="form-content">
            <form id="form-registro" action="" method="POST">
                <div class="section-title"><?= $translations['register_users'] ?></div>
                <div class="section-subtitle"><?= $translations['all_fields_required'] ?></div>

                <div class="section-title" style="margin-top: 20px;"><?= $translations['users'] ?></div>

                <div class="form-group">
                    <label><?= $translations['user_name'] ?></label>
                    <input type="text" name="nombre" required placeholder="<?= $translations['user_name'] ?>"
                           pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" title="Solo se permiten letras y espacios"
                           oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '')">
                </div>

                <div class="form-group">
                    <label><?= $translations['user_lastname'] ?></label>
                    <input type="text" name="apellidos" required placeholder="<?= $translations['user_lastname'] ?>"
                           pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" title="Solo se permiten letras y espacios"
                           oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '')">
                </div>
                
                <div class="form-group">
                    <label><?= $translations['user_phone'] ?></label>
                    <input type="number" name="telefono" required placeholder="<?= $translations['user_phone'] ?>" min="1"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>

                <div class="form-group">
                    <label><?= $translations['document_type'] ?></label>
                    <select name="tipo_documento" required>
                        <option value="" disabled selected><?= $translations['select_doc_type'] ?></option>
                        <option value="TI">TI</option>
                        <option value="CC">CC</option>
                    </select>
                </div>

                <div class="form-group">
                    <label><?= $translations['document_number'] ?></label>
                    <input type="number" name="documento" required placeholder="<?= $translations['document_number'] ?>" min="1"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>

                <div class="section-title" style="margin-top: 30px;"><?= $translations['email'] ?></div>
                <div class="section-subtitle"><?= $translations['access_email'] ?></div>

                <div class="form-group">
                    <label>Correo</label>
                    <input type="email" name="email" required placeholder="Correo">
                </div>

                <div class="section-title" style="margin-top: 30px;">Contraseña</div>

                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" name="contrasena" id="contrasena" required placeholder="Ingrese su contraseña">
                </div>

                <div class="form-group">
                    <label><?= $translations['confirm_password'] ?></label>
                    <input type="password" name="confirmar_contrasena" id="confirmar_contrasena" required placeholder="<?= $translations['confirm_password'] ?>">
                </div>

                <button type="submit" class="register-btn">Registrar</button>
            </form>
        </div>
    </div>

    <div class="green-section">
        <h1 class="registro-title"><?= $translations['register_users'] ?></h1>
    </div>
</div>

<script>
// Validación de contraseñas en cliente
document.getElementById("form-registro").addEventListener("submit", function(e) {
    const pass = document.getElementById("contrasena").value;
    const confirm = document.getElementById("confirmar_contrasena").value;

    if (pass !== confirm) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Error en la contraseña',
            text: 'Las contraseñas no coinciden.',
            timer: 1500,
            showConfirmButton: false
        });
    }
});

// Alertas flash de PHP
<?php if (!empty($_SESSION['flash']['error'])): ?>
Swal.fire({
    icon: 'error',
    title: 'Error',
    html: '<?= nl2br($_SESSION['flash']['error']) ?>', 
    showConfirmButton: true
});
<?php unset($_SESSION['flash']['error']); endif; ?>

<?php if (!empty($_SESSION['flash']['success'])): ?>
Swal.fire({
    icon: 'success',
    title: '¡Éxito!',
    text: '<?= $_SESSION['flash']['success'] ?>',
    showConfirmButton: false,
    timer: 1500
}).then(() => {
    window.location.href = "index.php?page=components/usuarios/usuarios";
});
<?php unset($_SESSION['flash']['success']); endif; ?>
</script>

</body>
</html>
