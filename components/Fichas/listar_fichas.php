<?php
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
<?php endif; ?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fichas - Tecnólogo/Técnico</title>
  <link rel="stylesheet" href="assets/css/listar_fichas.css">
  <link rel="stylesheet" href="assets/css/header.css">
  <link rel="stylesheet" href="assets/css/footer.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php
$tipoSeleccionado = $_GET['tipo'] ?? 'todos';

$titulo = match ($tipoSeleccionado) {
  'tecnologo' => 'Ficha del Tecnólogo ',
  'tecnico'   => 'Ficha del Técnico ',
  default     => 'Fichas de los ( Tecnólogo / Técnico )'
};
?>

<div class="container">
  <div class="titulo">
    <h1 class="title"><?= $titulo ?></h1>
  </div>

  <div class="controls">
    <div class="search-box">
      <input type="text" placeholder="Consultar..." id="searchInput">
    </div>

    <div class="dropdown-container">
      <div class="dropdown-wrapper">
        <div class="dropdown" onclick="toggleDropdown()">
          <span>Selecciona el Horario De Jornada...</span>
          <span class="arrow">▼</span>
        </div>
        <div class="dropdown-options" id="dropdownOptions">
          <div class="option">Diurna</div>
          <div class="option">Mixta</div>
          <div class="option">Nocturna</div>
        </div>
      </div>
    </div>
  </div>

  <?php
  require_once 'db/conexion.php';

  $sql = "SELECT f.*, i.nombre AS jefe_nombre, i.apellido AS jefe_apellido 
          FROM fichas f
          LEFT JOIN instructores i ON f.Jefe_grupo = i.Id_instructor";
  $result = $conn->query($sql);
  ?>

  <div class="fichas-grid">
    <?php while ($row = $result->fetch_assoc()): 
      $programa = strtolower($row['programa_formación']);

      if (
        ($tipoSeleccionado === 'tecnologo' && $programa !== 'análisis y desarrollo de software') ||
        ($tipoSeleccionado === 'tecnico' && $programa !== 'técnico en programación')
      ) {
        continue;
      }

      $estado = $row['Estado_ficha'] ?? 'Activo';
    ?>
      <div class="ficha-card" data-jornada="<?= strtolower($row['Jornada']) ?>">
        <div class="card-header">
          <span class="numero"><?= $row['numero_ficha'] ?></span>
          <div class="sena-logo">
            <img src="/" alt="Logo SENA" style="height:28px;">
          </div>
        </div>
        <p><strong>Jefe:</strong> <?= $row['jefe_nombre'] . ' ' . $row['jefe_apellido'] ?></p>
        <p><strong>Programa:</strong> <?= $row['programa_formación'] ?></p>
        <p><strong>Estado:</strong> <span class="estado-text"><?= $estado ?></span></p>
        <button class="btn-ver-ficha" onclick="verFicha(<?= $row['Id_ficha'] ?>)">Ver ficha</button>
        <button class="btn-deshabilitar" onclick="cambiarEstadoFicha(this, <?= $row['Id_ficha'] ?>, '<?= $estado ?>')">
          <?= $estado === 'Activo' ? 'Deshabilitar' : 'Habilitar' ?>
        </button>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<script src="assets/js/listar_fichas.js"></script>
</body>
</html>
