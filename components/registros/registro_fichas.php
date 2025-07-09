<?php
if (!ACCESO_PERMITIDO){
    header("Location: proyecto-sena/components/principales/login.php");
}
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
            <h1 class="titulo-formulario"><?= $translations['register_ficha'] ?></h1>

            <form action="index.php?page=functions/functions_registros_fichas" method="POST" enctype="multipart/form-data">
                <!-- Primera Fila -->
                <div class="fila-formulario">
                    <div class="grupo-formulario">
                        <label for="juicios"><?= $translations['import_judgments'] ?></label>
                        <input type="file" id="juicios" name="juicios" accept=".xlsx,.xls">
                    </div>

                    <div class="grupo-formulario">
                        <label for="jefeGrupo"><?= $translations['group_leader'] ?></label>
                        <select id="jefeGrupo" name="jefeGrupo" required>
                            <option value=""><?= $translations['Select_the_group_leader'] ?></option>
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
                        <label for="programa"><?= $translations['training_programs'] ?></label>
                        <select id="programa" name="programa" required>
                            <option value=""><?= $translations['select_doc_type'] ?></option>
                            <option value="Análisis y Desarrollo de Software"><?= $translations['technologist'] . ' ' . $translations['software_dev'] ?></option>
                            <option value="Técnico en Programación"><?= $translations['technician'] . ' ' . $translations['software_programming'] ?></option>
                        </select>
                    </div>

                    <div class="grupo-formulario">
                        <label for="Jornada"><?= $translations['document_type'] ?></label>
                        <select id="Jornada" name="Jornada" required>
                            <option value=""><?= $translations['select_doc_type'] ?></option>
                            <option value="Diurna"><?= $translations['daytime'] ?></option>
                            <option value="Nocturna"><?= $translations['nighttime'] ?></option>
                            <option value="Mixta"><?= $translations['mixed'] ?></option>
                        </select>
                    </div>
                </div>

                <!-- Tercera Fila -->
                <div class="fila-formulario">
                    <div class="grupo-formulario">
                        <label for="numero_ficha"><?= $translations['ficha_number'] ?></label>
                        <input type="number" id="numero_ficha" name="numero_ficha" placeholder="Ej: 2546889" required>
                    </div>
                </div>

                <!-- Botón -->
                <button type="submit" class="btn-registrar"><?= $translations['submit'] ?></button>
            </form>
        </div>
    </main>
</body>
</html>
