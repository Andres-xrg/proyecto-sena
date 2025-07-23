<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db/conexion.php';

if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'creada'): ?>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Ficha creada correctamente',
      showConfirmButton: false,
      timer: 2000
    });
  </script>
<?php endif;

$id_programa = $_GET['id_programa'] ?? null;
$titulo = 'Fichas - Programa desconocido';

if ($id_programa) {
    $stmt = $conn->prepare("SELECT nombre_programa FROM programas_formacion WHERE Id_programa = ?");
    $stmt->bind_param("i", $id_programa);
    $stmt->execute();
    $resultado = $stmt->get_result()->fetch_assoc();
    $titulo = $resultado ? $resultado['nombre_programa'] : 'Fichas - Programa no encontrado';
}

$estado_filtro = strtolower($_GET['estado'] ?? '');
$jornada_filtro = $_GET['jornada'] ?? '';
$tipo_oferta_filtro = $_GET['tipo_oferta'] ?? '';
$is_admin = isset($_SESSION['usuario']) && strtolower($_SESSION['usuario']['rol']) === 'administrador';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fichas</title>
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

  <div class="controls">
    <form method="GET" action="index.php" style="display: flex; gap: 1rem; align-items: center;">
      <input type="hidden" name="page" value="components/Fichas/listar_fichas">
      <input type="hidden" name="id_programa" value="<?= htmlspecialchars($id_programa) ?>">

      <!-- Búsqueda -->
      <div class="search-box">
        <input type="text" placeholder="Buscar..." id="searchInput">
      </div>

      <!-- Dropdown Jornada -->
      <div class="dropdown-container">
        <div class="dropdown-wrapper">
          <div class="dropdown" onclick="toggleDropdown()">
            <span id="selectedJornada"><?= htmlspecialchars($jornada_filtro ?: 'Seleccionar jornada...') ?></span>
            <span class="arrow">▼</span>
          </div>
          <div class="dropdown-options" id="dropdownOptions">
            <div class="option" onclick="seleccionarJornada('Todos')">Todos</div>
            <div class="option" onclick="seleccionarJornada('Diurna')">Diurna</div>
            <div class="option" onclick="seleccionarJornada('Mixta')">Mixta</div>
            <div class="option" onclick="seleccionarJornada('Nocturna')">Nocturna</div>
          </div>
        </div>
        <input type="hidden" name="jornada" id="jornadaHidden" value="<?= htmlspecialchars($jornada_filtro) ?>">
      </div>

      <!-- Dropdown Tipo de Oferta -->
      <div class="dropdown-container">
        <div class="dropdown-wrapper">
          <div class="dropdown" onclick="toggleDropdownTipoOferta()">
            <span id="selectedTipoOferta"><?= htmlspecialchars($tipo_oferta_filtro ?: 'Tipo de oferta') ?></span>
            <span class="arrow">▼</span>
          </div>
          <div class="dropdown-options" id="dropdownTipoOfertaOptions">
            <div class="option" onclick="seleccionarTipoOferta('Todos')">Todos</div>
            <div class="option" onclick="seleccionarTipoOferta('Abierta')">Abierta</div>
            <div class="option" onclick="seleccionarTipoOferta('Cerrada')">Cerrada</div>
          </div>
        </div>
        <input type="hidden" name="tipo_oferta" id="tipoOfertaHidden" value="<?= htmlspecialchars($tipo_oferta_filtro) ?>">
      </div>

      <!-- Dropdown Estado (solo para administrador) -->
      <?php if ($is_admin): ?>
        <div class="dropdown-container">
          <div class="dropdown-wrapper">
            <div class="dropdown" onclick="toggleDropdownEstado()">
              <span id="selectedEstado"><?= $estado_filtro ? ucfirst($estado_filtro) : 'Filtrar por estado' ?></span>
              <span class="arrow">▼</span>
            </div>
            <div class="dropdown-options" id="dropdownEstadoOptions">
              <div class="option" onclick="seleccionarEstado('Todos')">Todos</div>
              <div class="option" onclick="seleccionarEstado('Activo')">Activo</div>
              <div class="option" onclick="seleccionarEstado('Inactivo')">Inactivo</div>
            </div>
          </div>
          <input type="hidden" name="estado" id="estadoHidden" value="<?= htmlspecialchars($estado_filtro) ?>">
        </div>
      <?php endif; ?>
    </form>
  </div>

  <?php
  // Construcción dinámica de la consulta
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
          <p><strong>Jefe de grupo:</strong> <?= htmlspecialchars($row['jefe_nombre'] . ' ' . $row['jefe_apellido']) ?></p>
          <p><strong>Programa:</strong> <?= htmlspecialchars($row['nombre_programa']) ?></p>
          <p><strong>Tipo de Oferta:</strong> <?= htmlspecialchars($row['tipo_oferta']) ?></p>
          <p><strong>Estado:</strong> <span class="estado-text"><?= $estado ?></span></p>

          <button class="btn-ver-ficha" onclick="verFicha(<?= $row['Id_ficha'] ?>)">Ver Ficha</button>

          <?php if ($is_admin): ?>
              <button class="btn-deshabilitar" onclick="cambiarEstadoFicha(this, <?= $row['Id_ficha'] ?>, '<?= $estado ?>')">
                  <?= $estado === 'Activo' ? 'Deshabilitar' : 'Habilitar' ?>
              </button>
          <?php endif; ?>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<script src="assets/js/listar_fichas.js"></script>
</body>
</html>
