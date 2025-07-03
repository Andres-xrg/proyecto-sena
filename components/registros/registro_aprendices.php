<?php
if (!ACCESO_PERMITIDO){
    header("Location: /proyecto-sena/components/principales/login.php");
}
require_once 'db/conexion.php';

// Obtener todas las fichas registradas
$fichas = [];
$sql = "SELECT Id_ficha, Numero_ficha FROM fichas ORDER BY Numero_ficha ASC";
$resultado = $conn->query($sql);
while ($row = $resultado->fetch_assoc()) {
    $fichas[] = $row;
}

// Idioma traducido si ya lo estás usando
// require_once 'translations/es.php'; // o en.php si tienes multiidioma
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/registro_aprendices.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <title><?= $translations['register_apprentices'] ?> - SENA</title>
</head>

<body>
    <!-- Main Content -->
    <main class="main-content">
        <div class="form-container">
            <h1 class="form-title"><?= $translations['register_apprentices'] ?></h1>
            
            <form method="post" action="functions/functions_registro_aprendiz.php">
                <!-- First Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre"><?= $translations['name'] ?></label>
                        <input type="text" id="nombre" name="nombre" placeholder="<?= $translations['user_name'] ?> del aprendiz" required>
                    </div>
                    <div class="form-group">
                        <label for="apellido"><?= $translations['lastname'] ?></label>
                        <input type="text" id="apellido" name="apellido" placeholder="<?= $translations['user_lastname'] ?> del aprendiz" required>
                    </div>
                    <div class="form-group">
                        <label for="telefono"><?= $translations['phone'] ?></label>
                        <input type="tel" id="telefono" name="telefono" placeholder="<?= $translations['user_phone'] ?> del aprendiz" required>
                    </div>
                </div>

                <!-- Second Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="numeroDocumento"><?= $translations['document_number'] ?></label>
                        <input type="text" id="numeroDocumento" name="numeroDocumento" placeholder="<?= $translations['document_number'] ?> del aprendiz" required>
                    </div>
                    <div class="form-group">
                        <label for="tipoDocumento"><?= $translations['document_type'] ?></label>
                        <select id="tipoDocumento" name="tipoDocumento" required>
                            <option value=""><?= $translations['select_doc_type'] ?></option>
                            <option value="CC">C.C</option>
                            <option value="CE">C.E</option>
                            <option value="TI">T.I</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="correo"><?= $translations['email'] ?></label>
                        <input type="email" id="correo" name="Email" placeholder="<?= $translations['access_email'] ?> del aprendiz" required>
                    </div>
                </div>
                
                <!-- Ficha Row Actualizado -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="ficha">Número de Ficha</label>
                        <select id="ficha" name="ficha" required>
                            <option value="">Seleccione una ficha</option>
                            <?php foreach ($fichas as $ficha): ?>
                                <option value="<?= $ficha['Id_ficha'] ?>">
                                    <?= htmlspecialchars($ficha['Numero_ficha']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Mensajes -->
                <?php if (isset($_GET['msg'])): ?>
                    <div style="color: red; font-weight: bold; margin: 10px 0;">
                        <?php
                            switch ($_GET['msg']) {
                                case 'campos_vacios':
                                    echo '⚠️ ' . $translations['all_fields_required'];
                                    break;
                                case 'ficha_no_encontrada':
                                    echo '❌ La ficha ingresada no existe.';
                                    break;
                                case 'error_aprendiz':
                                    echo '❌ Error al registrar el aprendiz.';
                                    break;
                                case 'registro_exitoso':
                                    echo '✅ Aprendiz registrado correctamente.';
                                    break;
                            }
                        ?>
                    </div>
                <?php endif; ?>

                <!-- Submit -->
                <button type="submit" class="register-btn"><?= $translations['submit'] ?></button>
            </form>
        </div>
    </main>
</body>
</html>
