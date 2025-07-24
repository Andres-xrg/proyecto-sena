<?php
require_once __DIR__ . '/../../db/conexion.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$tipo = $_GET['tipo'] ?? '';
$estado_programa = $_GET['estado'] ?? '';
$es_admin = isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] === 'administrador';

$condiciones = [];
$params = [];
$tipos_param = '';

if (!empty($tipo) && in_array($tipo, ['tecnico', 'tecnologo'])) {
    $condiciones[] = "tipo_programa = ?";
    $params[] = $tipo;
    $tipos_param .= 's';
}

if (!$es_admin) {
    $condiciones[] = "estado = 'activo'";
} elseif (!empty($estado_programa) && in_array($estado_programa, ['activo', 'inactivo'])) {
    $condiciones[] = "estado = ?";
    $params[] = $estado_programa;
    $tipos_param .= 's';
}

$sql = "SELECT * FROM programas_formacion";
if ($condiciones) {
    $sql .= " WHERE " . implode(" AND ", $condiciones);
}
$sql .= " ORDER BY nombre_programa ASC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($tipos_param, ...$params);
}
$stmt->execute();
$programas = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programas de Formación</title>
    <link rel="stylesheet" href="/proyecto-sena/assets/css/header.css">
    <link rel="stylesheet" href="/proyecto-sena/assets/css/footer.css">
    <link rel="stylesheet" href="/proyecto-sena/assets/css/programas_formacion.css">
</head>
<body>

<div class="filtro-barra">
    <form method="GET" action="index.php" style="display: flex; align-items: center;">
        <input type="hidden" name="page" value="components/principales/programas_formacion">

        <!-- Filtro Tipo -->
        <div class="dropdown-container">
            <div class="dropdown" id="dropdownFiltroTipo" onclick="toggleDropdown('tipo')">
                <span id="selectedOptionTipo">
                    <?= isset($_GET['tipo']) ? ($_GET['tipo'] === '' || $_GET['tipo'] === 'Todos' ? 'Filtrar por tipo' : ucfirst($_GET['tipo'])) : 'Filtrar por tipo' ?>
                </span>
                <span class="arrow">&#9662;</span>
            </div>
            <div class="dropdown-options" id="dropdownOptionsTipo">
                <div onclick="seleccionarFiltro('', 'tipo')">Todos</div>
                <div onclick="seleccionarFiltro('tecnico', 'tipo')">Técnico</div>
                <div onclick="seleccionarFiltro('tecnologo', 'tipo')">Tecnólogo</div>
            </div>
            <input type="hidden" name="tipo" id="tipoHidden" value="<?= htmlspecialchars($tipo) ?>">
        </div>

        <!-- Filtro Estado solo si es administrador -->
        <?php if ($es_admin): ?>
        <div class="dropdown-container">
            <div class="dropdown" id="dropdownFiltroEstado" onclick="toggleDropdown('estado')">
                <span id="selectedOptionEstado">
                    <?= isset($_GET['estado']) ? ($_GET['estado'] === '' || $_GET['estado'] === 'Todos' ? 'Estado programa' : ucfirst($_GET['estado'])) : 'Estado programa' ?>
                </span>
                <span class="arrow">&#9662;</span>
            </div>
            <div class="dropdown-options" id="dropdownOptionsEstado">
                <div onclick="seleccionarFiltro('', 'estado')">Todos</div>
                <div onclick="seleccionarFiltro('activo', 'estado')">Activo</div>
                <div onclick="seleccionarFiltro('inactivo', 'estado')">Inactivo</div>
            </div>
            <input type="hidden" name="estado" id="estadoHidden" value="<?= htmlspecialchars($estado_programa) ?>">
        </div>
        <?php endif; ?>
    </form>

    <div class="search-box">
        <input type="text" placeholder="Buscar..." id="searchInput">
    </div>
</div>

<?php if ($es_admin): ?>
<div class="menu-fab">
    <button class="menu-toggle" onclick="toggleMenu()">☰</button>
    <div class="menu-options" id="menuOptions">
        <button onclick="abrirModalPrograma()">Crear Programa</button>
        <button onclick="registrarFicha()">Registrar Ficha</button>
        <button onclick="registrarInstructor()">Registrar Instructor</button>
    </div>
</div>
<?php endif; ?>

<main class="programs-main-content">
    <?php if (isset($_GET['creado']) && $_GET['creado'] == 1): ?>
        <div class="alert success">Programa creado exitosamente.</div>
    <?php endif; ?>

    <div class="programs-content-area">
        <div class="programs-grid">
            <?php while ($row = $programas->fetch_assoc()): ?>
                <div class="program-card">
                    <div class="card-header">
                        <div class="card-icon"></div>
                        <div class="card-info">
                            <div class="card-title">
                                <a href="index.php?page=components/Fichas/listar_fichas&id_programa=<?= $row['Id_programa'] ?>" style="text-decoration: none; color: inherit;">
                                    <?= htmlspecialchars($row['nombre_programa']) ?>
                                </a>
                            </div>

                            <?php if ($es_admin): ?>
                            <div class="card-buttons">
                                <button class="btn editar-btn"
                                    onclick="abrirModalEditar('<?= $row['Id_programa'] ?>', '<?= htmlspecialchars(addslashes($row['nombre_programa'])) ?>', '<?= $row['tipo_programa'] ?>')">Editar</button>
                                <form method="POST" action="functions/functions_estado_programa.php" style="display:inline;">
                                    <input type="hidden" name="id_programa" value="<?= $row['Id_programa'] ?>">
                                    <input type="hidden" name="nuevo_estado" value="<?= $row['estado'] === 'activo' ? 'inactivo' : 'activo' ?>">
                                    <button type="submit" class="btn <?= $row['estado'] === 'activo' ? 'deshabilitar-btn' : 'habilitar-btn' ?>">
                                        <?= $row['estado'] === 'activo' ? 'Deshabilitar' : 'Habilitar' ?>
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

<!-- Modales -->
<div id="modalPrograma" class="modal hidden">
    <div class="modal-content">
        <span class="close-btn" onclick="cerrarModalPrograma()">&times;</span>
        <h2>Registrar Programa de Formación</h2>
        <form action="/proyecto-sena/functions/functions_crear_programas.php" method="POST">
            <div class="form-group">
                <label for="programa">Nombre del Programa:</label>
                <input type="text" id="programa" name="programa" required>
            </div>
            <div class="form-group">
                <label for="tipo_programa">Tipo de Programa:</label>
                <select id="tipo_programa" name="tipo_programa" required>
                    <option value="">Seleccione tipo</option>
                    <option value="tecnico">Técnico</option>
                    <option value="tecnologo">Tecnólogo</option>
                </select>
            </div>
            <button type="submit" class="register-btn">Guardar</button>
        </form>
    </div>
</div>

<div id="modalEditarPrograma" class="modal hidden">
    <div class="modal-content">
        <span class="close-btn" onclick="cerrarModalEditar()">&times;</span>
        <h2>Editar Programa de Formación</h2>
        <form id="formEditarPrograma" method="POST" action="functions/functions_actualizar_programa.php">
            <input type="hidden" name="id_programa" id="editIdPrograma">
            <label for="editNombrePrograma">Nombre del programa:</label>
            <input type="text" name="programa" id="editNombrePrograma" required>
            <label for="editTipoPrograma">Tipo de programa:</label>
            <input type="text" name="tipo_programa" id="editTipoPrograma" required>
            <button type="submit">Guardar cambios</button>
        </form>
    </div>
</div>

<script src="/proyecto-sena/assets/js/registros.js"></script>
<script src="/proyecto-sena/assets/js/programas_formacion.js"></script>

</body>
</html>
