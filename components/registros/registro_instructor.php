<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/registro_instructor.css">
    <link rel="stylesheet" href="../../assets/css/header.css">
    <link rel="stylesheet" href="../../assets/css/footer.css">
    <title>Registrar Instructor - SENA</title>
</head>
<?php include '../../includes/header-secundario.php'; ?>
<body>
    <!-- Main Content -->
    <main class="main-content">
        <div class="form-container">
            <h1 class="form-title">Registrar Instructor</h1>
            
            <form>
                <!-- First Row -->
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

                <!-- Second Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="tipoDocumento">Tipo Documento</label>
                        <select id="tipoDocumento" name="tipoDocumento" required>
                            <option value="">Selecciona el tipo de documento</option>
                            <option value="cedula-extranjera">Cedula Extranjera</option>
                            <option value="cedula">Cedula</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="numeroDocumento">Numero de Documento</label>
                        <input type="text" id="numeroDocumento" name="numeroDocumento" required placeholder="Ingrese el número de documento">
                    </div>
                    <div class="form-group">
                        <label for="instructor">Instructor</label>
                        <select id="instructor" name="instructor" required>
                            <option value="">Seleccione el tipo de instructor</option>
                            <option value="instructor-innovacional">Instructor Innovacional</option>
                            <option value="instructor-normal">Instructor Normal</option>
                        </select>
                    </div>
                </div>

                <!-- Third Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="telefono">Numero de telefono</label>
                        <input type="tel" id="telefono" name="telefono" required placeholder="Ingrese el número de teléfono">
                    </div>
                    <div class="form-group">
                        <label for="correo">Correo Institucional</label>
                        <input type="email" id="correo" name="correo" required placeholder="Ingrese el correo institucional">
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="register-btn">registrar</button>
            </form>
        </div>
    </main>
</body>
<?php include '../../includes/footer.php'; ?>
</html>