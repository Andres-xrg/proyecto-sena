<?php
require_once __DIR__ . '/../../db/conexion.php';

$tipo = $_GET['tipo'] ?? '';

if ($tipo === 'tecnico' || $tipo === 'tecnologo') {
    $stmt = $conn->prepare("SELECT * FROM programas_formacion WHERE tipo_programa = ? ORDER BY nombre_programa ASC");
    $stmt->bind_param("s", $tipo);
    $stmt->execute();
    $programas = $stmt->get_result();
} else {
    $programas = $conn->query("SELECT * FROM programas_formacion ORDER BY nombre_programa ASC");
}
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
    <div class="filtro-barra" style="display: flex; align-items: center; gap: 1rem; position: fixed; top: 20px; left: 300px; z-index: 1000;">
        <form method="GET" action="/proyecto-sena/index.php" style="display: flex; align-items: center;">
            <input type="hidden" name="page" value="components/principales/programas_formacion">

            <div class="dropdown-container">
                <div class="dropdown" id="dropdownFiltro" onclick="toggleDropdown()">
                    <span id="selectedOption"><?= isset($_GET['tipo']) ? ucfirst($_GET['tipo']) : 'Filtrar por tipo' ?></span>
                    <span class="arrow">&#9662;</span>
                </div>
                <div class="dropdown-options" id="dropdownOptions">
                    <div onclick="seleccionarFiltro('Todos los programas')">Todos los programas</div>
                    <div onclick="seleccionarFiltro('tecnico')">Técnico</div>
                    <div onclick="seleccionarFiltro('tecnologo')">Tecnólogo</div>
                </div>
            </div>

            <input type="hidden" name="tipo" id="tipoHidden" value="<?= htmlspecialchars($_GET['tipo'] ?? '') ?>">
        </form>

        <div class="search-box">
            <input type="text" placeholder="Buscar..." id="searchInput">
        </div>
    </div>

    <main class="programs-main-content">
        <?php if (isset($_GET['creado']) && $_GET['creado'] == 1): ?>
            <div class="alert success"></div>
        <?php endif; ?>

        <div class="menu-fab">
            <button class="menu-toggle" onclick="toggleMenu()">☰</button>
            <div class="menu-options" id="menuOptions">
                <button onclick="abrirModalPrograma()">Crear Programa</button>
                <button onclick="registrarFicha()">Registrar Ficha</button>
                <button onclick="registrarInstructor()">Registrar Instructor</button>
                <button onclick="registrarAprendiz()">Registrar Aprendiz</button>
            </div>
        </div>

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
                                <div class="card-buttons">
                                    <button class="btn editar-btn" onclick="abrirModalEditar('<?= $row['Id_programa'] ?>', '<?= htmlspecialchars(addslashes($row['nombre_programa'])) ?>', '<?= $row['tipo_programa'] ?>')">Editar</button>
                                <form method="POST" action="functions/functions_estado_programa.php" style="display:inline;">
                                    <input type="hidden" name="id_programa" value="<?= $row['Id_programa'] ?>">
                                    <input type="hidden" name="nuevo_estado" value="<?= $row['estado'] === 'activo' ? 'inactivo' : 'activo' ?>">
                                    <button type="submit" class="btn <?= $row['estado'] === 'activo' ? 'deshabilitar-btn' : 'habilitar-btn' ?>">
                                        <?= $row['estado'] === 'activo' ? 'Deshabilitar' : 'Habilitar' ?>
                                    </button>
                                </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </main>

    <!-- Modal Editar Programa -->
    <div id="modalEditarPrograma" class="modal hidden">
        <div class="modal-content">
            <span class="close-btn" onclick="cerrarModalEditar()">&times;</span>
            <h2>Editar Programa de Formación</h2>
            <form id="formEditarPrograma" method="POST" action="/proyecto-sena/functions/functions_actualizar_programa.php">
                <input type="hidden" name="id_programa" id="editarIdPrograma">

                <div class="form-group">
                    <label for="editarNombrePrograma">Nombre del Programa:</label>
                    <input type="text" id="editarNombrePrograma" name="programa" required>
                </div>

                <div class="form-group">
                    <label for="editarTipoPrograma">Tipo de Programa:</label>
                    <select id="editarTipoPrograma" name="tipo_programa" required>
                        <option value="">Seleccione tipo</option>
                        <option value="tecnico">Técnico</option>
                        <option value="tecnologo">Tecnólogo</option>
                    </select>
                </div>

                <button type="submit" class="register-btn">Guardar cambios</button>
            </form>
        </div>
    </div>

    <!-- Modal Editar Programa -->
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
