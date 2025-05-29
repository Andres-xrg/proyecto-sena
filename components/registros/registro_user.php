<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - SENA</title>
    <link rel="stylesheet" href="../../assets/css/registro_user.css">
    <link rel="stylesheet" href="../../assets/css/header.css">
    <link rel="stylesheet" href="../../assets/css/footer.css">
</head>
<?php include '../../includes/header.php'; ?>
<body>

    <div class="main-container">
        <div class="form-section">
            <div class="form-header">
                <img src="../../assets/img/back-arrow.png" alt="Regresar" class="back-arrow" onclick="goBack()">
            </div>

            <div class="section-title">Información</div>
            <div class="section-subtitle">Todos los campos deben ser obligatorios</div>

            <div class="section-title" style="margin-top: 20px;">Usuario</div>
            
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" placeholder="Ingrese sus nombres">
            </div>

            <div class="form-group">
                <label>Apellidos</label>
                <input type="text" placeholder="Ingrese sus apellidos">
            </div>

            <div class="form-group">
                <label>N° teléfono</label>
                <input type="tel" placeholder="Ingrese su N° teléfono">
            </div>

            <div class="form-group">
                <label>Tipo Documento</label>
                <select>
                    <option value="" disabled selected>Seleccione su tipo de documento</option>
                    <option value="TI">TI</option>
                    <option value="CC">CC</option>
                </select>
            </div>

            <div class="form-group">
                <label>N° Documento</label>
                <input type="text" placeholder="Ingrese su N° de documento">
            </div>

            <div class="section-title" style="margin-top: 30px;">Correo</div>
            <div class="section-subtitle">Para realizar el acceso, su Email será su usuario</div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" placeholder="Ingrese su correo">
            </div>

            <div class="section-title" style="margin-top: 30px;">Contraseña</div>

            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" placeholder="Ingrese su contraseña">
            </div>

            <div class="form-group">
                <label>Confirmar contraseña</label>
                <input type="password" placeholder="Confirme su nueva contraseña">
            </div>

            <button class="register-btn">Registrar</button>
        </div>

        <div class="green-section">
            <h1 class="registro-title">Registro</h1>
        </div>
    </div>

</body>
<script src="../../assets/js/goBack.js"></script>
    <?php include '../../includes/footer.php'; ?>
</html>