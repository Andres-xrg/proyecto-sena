<?php
require_once(__DIR__ . "/../../db/conexion.php");
if (session_status() === PHP_SESSION_NONE) session_start();

if (!ACCESO_PERMITIDO) {
    header("Location: /proyecto-sena/components/principales/login.php");
}

$sql = "SELECT 
            i.Id_instructor, 
            i.nombre, 
            i.apellido, 
            i.Email, 
            i.T_documento, 
            i.N_Documento, 
            i.N_Telefono, 
            i.Tipo_instructor,
            CASE 
                WHEN EXISTS (
                    SELECT 1 FROM fichas f WHERE f.Jefe_grupo = i.Id_instructor
                ) THEN 'Sí'
                ELSE 'No'
            END AS es_jefe_grupo
        FROM instructores i";

$resultado = $conn->query($sql);
if (!$resultado) die("Error en la consulta SQL: " . $conn->error);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Instructores</title>
    <link rel="stylesheet" href="/proyecto-sena/assets/css/instructores.css">
    <link rel="stylesheet" href="/proyecto-sena/assets/css/header.css">
    <link rel="stylesheet" href="/proyecto-sena/assets/css/footer.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div class="container">
    <div class="titulo">
        <h1 class="title">Instructores</h1>
    </div>

    <div class="instructores-list">
        <?php if ($resultado->num_rows > 0): ?>
            <?php while ($instructor = $resultado->fetch_assoc()): ?>
                <?php
                    $activo = $instructor['Tipo_instructor'] !== 'Inactivo';
                    $claseCard = $activo ? '' : 'disabled';
                    $textoEstado = $activo ? 'Activo' : 'Inactivo';
                    $textoBoton = $activo ? 'Deshabilitar' : 'Habilitar';
                    $claseBoton = $activo ? 'btn-deshabilitar' : 'btn-habilitar';
                    $jefeFicha = $instructor['es_jefe_grupo'];
                ?>
                <div class="instructor-card <?= $claseCard ?>">
                    <div class="instructor-content">
                        <div class="avatar">
                            <div class="avatar-icon"><?= strtoupper(substr($instructor['nombre'], 0, 1)) ?></div>
                        </div>
                        <div class="instructor-info">
                            <div class="instructor-header">
                                <h3 class="instructor-name">
                                    <?= htmlspecialchars($instructor['nombre']) . ' ' . htmlspecialchars($instructor['apellido']) ?>
                                </h3>
                                <?php if ($_SESSION['usuario']['rol'] === 'administrador'): ?>
                                    <div class="botones-acciones">
                                        <form method="POST" action="/proyecto-sena/functions/functions_instructores.php">
                                            <input type="hidden" name="id" value="<?= $instructor['Id_instructor'] ?>">
                                            <input type="hidden" name="accion" value="<?= $textoBoton ?>">
                                            <button type="submit" class="btn-estado <?= $claseBoton ?>">
                                                <?= $textoBoton ?>
                                            </button>
                                        </form>
                                        <button class="btn-editar" onclick='abrirModal(<?= json_encode($instructor) ?>)'>Editar</button>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="instructor-details">
                                <div class="detail-item"><label>T. Documento</label><span><?= htmlspecialchars($instructor['T_documento']) ?></span></div>
                                <div class="detail-item"><label>Num. Documento</label><span><?= htmlspecialchars($instructor['N_Documento']) ?></span></div>
                                <div class="detail-item"><label>Correo Instructor</label><span><?= htmlspecialchars($instructor['Email']) ?></span></div>
                                <div class="detail-item"><label>Nº Teléfono</label><span><?= htmlspecialchars($instructor['N_Telefono']) ?></span></div>
                                <div class="detail-item estado-item"><label>Estado</label><span><?= $textoEstado ?></span></div>
                                <div class="detail-item"><label>Jefe de ficha</label><span><?= $jefeFicha ?></span></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No hay instructores registrados.</p>
        <?php endif; ?>
    </div>
</div>

<?php if ($_SESSION['usuario']['rol'] === 'administrador'): ?>
<!-- Modal edición -->
<div id="modalEditar" class="modal">
    <div class="modal-contenido">
        <span class="cerrar-modal" onclick="cerrarModal()">&times;</span>
        <h2>Editar Instructor</h2>
        <form id="formEditarInstructor" method="POST" action="/proyecto-sena/functions/actualizar_instructores.php" onsubmit="return validarFormulario()">
            <input type="hidden" name="id" id="editId">
            <input type="hidden" name="ficha" id="editFicha">

            <label>Nombre:</label>
            <input type="text" name="nombre" id="editNombre" required pattern="[A-Za-zÁÉÍÓÚñáéíóú\s]+" title="Solo letras y espacios">

            <label>Apellido:</label>
            <input type="text" name="apellido" id="editApellido" required pattern="[A-Za-zÁÉÍÓÚñáéíóú\s]+" title="Solo letras y espacios">

            <label>Email:</label>
            <input type="email" name="email" id="editEmail" required>

            <label>Tipo Documento:</label>
            <select name="tipo_documento" id="editTipoDocumento" required>
                <option value="CC">CC</option>
                <option value="CE">CE</option>
            </select>

            <label>Número Documento:</label>
            <input type="text" name="numero_documento" id="editNumeroDocumento" required pattern="[0-9]+" title="Solo números">

            <label>Teléfono:</label>
            <input type="text" name="telefono" id="editTelefono" required pattern="[0-9]+" title="Solo números">

            <button type="submit">Actualizar</button>
        </form>
    </div>
</div>
<?php endif; ?>

<!-- ALERTAS SWEETALERT -->
<?php if (isset($_GET['success'])): ?>
<script>
    <?php if ($_GET['success'] === 'estado-cambiado'): ?>
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: 'Estado del instructor actualizado correctamente.',
        confirmButtonColor: '#3085d6'
    });
    <?php elseif ($_GET['success'] === 'editado'): ?>
    Swal.fire({
        icon: 'success',
        title: '¡Instructor actualizado!',
        text: 'El instructor fue editado correctamente.',
        confirmButtonColor: '#3085d6'
    });
    <?php elseif ($_GET['success'] === 'creado'): ?>
    Swal.fire({
        icon: 'success',
        title: '¡Instructor creado!',
        text: 'El instructor fue registrado correctamente.',
        confirmButtonColor: '#3085d6'
    });
    <?php endif; ?>
</script>
<?php endif; ?>

<script src="/proyecto-sena/assets/js/editar_instructores.js"></script>
</body>
</html>
