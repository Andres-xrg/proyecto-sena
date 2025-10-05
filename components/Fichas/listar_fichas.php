<?php
if (session_status() === PHP_SESSION_NONE) session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Manejo de idioma
$idioma = $_SESSION['lang'] ?? 'es';
$lang = include __DIR__ . '/../../lang/' . $idioma . '.php';

require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../functions/autenticacion_login.php';

// Mensaje de ficha creada
if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'creada') {
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
      Swal.fire({
        icon: 'success',
        title: '" . htmlspecialchars($lang['ficha_creada'] ?? 'Ficha creada') . "',
        showConfirmButton: false,
        timer: 2000
      });
    </script>";
}

// ⚡ Mostrar alerta de cambio de estado (una sola vez)
if (isset($_SESSION['alert_ficha'])) {
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
      Swal.fire({
        icon: 'success',
        title: '" . addslashes($_SESSION['alert_ficha']) . "',
        showConfirmButton: false,
        timer: 2000
      });
    </script>
    ";
    unset($_SESSION['alert_ficha']); // Se elimina para que no vuelva a salir al refrescar
}

$id_programa = $_GET['id_programa'] ?? null;
$titulo = $lang['fichas_titulo'] ?? 'Fichas del Programa';

// Obtener nombre del programa si existe
if ($id_programa && is_numeric($id_programa)) {
    $stmt = $conn->prepare("SELECT nombre_programa FROM programas_formacion WHERE Id_programa = ?");
    $stmt->bind_param("i", $id_programa);
    $stmt->execute();
    $resultado = $stmt->get_result()->fetch_assoc();
    $titulo = $resultado ? $resultado['nombre_programa'] : ($lang['fichas_no_encontrado'] ?? 'Programa no encontrado');
}

// Filtros
$estado_filtro = strtolower($_GET['estado'] ?? '');
$jornada_filtro = $_GET['jornada'] ?? '';
$tipo_oferta_filtro = $_GET['tipo_oferta'] ?? '';
$is_admin = isset($_SESSION['usuario']) && strtolower($_SESSION['usuario']['rol']) === 'administrador';
?>

<!DOCTYPE html>
<html lang="<?= $idioma ?>">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($lang['files_tecnologo_tecnico'] ?? 'Fichas') ?></title>
  <link rel="stylesheet" href="assets/css/listar_fichas.css">
  <link rel="stylesheet" href="assets/css/header.css">
  <link rel="stylesheet" href="assets/css/footer.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container">
  <div class="titulo">
    <h1 class="title"><?= htmlspecialchars($titulo) ?></h1>
  </div>
</div>

<div class="controls">
  <form method="GET" action="index.php" style="display: flex; gap: 1rem; align-items: center;">
    <input type="hidden" name="page" value="components/Fichas/listar_fichas">
    <input type="hidden" name="id_programa" value="<?= htmlspecialchars($id_programa) ?>">

    <!-- Buscador -->
    <div class="search-box">
      <input type="text" placeholder="<?= $lang['buscar'] ?? 'Buscar...' ?>" id="searchInput">
    </div>

    <!-- Filtro jornada -->
    <div class="dropdown-container">
      <div class="dropdown-wrapper">
        <div class="dropdown" onclick="toggleDropdown()">
          <span id="selectedJornada"><?= htmlspecialchars($jornada_filtro ?: ($lang['seleccionar_jornada'] ?? 'Jornada')) ?></span>
          <span class="arrow">▼</span>
        </div>
        <div class="dropdown-options" id="dropdownOptions">
          <div class="option" onclick="seleccionarJornada('todos')"><?= $lang['todos'] ?? 'Todos' ?></div>
          <div class="option" onclick="seleccionarJornada('Diurna')"><?= $lang['diurna'] ?? 'Diurna' ?></div>
          <div class="option" onclick="seleccionarJornada('Mixta')"><?= $lang['mixta'] ?? 'Mixta' ?></div>
          <div class="option" onclick="seleccionarJornada('Nocturna')"><?= $lang['nocturna'] ?? 'Nocturna' ?></div>
        </div>
      </div>
      <input type="hidden" name="jornada" id="jornadaHidden" value="<?= htmlspecialchars($jornada_filtro) ?>">
    </div>

    <!-- Filtro tipo de oferta -->
    <div class="dropdown-container">
      <div class="dropdown-wrapper">
        <div class="dropdown" onclick="toggleDropdownTipoOferta()">
          <span id="selectedTipoOferta"><?= htmlspecialchars($tipo_oferta_filtro ?: ($lang['tipo_oferta'] ?? 'Tipo de oferta')) ?></span>
          <span class="arrow">▼</span>
        </div>
        <div class="dropdown-options" id="dropdownTipoOfertaOptions">
          <div class="option" onclick="seleccionarTipoOferta('todos')"><?= $lang['todos'] ?? 'Todos' ?></div>
          <div class="option" onclick="seleccionarTipoOferta('Abierta')"><?= $lang['abierta'] ?? 'Abierta' ?></div>
          <div class="option" onclick="seleccionarTipoOferta('Cerrada')"><?= $lang['cerrada'] ?? 'Cerrada' ?></div>
        </div>
      </div>
      <input type="hidden" name="tipo_oferta" id="tipoOfertaHidden" value="<?= htmlspecialchars($tipo_oferta_filtro) ?>">
    </div>

    <!-- Filtro estado (solo admin) -->
    <?php if ($is_admin): ?>
      <div class="dropdown-container">
        <div class="dropdown-wrapper">
          <div class="dropdown" onclick="toggleDropdownEstado()">
            <span id="selectedEstado"><?= $estado_filtro ? $lang[$estado_filtro] ?? ucfirst($estado_filtro) : ($lang['filtrar_estado'] ?? 'Estado') ?></span>
            <span class="arrow">▼</span>
          </div>
          <div class="dropdown-options" id="dropdownEstadoOptions">
            <div class="option" onclick="seleccionarEstado('todos')"><?= $lang['todos'] ?? 'Todos' ?></div>
            <div class="option" onclick="seleccionarEstado('activo')"><?= $lang['activo'] ?? 'Activo' ?></div>
            <div class="option" onclick="seleccionarEstado('inactivo')"><?= $lang['inactivo'] ?? 'Inactivo' ?></div>
          </div>
        </div>
        <input type="hidden" name="estado" id="estadoHidden" value="<?= htmlspecialchars($estado_filtro) ?>">
      </div>
    <?php endif; ?>
  </form>
</div>

<?php
// Consulta de fichas
$sql = "SELECT f.*, i.nombre AS jefe_nombre, i.apellido AS jefe_apellido, p.nombre_programa
        FROM fichas f
        LEFT JOIN instructores i ON f.Jefe_grupo = i.Id_instructor
        LEFT JOIN programas_formacion p ON f.Id_programa = p.Id_programa
        WHERE f.Id_programa = ?";

$params = [$id_programa];
$types = "i";

if (!$is_admin) {
    $sql .= " AND f.Estado_ficha = ?";
    $params[] = 'Activo';
    $types .= "s";
} elseif ($estado_filtro && $estado_filtro !== 'todos') {
    $sql .= " AND f.Estado_ficha = ?";
    $params[] = $estado_filtro;
    $types .= "s";
}

if (!empty($jornada_filtro) && strtolower($jornada_filtro) !== 'todos') {
    $sql .= " AND f.Jornada = ?";
    $params[] = $jornada_filtro;
    $types .= "s";
}

if (!empty($tipo_oferta_filtro) && strtolower($tipo_oferta_filtro) !== 'todos') {
    $sql .= " AND f.tipo_oferta = ?";
    $params[] = $tipo_oferta_filtro;
    $types .= "s";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="fichas-grid">
<?php while ($row = $result->fetch_assoc()):
  $estado = $row['Estado_ficha'] ?? 'Activo';
?>
  <div class="ficha-card" data-jornada="<?= strtolower($row['Jornada']) ?>">
      <div class="card-header">
          <span class="numero"><?= $row['numero_ficha'] ?></span>
          <div class="sena-logo">
              <img src="/proyecto-sena/assets/img/logo-sena.png" alt="Logo SENA" style="height:28px;">
          </div>
      </div>
      <p><strong><?= $lang['jefe_grupo'] ?? 'Jefe de grupo' ?>:</strong> <?= htmlspecialchars($row['jefe_nombre'] . ' ' . $row['jefe_apellido']) ?></p>
      <p><strong><?= $lang['programa'] ?? 'Programa' ?>:</strong> <?= htmlspecialchars($row['nombre_programa']) ?></p>
      <p><strong><?= $lang['tipo_oferta'] ?? 'Tipo de oferta' ?>:</strong> <?= htmlspecialchars($row['tipo_oferta']) ?></p>
      <p><strong><?= $lang['estado'] ?? 'Estado' ?>:</strong> <span class="estado-text"><?= htmlspecialchars($lang[strtolower($estado)] ?? $estado) ?></span></p>

      <button class="btn-ver-ficha" onclick="verFicha(<?= $row['Id_ficha'] ?>)"><?= $lang['ver_ficha'] ?? 'Ver Ficha' ?></button>

      <?php if ($is_admin): ?>
          <button class="btn-deshabilitar" onclick="cambiarEstadoFicha(this, <?= $row['Id_ficha'] ?>, '<?= $estado ?>')">
              <?= $estado === 'Activo' ? ($lang['deshabilitar'] ?? 'Deshabilitar') : ($lang['habilitar'] ?? 'Habilitar') ?>
          </button>
      <?php endif; ?>
  </div>
<?php endwhile; ?>
</div>

<script src="assets/js/listar_fichas.js"></script>
</body>
</html>
