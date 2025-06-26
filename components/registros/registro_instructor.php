<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/registro_instructor.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <title><?= $translations['register_instructor'] ?> - SENA</title>
</head>
<body>
    <main class="main-content">
        <div class="form-container">
            <h1 class="form-title"><?= $translations['register_instructor'] ?></h1>

            <form method="post" action="functions/functions_registro_instructor.php">
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre"><?= $translations['name'] ?></label>
                        <input type="text" id="nombre" name="nombre" required placeholder="<?= $translations['user_name'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="apellido"><?= $translations['lastname'] ?></label>
                        <input type="text" id="apellido" name="apellido" required placeholder="<?= $translations['user_lastname'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="ficha"><?= $translations['ficha_technologist'] ?></label>
                        <input type="text" id="ficha" name="ficha" placeholder="<?= $translations['ficha_number'] ?>">
                        <div class="optional-note">(<?= $translations['all_fields_required'] ?>)</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="tipoDocumento"><?= $translations['document_type'] ?></label>
                        <select id="tipoDocumento" name="tipoDocumento" required>
                            <option value=""><?= $translations['select_doc_type'] ?></option>
                            <option value="1">Cédula Extranjera</option>
                            <option value="2">Cédula</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="numeroDocumento"><?= $translations['document_number'] ?></label>
                        <input type="text" id="numeroDocumento" name="numeroDocumento" required placeholder="<?= $translations['document_number'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="instructor"><?= $translations['instructors'] ?></label>
                        <select id="instructor" name="instructor" required>
                            <option value=""><?= $translations['select_doc_type'] ?></option>
                            <option value="innovacional">Instructor Innovacional</option>
                            <option value="normal">Instructor Normal</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="telefono"><?= $translations['phone'] ?></label>
                        <input type="tel" id="telefono" name="telefono" required placeholder="<?= $translations['user_phone'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="correo"><?= $translations['email'] ?></label>
                        <input type="email" id="correo" name="Email" required placeholder="<?= $translations['access_email'] ?>">
                    </div>
                </div>

                <button type="submit" class="register-btn"><?= $translations['submit'] ?></button>
            </form>
        </div>
    </main>
</body>
</html>
