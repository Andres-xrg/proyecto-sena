<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/registro_instructor.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <title>Registrar Instructor - SENA</title>
</head>
<body>
    <main class="main-content">
        <div class="form-container">
            <h1 class="form-title">Registrar Instructor</h1>

            <form method="post" action="functions/functions_registro_instructor.php">
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" required placeholder="Ingrese el nombre">
                    </div>
                    <div class="form-group">
                        <label for="apellido">Apellido</label>
                        <input type="text" id="apellido" name="apellido" required placeholder="Ingrese el apellido">
                    </div>
                    <div class="form-group">
                        <label for="ficha">Ficha Instructor</label>
                        <input type="text" id="ficha" name="ficha" placeholder="Ingrese la ficha del instructor">
                        <div class="optional-note">(No es obligatorio)</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="tipoDocumento">Tipo Documento</label>
                        <select id="tipoDocumento" name="tipoDocumento" required>
                            <option value="">Selecciona el tipo de documento</option>
                            <option value="1">Cédula Extranjera</option>
                            <option value="2">Cédula</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="numeroDocumento">Número de Documento</label>
                        <input type="text" id="numeroDocumento" name="numeroDocumento" required placeholder="Ingrese el número de documento">
                    </div>
                    <div class="form-group">
                        <label for="instructor">Instructor</label>
                        <select id="instructor" name="instructor" required>
                            <option value="">Seleccione el tipo de instructor</option>
                            <option value="innovacional">Instructor Innovacional</option>
                            <option value="normal">Instructor Normal</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="telefono">Número de teléfono</label>
                        <input type="tel" id="telefono" name="telefono" required placeholder="Ingrese el número de teléfono">
                    </div>
                    <div class="form-group">
                        <label for="correo">Correo Institucional</label>
                        <input type="email" id="correo" name="Email" required placeholder="Ingrese el correo institucional">
                    </div>
                </div>

                <button type="submit" class="register-btn">Registrar</button>
            </form>
        </div>
    </main>
</body>
</html>
