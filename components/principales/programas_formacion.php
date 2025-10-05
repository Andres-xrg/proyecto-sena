<?php
require_once __DIR__ . '/../../db/conexion.php';
if (session_status() === PHP_SESSION_NONE) session_start();

// Idioma
$idioma = $_SESSION['lang'] ?? 'es';
$t = include __DIR__ . '/../../lang/' . $idioma . '.php';

$tipo = $_GET['tipo'] ?? '';
$estado_programa = $_GET['estado'] ?? '';
$es_admin = isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] === 'administrador';

// Construir condiciones SQL
$condiciones = [];
$params = [];
$tipos_param = '';

if (!empty($tipo) && in_array($tipo, ['tecnico', 'tecnologo'])) {
    $condiciones[] = "tipo_programa = ?";
    $params[] = $tipo;
    $tipos_param .= 's';
}
require_once __DIR__ . '/../../functions/autenticacion_login.php';

if (!$es_admin) {
    $condiciones[] = "estado = 'activo'";
} elseif (!empty($estado_programa) && in_array($estado_programa, ['activo', 'inactivo'])) {
    $condiciones[] = "estado = ?";
    $params[] = $estado_programa;
    $tipos_param .= 's';
}

$sql = "SELECT * FROM programas_formacion";
if ($condiciones) $sql .= " WHERE " . implode(" AND ", $condiciones);
$sql .= " ORDER BY nombre_programa ASC";

$stmt = $conn->prepare($sql);
if (!empty($params)) $stmt->bind_param($tipos_param, ...$params);
$stmt->execute();
$programas = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="<?= $idioma ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $t['training_programs'] ?? 'Programas de Formación' ?></title>
    <link rel="stylesheet" href="/proyecto-sena/assets/css/header.css">
    <link rel="stylesheet" href="/proyecto-sena/assets/css/footer.css">
    <link rel="stylesheet" href="/proyecto-sena/assets/css/programas_formacion.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Clase para inputs y selects uniformes */
        .input-style {
            width: 100%;
            padding: 0.75rem;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            color: #1f2937;
            font-size: 0.95rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.1s ease;
        }
        .input-style:focus {
            outline: none;
            border-color: #94a3b8;
            box-shadow: 0 0 0 3px rgba(148, 163, 184, 0.1);
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

<!-- Barra de filtros y buscador -->
<div class="filtro-barra">
    <form method="GET" action="index.php" style="display: flex; align-items: center;">
        <input type="hidden" name="page" value="components/principales/programas_formacion">

        <!-- Filtro por tipo -->
        <div class="dropdown-container">
            <div class="dropdown" id="dropdownFiltroTipo" onclick="toggleDropdown('tipo')">
                <span id="selectedOptionTipo">
                    <?= isset($_GET['tipo']) ? ($_GET['tipo'] === '' ? $t['filter_by_type'] : ucfirst($_GET['tipo'])) : $t['filter_by_type'] ?>
                </span>
                <span class="arrow">&#9662;</span>
            </div>
            <div class="dropdown-options" id="dropdownOptionsTipo">
                <div onclick="seleccionarFiltro('', 'tipo')"><?= $t['all'] ?? 'Todos' ?></div>
                <div onclick="seleccionarFiltro('tecnico', 'tipo')"><?= $t['technical'] ?? 'Técnico' ?></div>
                <div onclick="seleccionarFiltro('tecnologo', 'tipo')"><?= $t['technologist'] ?? 'Tecnólogo' ?></div>
            </div>
            <input type="hidden" name="tipo" id="tipoHidden" value="<?= htmlspecialchars($tipo) ?>">
        </div>

        <!-- Filtro por estado (solo admin) -->
        <?php if ($es_admin): ?>
        <div class="dropdown-container">
            <div class="dropdown" id="dropdownFiltroEstado" onclick="toggleDropdown('estado')">
                <span id="selectedOptionEstado">
                    <?= isset($_GET['estado']) ? ($_GET['estado'] === '' ? $t['program_status'] : ucfirst($_GET['estado'])) : $t['program_status'] ?>
                </span>
                <span class="arrow">&#9662;</span>
            </div>
            <div class="dropdown-options" id="dropdownOptionsEstado">
                <div onclick="seleccionarFiltro('', 'estado')"><?= $t['all'] ?? 'Todos' ?></div>
                <div onclick="seleccionarFiltro('activo', 'estado')"><?= $t['active'] ?? 'Activo' ?></div>
                <div onclick="seleccionarFiltro('inactivo', 'estado')"><?= $t['inactive'] ?? 'Inactivo' ?></div>
            </div>
            <input type="hidden" name="estado" id="estadoHidden" value="<?= htmlspecialchars($estado_programa) ?>">
        </div>
        <?php endif; ?>
    </form>

    <!-- Buscador -->
    <div class="search-box">
        <input type="text" placeholder="<?= $t['search_placeholder'] ?? 'Buscar...' ?>" id="searchInput">
    </div>
</div>

<!-- Menú flotante admin -->
<?php if ($es_admin): ?>
<div class="menu-fab">
    <button class="menu-toggle" onclick="toggleMenu()">☰</button>
    <div class="menu-options" id="menuOptions">
        <button onclick="abrirModalPrograma()"><?= $t['create_program'] ?? 'Crear Programa' ?></button>
        <button onclick="registrarFicha()"><?= $t['register_record'] ?? 'Registrar Ficha' ?></button>
        <button onclick="registrarInstructor()"><?= $t['register_instructor'] ?? 'Registrar Instructor' ?></button>
    </div>
</div>
<?php endif; ?>

<!-- Contenido principal -->
<main class="programs-main-content">

    <!-- SweetAlerts -->
    <?php if (isset($_GET['creado']) && $_GET['creado'] == 1): ?>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        Swal.fire({
            icon: "success",
            title: "<?= $t['program_created_success'] ?? 'Programa creado exitosamente.' ?>",
            showConfirmButton: false,
            timer: 2000
        });
        const url = new URL(window.location.href);
        url.searchParams.delete("creado");
        window.history.replaceState({}, document.title, url.toString());
    });
    </script>
    <?php endif; ?>

    <?php if (isset($_GET['actualizado'])): ?>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        <?php if ($_GET['actualizado'] == 1): ?>
            Swal.fire({
                icon: "success",
                title: "<?= $t['program_updated_success'] ?? 'Programa actualizado exitosamente.' ?>",
                showConfirmButton: false,
                timer: 2000
            });
        <?php else: ?>
            Swal.fire({
                icon: "error",
                title: "<?= $t['program_updated_error'] ?? 'Error al actualizar el programa.' ?>",
                showConfirmButton: true
            });
        <?php endif; ?>
        const url = new URL(window.location.href);
        url.searchParams.delete("actualizado");
        window.history.replaceState({}, document.title, url.toString());
    });
    </script>
    <?php endif; ?>

    <?php if (isset($_GET['habilitado']) && $_GET['habilitado'] == 1): ?>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        Swal.fire({
            icon: "success",
            title: "<?= $t['program_enabled_success'] ?? 'Programa habilitado exitosamente.' ?>",
            showConfirmButton: false,
            timer: 2000
        });
        const url = new URL(window.location.href);
        url.searchParams.delete("habilitado");
        window.history.replaceState({}, document.title, url.toString());
    });
    </script>
    <?php endif; ?>

    <?php if (isset($_GET['deshabilitado']) && $_GET['deshabilitado'] == 1): ?>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        Swal.fire({
            icon: "success",
            title: "<?= $t['program_disabled_success'] ?? 'Programa deshabilitado exitosamente.' ?>",
            showConfirmButton: false,
            timer: 2000
        });
        const url = new URL(window.location.href);
        url.searchParams.delete("deshabilitado");
        window.history.replaceState({}, document.title, url.toString());
    });
    </script>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        Swal.fire({
            icon: "error",
            title: "<?= $t['error_occurred'] ?? 'Ocurrió un error al procesar la solicitud.' ?>",
            showConfirmButton: true
        });
        const url = new URL(window.location.href);
        url.searchParams.delete("error");
        window.history.replaceState({}, document.title, url.toString());
    });
    </script>
    <?php endif; ?>

    <div class="programs-content-area">
        <div class="programs-grid">
            <?php while ($row = $programas->fetch_assoc()): ?>
                <div class="program-card">
                    <div class="card-header">
                        <div class="card-icon">
                            <?= htmlspecialchars(strtoupper(substr($row['nombre_programa'], 0, 1))) ?>
                        </div>
                        <div class="card-info">
                            <div class="card-title">
                                <a href="index.php?page=components/Fichas/listar_fichas&id_programa=<?= $row['Id_programa'] ?>" style="text-decoration: none; color: inherit;">
                                    <?= htmlspecialchars($row['nombre_programa']) ?>
                                </a>
                            </div>
                            <?php if ($es_admin): ?>
                            <div class="card-buttons">
                                <button class="btn editar-btn"
                                    onclick="abrirModalEditar('<?= $row['Id_programa'] ?>', '<?= htmlspecialchars(addslashes($row['nombre_programa'])) ?>', '<?= $row['tipo_programa'] ?>')"><?= $t['edit'] ?? 'Editar' ?></button>
                                <form method="POST" action="functions/functions_estado_programa.php" style="display:inline;">
                                    <input type="hidden" name="id_programa" value="<?= $row['Id_programa'] ?>">
                                    <input type="hidden" name="nuevo_estado" value="<?= $row['estado'] === 'activo' ? 'inactivo' : 'activo' ?>">
                                    <button type="submit" class="btn <?= $row['estado'] === 'activo' ? 'deshabilitar-btn' : 'habilitar-btn' ?>">
                                        <?= $row['estado'] === 'activo' ? ($t['disable'] ?? 'Deshabilitar') : ($t['enable'] ?? 'Habilitar') ?>
                                    </button>
                                </form>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</main>

<!-- Modal Crear Programa -->
<div id="modalPrograma" class="modal hidden">
    <div class="modal-content">
        <span class="close-btn" onclick="cerrarModalPrograma()">&times;</span>
        <h2><?= $t['register_training_program'] ?? 'Registrar Programa de Formación' ?></h2>
        <form action="/proyecto-sena/functions/functions_crear_programas.php" method="POST">
            <div class="form-group">
                <label><?= $t['program_name'] ?? 'Nombre del Programa:' ?></label>
                <input type="text" name="programa" class="input-style" required>
            </div>
            <div class="form-group">
                <label><?= $t['program_type'] ?? 'Tipo de Programa:' ?></label>
                <select name="tipo_programa" class="input-style" required>
                    <option value=""><?= $t['select_type'] ?? 'Seleccione tipo' ?></option>
                    <option value="tecnico"><?= $t['technical'] ?? 'Técnico' ?></option>
                    <option value="tecnologo"><?= $t['technologist'] ?? 'Tecnólogo' ?></option>
                </select>
            </div>
            <button type="submit" class="register-btn"><?= $t['save'] ?? 'Guardar' ?></button>
        </form>
    </div>
</div>

<!-- Modal Editar Programa -->
<div id="modalEditarPrograma" class="modal hidden">
    <div class="modal-content">
        <span class="close-btn" onclick="cerrarModalEditar()">&times;</span>
        <h2><?= $t['edit_training_program'] ?? 'Editar Programa de Formación' ?></h2>
        <form id="formEditarPrograma" method="POST" action="functions/functions_actualizar_programa.php">
            <input type="hidden" name="id_programa" id="editIdPrograma">
            <label><?= $t['program_name'] ?? 'Nombre del Programa:' ?></label>
            <input type="text" name="programa" id="editNombrePrograma" class="input-style" required>
            <label><?= $t['program_type'] ?? 'Tipo de Programa:' ?></label>
            <select name="tipo_programa" id="editTipoPrograma" class="input-style" required>
                <option value=""><?= $t['select_type'] ?? 'Seleccione tipo' ?></option>
                <option value="tecnico"><?= $t['technical'] ?? 'Técnico' ?></option>
                <option value="tecnologo"><?= $t['technologist'] ?? 'Tecnólogo' ?></option>
            </select>
            <button type="submit"><?= $t['save_changes'] ?? 'Guardar cambios' ?></button>
        </form>
    </div>
</div>

<script src="/proyecto-sena/assets/js/registros.js"></script>
<script src="/proyecto-sena/assets/js/programas_formacion.js"></script>

<script>
// Buscador
document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("searchInput");
    const cards = document.querySelectorAll(".program-card");

    searchInput.addEventListener("input", () => {
        const searchTerm = searchInput.value.toLowerCase();
        cards.forEach(card => {
            const nombrePrograma = card.querySelector(".card-title").textContent.toLowerCase();
            card.style.display = nombrePrograma.includes(searchTerm) ? "block" : "none";
        });
    });

    // Validación: nombre del programa solo letras
    const nombreInputs = document.querySelectorAll("input[name='programa']");
    nombreInputs.forEach(input => {
        input.addEventListener("input", () => {
            let val = input.value;
            if (/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/.test(val)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Formato inválido',
                    text: 'El nombre del programa solo puede contener letras.'
                });
                input.value = val.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, "");
            }
        });
    });
});
</script>

</body>
</html>
