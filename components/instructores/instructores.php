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
            i.fecha_inicio_contrato,
            i.fecha_fin_contrato,
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
        <h1 class="title" lang="es">Instructores</h1>
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
                    $tipoDoc = $instructor['T_documento'];
                ?>
                <div class="instructor-card <?= $claseCard ?>">
                    <div class="instructor-content">
                        <div class="avatar">
                            <div class="avatar-icon"><?= strtoupper(substr($instructor['nombre'], 0, 1)) ?></div>
                        </div>
                        <div class="instructor-info">
                            <div class="instructor-header">
                                <h3 class="instructor-name" lang="es">
                                    <?= htmlspecialchars($instructor['nombre']) . ' ' . htmlspecialchars($instructor['apellido']) ?>
                                </h3>
                                <?php if ($_SESSION['usuario']['rol'] === 'administrador'): ?>
                                    <div class="botones-acciones">
                                        <form method="POST" action="/proyecto-sena/functions/functions_instructores.php">
                                            <input type="hidden" name="id" value="<?= $instructor['Id_instructor'] ?>">
                                            <input type="hidden" name="accion" value="<?= $textoBoton ?>">
                                            <button type="submit" class="btn-estado <?= $claseBoton ?>" lang="es">
                                                <?= $textoBoton ?>
                                            </button>
                                        </form>
                                        <button class="btn-editar" onclick='abrirModal(<?= json_encode($instructor) ?>)' lang="es">Editar</button>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="instructor-details">
                                <div class="detail-item"><label lang="es">T. Documento</label><span><?= htmlspecialchars($instructor['T_documento']) ?></span></div>
                                <div class="detail-item"><label lang="es">Num. Documento</label><span><?= htmlspecialchars($instructor['N_Documento']) ?></span></div>
                                <div class="detail-item"><label lang="es">Correo Instructor</label><span><?= htmlspecialchars($instructor['Email']) ?></span></div>
                                <div class="detail-item"><label lang="es">Nº Teléfono</label><span><?= htmlspecialchars($instructor['N_Telefono']) ?></span></div>
                                <div class="detail-item estado-item"><label lang="es">Estado</label><span><?= $textoEstado ?></span></div>
                                <div class="detail-item"><label lang="es">Jefe de ficha</label><span><?= $jefeFicha ?></span></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p lang="es">No hay instructores registrados.</p>
        <?php endif; ?>
    </div>
</div>

<?php if ($_SESSION['usuario']['rol'] === 'administrador'): ?>
<!-- Modal edición -->
<div id="modalEditar" class="modal">
    <div class="modal-contenido">
        <span class="cerrar-modal" onclick="cerrarModal()" aria-label="Cerrar modal">&times;</span>
        <h2 lang="es">Editar Instructor</h2>
        <form id="formEditarInstructor" method="POST" action="/proyecto-sena/functions/actualizar_instructores.php" onsubmit="return validarFormulario()">
            <input type="hidden" name="id" id="editId">
            <input type="hidden" name="ficha" id="editFicha">

            <label lang="es">Nombre:</label>
            <input type="text" name="nombre" id="editNombre" required pattern="[A-Za-zÁÉÍÓÚñáéíóú\s]+" title="Solo letras y espacios">

            <label lang="es">Apellido:</label>
            <input type="text" name="apellido" id="editApellido" required pattern="[A-Za-zÁÉÍÓÚñáéíóú\s]+" title="Solo letras y espacios">

            <label lang="es">Email:</label>
            <input type="email" name="email" id="editEmail" required>

            <label lang="es">Tipo Documento:</label>
            <select name="tipo_documento" id="editTipoDocumento" required>
                <option value="CC">CC</option>
                <option value="CE">CE</option>
            </select>

            <label lang="es">Número Documento:</label>
            <input type="text" name="numero_documento" id="editNumeroDocumento" required pattern="[0-9]+" title="Solo números">

            <label lang="es">Teléfono:</label>
            <input type="text" name="telefono" id="editTelefono" required pattern="[0-9]+" title="Solo números">

            <button type="submit" lang="es">Actualizar</button>
        </form>
    </div>
</div>
<?php endif; ?>

<?php if (isset($_GET['success'])): ?>
<script>
    <?php if ($_GET['success'] === 'estado-cambiado'): ?>
    Swal.fire({ icon: 'success', title: '¡Éxito!', text: 'Estado actualizado.', confirmButtonColor: '#3085d6' });
    <?php elseif ($_GET['success'] === 'editado'): ?>
    Swal.fire({ icon: 'success', title: '¡Instructor actualizado!', text: 'Instructor editado correctamente.', confirmButtonColor: '#3085d6' });
    <?php elseif ($_GET['success'] === 'creado'): ?>
    Swal.fire({ icon: 'success', title: '¡Instructor creado!', text: 'Instructor registrado correctamente.', confirmButtonColor: '#3085d6' });
    <?php endif; ?>
</script>
<?php endif; ?>

<script>
function abrirModal(instructor) {
    document.getElementById('editId').value = instructor.Id_instructor;
    document.getElementById('editFicha').value = instructor.Ficha ?? '';
    document.getElementById('editNombre').value = instructor.nombre;
    document.getElementById('editApellido').value = instructor.apellido;
    document.getElementById('editEmail').value = instructor.Email;
    document.getElementById('editTipoDocumento').value = instructor.T_documento;
    document.getElementById('editNumeroDocumento').value = instructor.N_Documento;
    document.getElementById('editTelefono').value = instructor.N_Telefono;
    document.getElementById('editTipoInstructor').value = instructor.Tipo_instructor;

    document.getElementById('editFechaInicio').value = instructor.fecha_inicio_contrato ?? '';
    document.getElementById('editFechaFin').value = instructor.fecha_fin_contrato ?? '';

    mostrarFechasContrato();
    document.getElementById('modalEditar').style.display = 'block';
}

function cerrarModal() {
    document.getElementById('modalEditar').style.display = 'none';
}

function mostrarFechasContrato() {
    const tipo = document.getElementById('editTipoInstructor').value;
    const fechas = document.getElementById('fechasContrato');
    if (tipo === 'contratista') {
        fechas.style.display = 'block';
    } else {
        fechas.style.display = 'none';
        document.getElementById('editFechaInicio').value = '';
        document.getElementById('editFechaFin').value = '';
    }
}
</script>

</body>
</html>
