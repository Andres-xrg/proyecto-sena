<?php
require_once 'db/conexion.php';
$instructores = $conn->query("SELECT Id_instructor, nombre, apellido FROM instructores ORDER BY nombre ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/registro_fichas.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <title>Registrar Ficha - SENA</title>
</head>
<body>
    <main class="contenido-principal">
        <div class="contenedor-formulario">
            <h1 class="titulo-formulario">Registrar Ficha</h1>

          <form action="index.php?page=functions/functions_registros_fichas" method="POST" enctype="multipart/form-data">
                <!-- Primera Fila -->
                <div class="fila-formulario">
                    <div class="grupo-formulario">
                        <label for="juicios">Importar juicios evaluativos</label>
                        <input type="file" id="juicios" name="juicios" accept=".xlsx,.xls">
                    </div>

                    <div class="grupo-formulario">
                        <label for="jefeGrupo">Jefe de grupo</label>
                        <select id="jefeGrupo" name="jefeGrupo" required>
                            <option value="">Selecciona un instructor</option>
                            <?php while ($inst = $instructores->fetch_assoc()): ?>
                                <option value="<?= $inst['Id_instructor'] ?>">
                                    <?= htmlspecialchars($inst['nombre'] . ' ' . $inst['apellido']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <!-- Segunda Fila -->
                <div class="fila-formulario">
                    <div class="grupo-formulario">
                        <label for="programa">Programa de formación</label>
                        <select id="programa" name="programa" required>
                            <option value="">Selecciona un programa</option>
                            <option value="Análisis y Desarrollo de Software">Tecnólogo en Análisis y Desarrollo de Software</option>
                            <option value="Técnico en Programación">Técnico en Programación</option>
                        </select>
                    </div>

                    <div class="grupo-formulario">
                        <label for="Jornada">Jornada</label>
                        <select id="Jornada" name="Jornada" required>
                            <option value="">Selecciona la jornada</option>
                            <option value="Diurna">Diurna</option>
                            <option value="Nocturna">Nocturna</option>
                            <option value="Mixta">Mixta</option>
                        </select>
                    </div>
                </div>

                <!-- Tercera Fila -->
                <div class="fila-formulario">
                    <div class="grupo-formulario">
                        <label for="numero_ficha">Número de ficha</label>
                        <input type="number" id="numero_ficha" name="numero_ficha" placeholder="Ej: 2546889" required>
                    </div>

                    <div class="grupo-formulario">
                        <label for="horas_totales">Horas Totales</label>
                        <input type="number" id="horas_totales" name="horas_totales" placeholder="Ej: 2200" required>
                    </div>
                </div>

                <!-- Botón -->
                <button type="submit" class="btn-registrar">Registrar</button>
            </form>
        </div>
    </main>
</body>
</html>
