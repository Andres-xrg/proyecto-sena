<?php
require_once(__DIR__ . "/../../db/conexion.php");
if (session_status() === PHP_SESSION_NONE) session_start();

// Acceso permitido
if (!ACCESO_PERMITIDO) {
    header("Location: /proyecto-sena/components/principales/login.php");
}

// Idioma
$idioma = $_SESSION['lang'] ?? 'es';
$t = include __DIR__ . '/../../lang/' . $idioma . '.php';

// Consulta **todos** los instructores sin paginación
$sql = "SELECT 
            i.Id_instructor, 
            i.nombre, 
            i.apellido, 
            i.Email, 
            i.T_documento, 
            i.N_Documento, 
            i.N_Telefono, 
            i.Tipo_instructor,
            i.rol_instructor,
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
if (!$resultado) die($t['sql_error'] ?? "Error en la consulta SQL: " . $conn->error);

$es_admin = isset($_SESSION['usuario']['rol']) && $_SESSION['usuario']['rol'] === 'administrador';
?>

<!DOCTYPE html>
<html lang="<?= $idioma ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $t['instructors'] ?? 'Instructores' ?></title>
    <link rel="stylesheet" href="/proyecto-sena/assets/css/instructores.css">
    <link rel="stylesheet" href="/proyecto-sena/assets/css/header.css">
    <link rel="stylesheet" href="/proyecto-sena/assets/css/footer.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div class="container">
    <div class="titulo">
        <h1 class="title"><?= $t['instructors'] ?? 'Instructores' ?></h1>
    </div>

<!-- 🔎 Buscador y filtros integrados -->
<div class="filtro-barra">
  <form>
    <!-- Buscador -->
    <div class="search-box">
      <input 
        type="text" 
        id="buscador" 
        placeholder="<?= $t['search_instructor'] ?? 'Buscar instructor...' ?>" 
        onkeyup="filtrarInstructores()"
      >
    </div>

    <!-- Filtro por Tipo -->
    <div class="dropdown-container">
      <select id="filtroTipo" class="dropdown" onchange="filtrarInstructores()">
        <option value=""><?= $t['all_types'] ?? 'Todos los tipos' ?></option>
        <option value="planta"><?= $t['permanent'] ?? 'Planta' ?></option>
        <option value="contratista"><?= $t['contractor'] ?? 'Contratista' ?></option>
        <option value="inactivo"><?= $t['inactive'] ?? 'Inactivo' ?></option>
      </select>
    </div>

    <!-- Filtro por Rol -->
    <div class="dropdown-container">
      <select id="filtroRol" class="dropdown" onchange="filtrarInstructores()">
        <option value=""><?= $t['all_roles'] ?? 'Todos los roles' ?></option>
        <option value="clave"><?= $t['key_role'] ?? 'Clave' ?></option>
        <option value="transversal"><?= $t['transversal'] ?? 'Transversal' ?></option>
        <option value="tecnico"><?= $t['technical'] ?? 'Técnico' ?></option>
      </select>
    </div>
  </form>
</div>


    <div class="instructores-list" id="lista-instructores">
        <?php if ($resultado->num_rows > 0): ?>
            <?php while ($instructor = $resultado->fetch_assoc()): 
                $activo = $instructor['Tipo_instructor'] !== 'Inactivo';
                $claseCard = $activo ? 'instructor-item' : 'instructor-item disabled';
                $textoEstado = $activo ? ($t['active'] ?? 'Activo') : ($t['inactive'] ?? 'Inactivo');
                $textoBoton = $activo ? ($t['disable'] ?? 'Deshabilitar') : ($t['enable'] ?? 'Habilitar');
                $claseBoton = $activo ? 'btn-deshabilitar' : 'btn-habilitar';
                $jefeFicha = $instructor['es_jefe_grupo'];
            ?>
            <div class="instructor-card <?= $claseCard ?> instructor-item"
                 data-tipo="<?= strtolower($instructor['Tipo_instructor']) ?>"
                 data-rol="<?= strtolower($instructor['rol_instructor'] ?? '') ?>">
                <div class="instructor-content">
                    <div class="avatar">
                        <div class="avatar-icon"><?= strtoupper(substr($instructor['nombre'], 0, 1)) ?></div>
                    </div>
                    <div class="instructor-info">
                        <div class="instructor-header">
                            <h3 class="instructor-name">
                                <?= htmlspecialchars($instructor['nombre'] . ' ' . $instructor['apellido']) ?>
                            </h3>
                            <?php if ($es_admin): ?>
                            <div class="botones-acciones">
                                <form method="POST" action="/proyecto-sena/functions/functions_instructores.php">
                                    <input type="hidden" name="id" value="<?= $instructor['Id_instructor'] ?>">
                                    <input type="hidden" name="accion" value="<?= $textoBoton ?>">
                                    <button type="submit" class="btn-estado <?= $claseBoton ?>"><?= $textoBoton ?></button>
                                </form>
                                <button class="btn-editar" onclick='abrirModal(<?= json_encode($instructor) ?>)'><?= $t['edit'] ?? 'Editar' ?></button>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="instructor-details">
                            <div class="detail-item"><label><?= $t['doc_type'] ?? 'T. Documento' ?></label><span><?= htmlspecialchars($instructor['T_documento']) ?></span></div>
                            <div class="detail-item"><label><?= $t['doc_number'] ?? 'Num. Documento' ?></label><span><?= htmlspecialchars($instructor['N_Documento']) ?></span></div>
                            <div class="detail-item"><label><?= $t['email'] ?? 'Correo Instructor' ?></label><span><?= htmlspecialchars($instructor['Email']) ?></span></div>
                            <div class="detail-item"><label><?= $t['phone'] ?? 'Nº Teléfono' ?></label><span><?= htmlspecialchars($instructor['N_Telefono']) ?></span></div>
                            <div class="detail-item"><label><?= $t['instructor_type'] ?? 'Tipo Instructor' ?></label><span><?= ucfirst($instructor['Tipo_instructor']) ?></span></div>
                            <div class="detail-item"><label><?= $t['instructor_role'] ?? 'Rol Instructor' ?></label><span><?= ucfirst($instructor['rol_instructor'] ?? 'No definido') ?></span></div>

                            <?php if ($instructor['Tipo_instructor'] === 'contratista'): ?>
                                <div class="detail-item"><label><?= $t['contract_start'] ?? 'Fecha Inicio Contrato' ?></label><span><?= htmlspecialchars($instructor['fecha_inicio_contrato']) ?: ($t['not_applicable'] ?? 'No aplica') ?></span></div>
                                <div class="detail-item"><label><?= $t['contract_end'] ?? 'Fecha Fin Contrato' ?></label><span><?= htmlspecialchars($instructor['fecha_fin_contrato']) ?: ($t['not_applicable'] ?? 'No aplica') ?></span></div>
                            <?php endif; ?>

                            <div class="detail-item estado-item"><label><?= $t['status'] ?? 'Estado' ?></label><span><?= $textoEstado ?></span></div>
                            <div class="detail-item"><label><?= $t['group_leader'] ?? 'Jefe de ficha' ?></label><span><?= $jefeFicha ?></span></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p><?= $t['no_instructors'] ?? 'No hay instructores registrados.' ?></p>
        <?php endif; ?>
    </div>
</div>

<?php if ($es_admin): ?>
<div id="modalEditar" class="modal">
    <div class="modal-contenido">
        <span class="cerrar-modal" onclick="cerrarModal()">&times;</span>
        <h2><?= $t['edit_instructor'] ?? 'Editar Instructor' ?></h2>
        <form id="formEditarInstructor" method="POST" action="/proyecto-sena/functions/actualizar_instructores.php" onsubmit="return validarFormulario()">
            <input type="hidden" name="id" id="editId">
            <input type="hidden" name="ficha" id="editFicha">
            <label><?= $t['first_name'] ?? 'Nombre' ?>:</label>
            <input type="text" name="nombre" id="editNombre" required pattern="[A-Za-zÁÉÍÓÚñáéíóú\s]+" title="<?= $t['letters_only'] ?? 'Solo letras y espacios' ?>">
            <label><?= $t['last_name'] ?? 'Apellido' ?>:</label>
            <input type="text" name="apellido" id="editApellido" required pattern="[A-Za-zÁÉÍÓÚñáéíóú\s]+" title="<?= $t['letters_only'] ?? 'Solo letras y espacios' ?>">
            <label><?= $t['email'] ?? 'Email' ?>:</label>
            <input type="email" name="email" id="editEmail" required>
            <label><?= $t['doc_type'] ?? 'Tipo Documento' ?>:</label>
            <select name="tipo_documento" id="editTipoDocumento" required>
                <option value="CC">CC</option>
                <option value="CE">CE</option>
            </select>
            <label><?= $t['doc_number'] ?? 'Número Documento' ?>:</label>
            <input type="text" name="numero_documento" id="editNumeroDocumento" required pattern="[0-9]+" title="<?= $t['numbers_only'] ?? 'Solo números' ?>">
            <label><?= $t['phone'] ?? 'Teléfono' ?>:</label>
            <input type="text" name="telefono" id="editTelefono" required pattern="[0-9]+" title="<?= $t['numbers_only'] ?? 'Solo números' ?>">
            <label><?= $t['instructor_type'] ?? 'Tipo de Instructor' ?>:</label>
            <select name="tipo_instructor" id="editTipoInstructor" required onchange="mostrarFechasContrato()">
                <option value=""><?= $t['select_type'] ?? 'Seleccione tipo' ?></option>
                <option value="planta"><?= $t['permanent'] ?? 'Planta' ?></option>
                <option value="contratista"><?= $t['contractor'] ?? 'Contratista' ?></option>
            </select>
            <label><?= $t['instructor_role'] ?? 'Rol Instructor' ?>:</label>
            <select name="rol_instructor" id="editRolInstructor" required>
                <option value=""><?= $t['select_role'] ?? 'Seleccione rol' ?></option>
                <option value="clave"><?= $t['key_role'] ?? 'Clave' ?></option>
                <option value="transversal"><?= $t['transversal'] ?? 'Transversal' ?></option>
                <option value="tecnico"><?= $t['technical'] ?? 'Técnico' ?></option>
            </select>
            <div id="fechasContrato" style="display: none;">
                <label><?= $t['contract_start'] ?? 'Fecha Inicio Contrato' ?>:</label>
                <input type="date" name="fecha_inicio_contrato" id="editFechaInicio">
                <label><?= $t['contract_end'] ?? 'Fecha Fin Contrato' ?>:</label>
                <input type="date" name="fecha_fin_contrato" id="editFechaFin">
            </div>
            <button type="submit"><?= $t['update'] ?? 'Actualizar' ?></button>
        </form>
    </div>
</div>
<?php endif; ?>

<?php if (isset($_GET['success'])): ?>
<script>
    <?php if ($_GET['success'] === 'estado-cambiado'): ?>
    Swal.fire({ icon: 'success', title: '<?= $t['success'] ?? '¡Éxito!' ?>', text: '<?= $t['status_updated'] ?? 'Estado actualizado.' ?>', confirmButtonColor: '#3085d6' });
    <?php elseif ($_GET['success'] === 'editado'): ?>
    Swal.fire({ icon: 'success', title: '<?= $t['success'] ?? '¡Éxito!' ?>', text: '<?= $t['instructor_edited'] ?? 'Instructor editado correctamente.' ?>', confirmButtonColor: '#3085d6' });
    <?php elseif ($_GET['success'] === 'creado'): ?>
    Swal.fire({ icon: 'success', title: '<?= $t['success'] ?? '¡Éxito!' ?>', text: '<?= $t['instructor_created'] ?? 'Instructor registrado correctamente.' ?>', confirmButtonColor: '#3085d6' });
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
    document.getElementById('editRolInstructor').value = instructor.rol_instructor ?? '';
    document.getElementById('editFechaInicio').value = instructor.fecha_inicio_contrato ?? '';
    document.getElementById('editFechaFin').value = instructor.fecha_fin_contrato ?? '';
    mostrarFechasContrato();
    document.getElementById('modalEditar').style.display = 'block';
}

function cerrarModal() { document.getElementById('modalEditar').style.display = 'none'; }

function mostrarFechasContrato() {
    const tipo = document.getElementById('editTipoInstructor').value;
    const fechas = document.getElementById('fechasContrato');
    if (tipo === 'contratista') fechas.style.display = 'block';
    else {
        fechas.style.display = 'none';
        document.getElementById('editFechaInicio').value = '';
        document.getElementById('editFechaFin').value = '';
    }
}

function filtrarInstructores() {
    const texto = document.getElementById("buscador").value.toLowerCase().trim();
    const tipoFiltro = document.getElementById("filtroTipo").value.toLowerCase();
    const rolFiltro = document.getElementById("filtroRol").value.toLowerCase();

    document.querySelectorAll(".instructor-item").forEach(item => {
        const nombre = item.querySelector(".instructor-name")?.innerText.toLowerCase() || "";
        const tipo = item.getAttribute("data-tipo") || "";
        const rol = item.getAttribute("data-rol") || "";

        const coincideTexto = nombre.includes(texto);
        const coincideTipo = tipoFiltro === "" || tipo === tipoFiltro;
        const coincideRol = rolFiltro === "" || rol === rolFiltro;

        item.style.display = (coincideTexto && coincideTipo && coincideRol) ? "block" : "none";
    });
}
</script>

</body>
</html>
