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
  <title>Fichas</title>
  <link rel="stylesheet" href="assets/css/listar_fichas.css">
  <link rel="stylesheet" href="assets/css/header.css">
  <link rel="stylesheet" href="assets/css/footer.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php
require_once 'db/conexion.php';

$id_programa = $_GET['id_programa'] ?? null;
$titulo = 'Fichas - Programa desconocido';

if ($id_programa) {
    $stmt = $conn->prepare("SELECT nombre_programa FROM programas_formacion WHERE Id_programa = ?");
    $stmt->bind_param("i", $id_programa);
    $stmt->execute();
    $resultado = $stmt->get_result()->fetch_assoc();
    $titulo = $resultado ? $resultado['nombre_programa'] : 'Fichas - Programa no encontrado';
}
?>

<div class="container">
  <div class="titulo">
    <h1 class="title"><?= htmlspecialchars($titulo) ?></h1>
  </div>

  <div class="controls">
    <div class="search-box">
      <input type="text" placeholder="Buscar..." id="searchInput">
    </div>

    <div class="dropdown-container">
      <div class="dropdown-wrapper">
        <div class="dropdown" onclick="toggleDropdown()">
          <span>Seleccionar jornada...</span>
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
  $sql = "SELECT f.*, i.nombre AS jefe_nombre, i.apellido AS jefe_apellido, p.nombre_programa
          FROM fichas f
          LEFT JOIN instructores i ON f.Jefe_grupo = i.Id_instructor
          LEFT JOIN programas_formacion p ON f.Id_programa = p.Id_programa
          WHERE f.Id_programa = ?";
  $stmt_fichas = $conn->prepare($sql);
  $stmt_fichas->bind_param("i", $id_programa);
  $stmt_fichas->execute();
  $result = $stmt_fichas->get_result();
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
          <p><strong>Estado:</strong> 
              <span class="estado-text"><?= $estado === 'Activo' ? 'Activo' : 'Inactivo' ?></span>
          </p>
          <button class="btn-ver-ficha" onclick="verFicha(<?= $row['Id_ficha'] ?>)">
              Ver Ficha
          </button>
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
