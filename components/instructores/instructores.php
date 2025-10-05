<?php
require_once(__DIR__ . "/../../db/conexion.php");
if (session_status() === PHP_SESSION_NONE) session_start();

// Acceso permitido
if (!ACCESO_PERMITIDO) {
    header("Location: /proyecto-sena/components/principales/login.php");
    exit;
}

require_once __DIR__ . '/../../functions/autenticacion_login.php';

// Idioma
$idioma = $_SESSION['lang'] ?? 'es';
$t = include __DIR__ . '/../../lang/' . $idioma . '.php';

// Consulta todos los instructores con estado desde usuarios
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
            u.estado AS estado_usuario,
            CASE 
                WHEN EXISTS (
                    SELECT 1 FROM fichas f WHERE f.Jefe_grupo = i.Id_instructor
                ) THEN 'Sí'
                ELSE 'No'
            END AS es_jefe_grupo
        FROM instructores i
        LEFT JOIN usuarios u ON u.Email = i.Email";

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
    <style>
        /* Clase para ocultar elementos sin tocar display flex del CSS */
        .hidden { display: none !important; }
    </style>
</head>
<body>

<div class="container">
    <div class="titulo">
        <h1 class="title"><?= $t['instructors'] ?? 'Instructores' ?></h1>
    </div>

    <div class="filtro-barra">
        <form>
            <div class="search-box">
                <input type="text" id="buscador" placeholder="<?= $t['search_instructor'] ?? 'Buscar instructor...' ?>" onkeyup="filtrarInstructores()">
            </div>
            <div class="dropdown-container">
                <select id="filtroTipo" class="dropdown" onchange="filtrarInstructores()">
                    <option value=""><?= $t['all_types'] ?? 'Todos los tipos' ?></option>
                    <option value="planta"><?= $t['permanent'] ?? 'Planta' ?></option>
                    <option value="contratista"><?= $t['contractor'] ?? 'Contratista' ?></option>
                    <option value="inactivo"><?= $t['inactive'] ?? 'Inactivo' ?></option>
                </select>
            </div>
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

                $activo = $instructor['estado_usuario'] == 1;

                // Deshabilitar automáticamente si es contratista y la fecha_fin_contrato pasó
                if ($instructor['Tipo_instructor'] === 'contratista' && !empty($instructor['fecha_fin_contrato'])) {
                    $fechaFin = strtotime($instructor['fecha_fin_contrato']);
                    $hoy = strtotime(date('Y-m-d'));
                    if ($fechaFin < $hoy && $activo) {
                        $updateSQL = "UPDATE usuarios SET estado = 0 WHERE Email = ?";
                        $stmt = $conn->prepare($updateSQL);
                        $stmt->bind_param("s", $instructor['Email']);
                        $stmt->execute();
                        $stmt->close();
                        $activo = false; 
                    }
                }

                $claseCard = $activo ? 'instructor-item' : 'instructor-item disabled';
                $textoEstado = $activo ? ($t['active'] ?? 'Activo') : ($t['inactive'] ?? 'Inactivo');
                $textoBoton = $activo ? ($t['disable'] ?? 'Deshabilitar') : ($t['enable'] ?? 'Habilitar');
                $claseBoton = $activo ? 'btn-deshabilitar' : 'btn-habilitar';
                $jefeFicha = $instructor['es_jefe_grupo'];
            ?>
            <div class="instructor-card <?= $claseCard ?>"
                 data-tipo="<?= strtolower($activo ? $instructor['Tipo_instructor'] : 'inactivo') ?>"
                 data-rol="<?= strtolower($instructor['rol_instructor'] ?? '') ?>">
                <div class="instructor-content">
                    <div class="avatar">
                        <div class="avatar-icon"><?= strtoupper(substr($instructor['nombre'], 0, 1)) ?></div>
                    </div>
                    <div class="instructor-info">
                        <div class="instructor-header">
                            <h3 class="instructor-name"><?= htmlspecialchars($instructor['nombre'] . ' ' . $instructor['apellido']) ?></h3>
                            <?php if ($es_admin): ?>
                            <div class="botones-acciones">
                                <?php if (empty($instructor['fecha_fin_contrato'])): ?>
                                <form method="POST" action="/proyecto-sena/functions/functions_instructores.php" class="form-estado">
                                    <input type="hidden" name="id" value="<?= $instructor['Id_instructor'] ?>">
                                    <input type="hidden" name="accion" value="<?= $textoBoton ?>">
                                    <input type="hidden" name="tipo_instructor" value="<?= $instructor['Tipo_instructor'] ?>">
                                    <button type="submit" class="btn-estado <?= $claseBoton ?>"><?= $textoBoton ?></button>
                                </form>
                                <?php endif; ?>
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
<!-- Modal Editar Instructor -->
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

            <label><?= $t['doc_number'] ?? 'Número de Documento' ?></label>
            <input type="text" name="numero_documento" id="editNumeroDocumento" required pattern="\d+" title="<?= $t['numbers_only'] ?? 'Solo números' ?>" oninput="this.value=this.value.replace(/\D/g,'')">
            <label><?= $t['phone'] ?? 'Teléfono' ?></label>
            <input type="text" name="telefono" id="editTelefono" required pattern="\d+" title="<?= $t['numbers_only'] ?? 'Solo números' ?>" oninput="this.value=this.value.replace(/\D/g,'')">

            <label><?= $t['instructor_type'] ?? 'Tipo de Instructor' ?>:</label>
            <select name="tipo_instructor" id="editTipoInstructor" onchange="mostrarFechasContrato()">
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
                <label><?= $t['contract_end'] ?? 'Fecha Fin Contrato' ?></label>
                <input type="date" name="fecha_fin_contrato" id="editFechaFin">
            </div>

            <button type="submit"><?= $t['update'] ?? 'Actualizar' ?></button>
        </form>
    </div>
</div>
<?php endif; ?>

<?php if (isset($_GET['success'])): ?>
<script>
document.addEventListener("DOMContentLoaded", () => {
    <?php if ($_GET['success'] === 'estado-cambiado'): ?>
    Swal.fire({icon:'success', title:'<?= $t['success'] ?>', text:'<?= $t['status_updated'] ?>', confirmButtonColor:'#3085d6'});
    <?php elseif ($_GET['success'] === 'editado'): ?>
    Swal.fire({icon:'success', title:'<?= $t['success'] ?>', text:'<?= $t['instructor_edited'] ?>', confirmButtonColor:'#3085d6'});
    <?php elseif ($_GET['success'] === 'creado'): ?>
    Swal.fire({icon:'success', title:'<?= $t['success'] ?>', text:'<?= $t['instructor_created'] ?>', confirmButtonColor:'#3085d6'});
    <?php endif; ?>

    if (window.history.replaceState) {
        const url = new URL(window.location.href);
        url.searchParams.delete("success");
        window.history.replaceState({}, document.title, url.toString());
    }
});
</script>
<?php endif; ?>

<script>
const correosExistentes = [
    <?php
    $resultado->data_seek(0);
    while ($row = $resultado->fetch_assoc()) {
        echo "{id: {$row['Id_instructor']}, email: '" . addslashes($row['Email']) . "'},";
    }
    ?>
];

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

function validarFormulario() {
    const inicio = document.getElementById('editFechaInicio').value;
    const fin = document.getElementById('editFechaFin').value;
    if (inicio && fin && inicio > fin) {
        Swal.fire({icon:'error', title:'Error', text:'<?= $t['date_error'] ?? 'Fecha de inicio no puede ser mayor a fecha fin' ?>'});
        return false;
    }

    const numDoc = parseInt(document.getElementById('editNumeroDocumento').value, 10);
    const telefono = parseInt(document.getElementById('editTelefono').value, 10);

    if (isNaN(numDoc) || isNaN(telefono)) {
        Swal.fire({icon:'error', title:'Error', text:'<?= $t['numbers_only'] ?? 'Número de documento y teléfono deben ser válidos' ?>'});
        return false;
    }

    if (numDoc <= 0 || telefono <= 0) {
        Swal.fire({icon:'error', title:'Error', text:'<?= $t['positive_numbers_only'] ?? 'Número de documento y teléfono deben ser positivos' ?>'});
        return false;
    }

    const email = document.getElementById('editEmail').value.trim().toLowerCase();
    const idActual = parseInt(document.getElementById('editId').value, 10);

    const emailDuplicado = correosExistentes.some(c => c.email.toLowerCase() === email && c.id !== idActual);
    if (emailDuplicado) {
        Swal.fire({icon:'error', title:'Error', text:'<?= $t['email_exists'] ?? 'Este correo ya está registrado' ?>'});
        return false;
    }

    return true;
}

// --- FILTRO SIN MODIFICAR DISPLAY FLEX --- 
function filtrarInstructores() {
    const texto = document.getElementById("buscador").value.toLowerCase().trim();
    const tipoFiltro = document.getElementById("filtroTipo").value.toLowerCase();
    const rolFiltro = document.getElementById("filtroRol").value.toLowerCase();

    document.querySelectorAll(".instructor-item").forEach(item => {
        const nombre = item.querySelector(".instructor-name")?.innerText.toLowerCase() || "";
        const tipo = item.getAttribute("data-tipo") || "";
        const rol = item.getAttribute("data-rol") || "";

        const mostrar = 
            (nombre.includes(texto) || texto === "") &&
            (tipoFiltro === "" || tipo === tipoFiltro) &&
            (rolFiltro === "" || rol === rolFiltro);

        item.classList.toggle('hidden', !mostrar);
    });
}

</script>

</body>
</html>
