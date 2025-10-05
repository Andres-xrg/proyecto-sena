<?php
if (!defined('ACCESO_PERMITIDO')) {
    header("Location: proyecto-sena/components/principales/login.php");
    exit;
}

require_once 'db/conexion.php';

// Verificamos la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

try {
    $instructores = $conn->query("SELECT Id_instructor, nombre, apellido FROM instructores ORDER BY nombre ASC");
    $programas = $conn->query("SELECT id_programa, nombre_programa FROM programas_formacion ORDER BY nombre_programa ASC");

    if (!$instructores || !$programas) {
        throw new Exception("Error al obtener datos de la base de datos.");
    }
} catch (Exception $e) {
    die("Ocurrió un error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Ficha - SENA</title>
    <link rel="stylesheet" href="assets/css/registro_fichas.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<main class="contenido-principal">
    <div class="contenedor-formulario">
        <h1 class="titulo-formulario"><?= $translations['register_ficha'] ?? 'Registrar Ficha' ?></h1>

        <form id="formFicha" action="index.php?page=functions/functions_registros_fichas" method="POST" enctype="multipart/form-data">
            <!-- Primera Fila -->
            <div class="fila-formulario">
                <div class="grupo-formulario">
                    <label for="juicios"><?= $translations['import_judgments'] ?? 'Importar Juicios Evaluativos' ?></label>
                    <input type="file" id="juicios" name="juicios" accept=".xlsx,.xls" required>
                </div>

                <div class="grupo-formulario">
                    <label for="jefeGrupo"><?= $translations['group_leader'] ?? 'Jefe de Grupo' ?></label>
                    <select id="jefeGrupo" name="jefeGrupo" required>
                        <option value=""><?= $translations['Select_the_group_leader'] ?? 'Seleccione el jefe de grupo' ?></option>
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
                        <option value="">Seleccione programa de formación</option>
                        <?php while ($prog = $programas->fetch_assoc()): ?>
                            <option value="<?= $prog['id_programa'] ?>">
                                <?= htmlspecialchars($prog['nombre_programa']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="grupo-formulario">
                    <label for="Jornada">Jornada</label>
                    <select id="Jornada" name="Jornada" required>
                        <option value="">Seleccione su jornada</option>
                        <option value="Diurna">Diurna</option>
                        <option value="Nocturna">Nocturna</option>
                        <option value="Mixta">Mixta</option>
                    </select>
                </div>
            </div>

            <!-- Tercera Fila -->
            <div class="fila-formulario">
                <div class="grupo-formulario">
                    <label for="numero_ficha"><?= $translations['ficha_number'] ?? 'Número de ficha' ?></label>
                    <input type="number" id="numero_ficha" name="numero_ficha" placeholder="Ej: 2546889" required>
                </div>

                <div class="grupo-formulario">
                    <label for="tipo_oferta">Tipo de Oferta</label>
                    <select id="tipo_oferta" name="tipo_oferta" required>
                        <option value="">Seleccione tipo de oferta</option>
                        <option value="Abierta">Abierta</option>
                        <option value="Cerrada">Cerrada</option>
                    </select>
                </div>
            </div>

            <!-- Botón -->
            <button type="submit" class="btn-registrar"><?= $translations['submit'] ?? 'Registrar' ?></button>
        </form>
    </div>
</main>

<!-- FUNCION GLOBAL PARA ALERTAS -->
<script>
function showAlert(icon, title, text) {
    Swal.fire({
        icon: icon,
        title: title,
        text: text,
        showConfirmButton: false,   
        timer: 1500,                // Se cierra en 1.5s
        timerProgressBar: true
    }).then(() => {
        const url = new URL(window.location.href);
        url.searchParams.delete('success');
        url.searchParams.delete('error');

        // Si ya no quedan parámetros, limpia todo el query string
        const newUrl = url.searchParams.toString() ? url.pathname + '?' + url.searchParams.toString() : url.pathname;
        window.history.replaceState({}, document.title, newUrl);
    });
}
</script>

<!-- ALERTAS SWEETALERT -->
<?php if (isset($_GET['success']) && $_GET['success'] === 'ficha-creada'): ?>
<script>
showAlert('success', '¡Ficha creada correctamente!', 'La ficha se ha registrado exitosamente.');
</script>
<?php endif; ?>

<?php if (isset($_GET['error']) && $_GET['error'] === 'ficha-repetida'): ?>
<script>
showAlert('error', 'Ficha duplicada', 'El número de ficha ya existe en el sistema.');
</script>
<?php endif; ?>

<?php if (isset($_GET['error']) && $_GET['error'] === 'excel'): ?>
<script>
showAlert('error', 'Error con el archivo Excel', 'No se pudo procesar el archivo. Verifique el formato.');
</script>
<?php endif; ?>

<?php if (isset($_GET['error']) && $_GET['error'] === 'insertar-ficha'): ?>
<script>
showAlert('error', 'Error al registrar ficha', 'Ocurrió un problema al guardar la ficha en la base de datos.');
</script>
<?php endif; ?>

<!-- VALIDACIÓN Y LOADER -->
<script>
document.getElementById("formFicha").addEventListener("submit", function(e) {
    const numeroFicha = document.getElementById("numero_ficha").value;

    // Validar número negativo o cero
    if (numeroFicha <= 0) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Número inválido',
            text: 'El número de ficha no puede ser negativo ni cero.',
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true
        });
        return;
    }

    // Loader mientras procesa
    Swal.fire({
        title: 'Registrando ficha...',
        text: 'Por favor espera mientras procesamos la información.',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
});
</script>

</body>
</html>
