<?php
if (!ACCESO_PERMITIDO){
    header("Location: /proyecto-sena/components/principales/login.php");
}
require_once 'functions/lang.php'; 
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
</head>
<body>

<div class="main-container">
    <div class="form-section">
        <div class="form-header">
            <img src="assets/img/back-arrow.png" alt="Regresar" class="back-arrow" onclick="goBack()">
        </div>
        
        <div class="form-content">
            <form action="functions/functions_registro_user.php" method="POST">
                <div class="section-title"><?= $translations['register_users'] ?></div>
                <div class="section-subtitle"><?= $translations['all_fields_required'] ?></div>

                <div class="section-title" style="margin-top: 20px;"><?= $translations['users'] ?></div>

                <div class="form-group">
                    <label><?= $translations['user_name'] ?></label>
                    <input type="text" name="nombre" required placeholder="<?= $translations['user_name'] ?>">
                </div>

                <div class="form-group">
                    <label><?= $translations['user_lastname'] ?></label>
                    <input type="text" name="apellidos" required placeholder="<?= $translations['user_lastname'] ?>">
                </div>

                <div class="form-group">
                    <label><?= $translations['user_phone'] ?></label>
                    <input type="tel" name="telefono" required placeholder="<?= $translations['user_phone'] ?>">
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
                    <input type="text" name="documento" required placeholder="<?= $translations['document_number'] ?>">
                </div>

                <div class="section-title" style="margin-top: 30px;"><?= $translations['email'] ?></div>
                <div class="section-subtitle"><?= $translations['access_email'] ?></div>

                <div class="form-group">
                    <label><?= $translations['email'] ?></label>
                    <input type="email" name="email" required placeholder="<?= $translations['email'] ?>">
                </div>

                <div class="section-title" style="margin-top: 30px;">Contraseña</div>

                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" name="contrasena" required placeholder="Ingrese su contraseña">
                </div>

                <div class="form-group">
                    <label><?= $translations['confirm_password'] ?></label>
                    <input type="password" name="confirmar_contrasena" required placeholder="<?= $translations['confirm_password'] ?>">
                </div>

                <button type="submit" class="register-btn"><?= $translations['submit'] ?></button>
            </form>
        </div>
    </div>

    <div class="green-section">
        <h1 class="registro-title"><?= $translations['register_users'] ?></h1>
    </div>
</div>

<script src="assets/js/goBack.js"></script>

</body>
</html>