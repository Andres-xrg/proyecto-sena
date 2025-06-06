<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/registro_aprendices.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <title>Registrar Aprendices - SENA</title>
</head>

<body>
    <!-- Main Content -->
    <main class="main-content">
        <div class="form-container">
            <h1 class="form-title">Registrar Aprendices</h1>
            
            <form method="post" action="functions/functions_registro_aprendiz .php">
                <!-- First Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" placeholder="Nombre del aprendiz" required>
                    </div>
                    <div class="form-group">
                        <label for="apellido">Apellido</label>
                        <input type="text" id="apellido" name="apellido" placeholder="Apellido del aprendiz" required>
                    </div>
                    <div class="form-group">
                        <label for="telefono">Numero de telefono</label>
                        <input type="tel" id="telefono" name="telefono" placeholder="Telefono del aprendiz" required>
                    </div>
                </div>

                <!-- Second Row -->
                <div class="form-row">
                    
                    <div class="form-group">
                        <label for="numeroDocumento">Numero de Documento</label>
                        <input type="text" id="numeroDocumento" name="numeroDocumento" placeholder="Numero de documento del aprendiz" required>
                    </div>
                    <div class="form-group">
                        <label for="tipoDocumento">Tipo Documento</label>
                        <select id="tipoDocumento" name="tipoDocumento" required>
                            <option value="">Selecciona el tipo de documento</option>
                            <option value="cedula-extranjera">Cedula Extranjera</option>
                            <option value="cedula">Cedula</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="correo">Correo Institucional</label>
                        <input type="email" id="correo" name="Email" placeholder="Correo institucional del aprendiz" required>
                    </div>
                </div>
                
                <!-- Third Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="ficha">Numero de ficha a la que entra</label>
                        <input type="number" id="ficha" name="ficha" placeholder="Numero de ficha a la que entra el aprendiz" required>
                    </div>
                    
                </div>

                
                <!-- Submit Button -->
                <button type="submit" class="register-btn">registrar</button>
            </form>
        </div>
    </main>
</body>
</html>