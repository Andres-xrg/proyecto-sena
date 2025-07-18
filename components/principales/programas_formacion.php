<?php
require_once __DIR__ . '/../../db/conexion.php';

// Capturar tipo si viene por GET
$tipo = $_GET['tipo'] ?? '';

// Consulta de programas con filtro si aplica
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
    <!-- Barra superior con filtro y búsqueda -->
    <div class="filtro-barra" style="display: flex; align-items: center; gap: 1rem; position: fixed; top: 20px; left: 300px; z-index: 1000;">
        <form method="GET" action="/proyecto-sena/index.php" style="display: flex; align-items: center;">
            <input type="hidden" name="page" value="components/principales/programas_formacion">

            <div class="dropdown-container">
                <div class="dropdown" id="dropdownFiltro" onclick="toggleDropdown()">
                    <span id="selectedOption"><?= isset($_GET['tipo']) ? ucfirst($_GET['tipo']) : 'Filtrar por tipo' ?></span>
                    <span class="arrow">&#9662;</span>
                </div>
                <div class="dropdown-options" id="dropdownOptions">
                    <div onclick="seleccionarFiltro('')">Todos los programas</div>
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

        <!-- Menú hamburguesa -->
        <div class="menu-fab">
            <button class="menu-toggle" onclick="toggleMenu()">☰</button>
            <div class="menu-options" id="menuOptions">
                <button onclick="abrirModalPrograma()">Crear Programa</button>
                <button onclick="registrarFicha()">Registrar Ficha</button>
                <button onclick="registrarInstructor()">Registrar Instructor</button>
                <button onclick="registrarAprendiz()">Registrar Aprendiz</button>
            </div>
        </div>

        <!-- Programas de formación -->
        <div class="programs-content-area">
            <div class="programs-grid">
                <?php while ($row = $programas->fetch_assoc()): ?>
                    <?php $Id_programa = urlencode($row['Id_programa']); ?>
                    <a href="index.php?page=components/Fichas/listar_fichas&id_programa=<?= $row['Id_programa'] ?>"class="program-card-link" style="text-decoration: none; color: inherit;">
                        <div class="program-card">
                            <div class="card-header">
                                <div class="card-icon"></div>
                                <div>
                                    <div class="card-title"><?= htmlspecialchars($row['nombre_programa']) ?></div>
                                    <div class="card-subtitle"><?= htmlspecialchars($row['descripcion']) ?></div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>
        </div>
    </main>

    <!-- Modal Crear Programa -->
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

                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" rows="3" maxlength="200"
                              placeholder="Determine si es oferta abierta o cerrada" required></textarea>
                </div>

                <button type="submit" class="register-btn">Guardar</button>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="/proyecto-sena/assets/js/registros.js"></script>
    <script src="/proyecto-sena/assets/js/programas_formacion.js"></script>
</body>
</html>
