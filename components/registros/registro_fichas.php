<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/registro_fichas.css">
    <link rel="stylesheet" href="../../assets/css/header.css">
    <link rel="stylesheet" href="../../assets/css/footer.css">
    <title>Registrar Ficha - SENA</title>
</head>
<?php include '../../includes/header-secundario.php'; ?>
<body>
    <!-- Contenido Principal -->
    <main class="contenido-principal">
        <div class="contenedor-formulario">
            <h1 class="titulo-formulario">Registrar Ficha</h1>
            
            <form>
                <!-- Primera Fila -->
                <div class="fila-formulario">
                    <div class="grupo-formulario">
                        <label for="juicios">Impotar juicios evaluativos</label>
                        <input type="file" id="juicios" name="juicios" required>
                    </div>

                    <div class="grupo-formulario">
                        <label for="jefeGrupo">Jefe de grupo</label>
                        <input type="text" id="jefeGrupo" name="jefeGrupo" placeholder="Ingrese el nombre del jefe de grupo" required>
                    </div>
                </div>

                <!-- Segunda Fila -->
                <div class="fila-formulario">
                    <div class="grupo-formulario">
                        <label for="programa">Programa de formacion</label>
                        <select id="programa" name="programa" required>
                            <option value="">Programa de formacion</option>
                            <option value="analisis-y-Desarrollo-de-Software">Tecnologo de Analisis y Desarrollo de Software</option>
                            <option value="tecnico-en-programacion">Tecnico en programación</option>
                        </select>
                    </div>

                    <div class="grupo-formulario">
                        <label for="Jornada">Jornada</label>
                        <select id="Jornada" name="Jornada" required>
                            <option value="">Jornada</option>
                            <option value="diurna">Diurna</option>
                            <option value="nocturna">Nocturna</option>
                            <option value="mixta">Mixta</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn-registrar">registrar</button>
            </form>
        </div>
    </main>
</body>
<?php include '../../includes/footer.php'; ?>
</html>