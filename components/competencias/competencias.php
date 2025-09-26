
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../functions/functions_agrupar_competencias.php';

$documento = $_GET['doc'] ?? null;
$aprendiz = null;
$idAprendiz = null;

if (!$documento) {
    echo "<p style='color:red;'>Documento no válido.</p>";
    exit;
}

// 1. Obtener datos del aprendiz
$stmt1 = $conn->prepare("SELECT * FROM aprendices WHERE N_Documento = ?");
if (!$stmt1) {
    die("Error al preparar la consulta de aprendiz: " . $conn->error);
}
$stmt1->bind_param("s", $documento);
$stmt1->execute();
$result1 = $stmt1->get_result();
$aprendiz = $result1->fetch_assoc();

if (!$aprendiz) {
    echo "<p style='color:red;'>Aprendiz no encontrado.</p>";
    exit;
}

$idAprendiz = $aprendiz['Id_aprendiz'] ?? null;

// 2. Obtener juicios evaluativos
$stmt2 = $conn->prepare("SELECT * FROM juicios_evaluativos WHERE N_Documento = ? ORDER BY Competencia, Resultado_aprendizaje, Fecha_registro DESC");
if (!$stmt2) {
    die("Error al preparar la consulta de juicios: " . $conn->error);
}
$stmt2->bind_param("s", $documento);
$stmt2->execute();
$resultado = $stmt2->get_result();

if (!$resultado) {
    die("Error al obtener los juicios evaluativos: " . $conn->error);
}

// 3. Agrupar competencias
$resultado->data_seek(0);
list($competencias_agrupadas, $materias_organizadas) = agruparCompetencias($resultado);

//4. Obtener el estado de formación desde el último juicio evaluativo registrado
$stmt_estado = $conn->prepare("SELECT Estado_formacion FROM juicios_evaluativos WHERE N_Documento = ? ORDER BY Fecha_registro DESC LIMIT 1");
$stmt_estado->bind_param("s", $documento);
$stmt_estado->execute();
$res_estado = $stmt_estado->get_result();
$estado_formacion = $res_estado->fetch_assoc()['Estado_formacion'] ?? 'No registrado';
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/competencias.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juicios de <?= htmlspecialchars($aprendiz['nombre'] ?? '') ?></title>
</head>
<body>
<main class="main-content">
    <h1 class="page-title">Juicios Evaluativos de <?= htmlspecialchars($aprendiz['nombre'] ?? '') ?> <?= htmlspecialchars($aprendiz['apellido'] ?? '') ?></h1>

    <div class="search-section">
        <div class="search-container">
            <button class="btn-generate-report" onclick="abrirModal()">
                <i class="fas fa-file-alt"></i>
                GENERAR REPORTE
            </button>
        </div>
    </div>

    <div class="content-grid">
        <?php foreach ($materias_organizadas as $categoria => $materias_categoria): ?>
            <?php if (!empty($materias_categoria)): ?>
                <div class="category-column">
                    <div class="card category-card">
                        <div class="card-header category-header">
                            <span class="card-title"><?= htmlspecialchars($categoria) ?></span>
                        </div>
                        <div class="card-content category-content">
                            <?php foreach ($materias_categoria as $materia => $competencias_materia): ?>
                                <?php if (!empty($competencias_materia)): ?>
                                    <div class="subject-section">
                                        <div class="subject-header" data-bs-toggle="collapse" data-bs-target="#collapse-<?= md5($categoria . $materia) ?>" aria-expanded="false">
                                            <span class="subject-title"><?= htmlspecialchars($materia) ?></span>
                                            <div class="subject-stats">
                                                <?php
                                                $total_resultados = 0;
                                                $aprobados = 0;
                                                foreach ($competencias_materia as $juicios) {
                                                    foreach ($juicios as $juicio) {
                                                        $estado = strtolower(trim($juicio['Juicio'] ?? ''));
                                                        $total_resultados++;
                                                        if ($estado === 'aprobado') $aprobados++;
                                                    }
                                                }
                                                ?>
                                                <span class="stats-badge"><?= $aprobados ?>/<?= $total_resultados ?></span>
                                                <i class="fas fa-chevron-down collapse-icon"></i>
                                            </div>
                                        </div>
                                        <div class="collapse subject-content" id="collapse-<?= md5($categoria . $materia) ?>">
                                            <div class="results-container">
                                                <?php foreach ($competencias_materia as $competencia => $juicios_de_la_competencia): ?>
                                                   <?php
                                                        $tiene_no_aprobado = false;
                                                        $tiene_por_evaluar = false;
                                                        $todos_aprobados = true;

                                                        foreach ($juicios_de_la_competencia as $j) {
                                                            $estado = strtolower(trim($j['Juicio'] ?? ''));

                                                            if ($estado === 'no aprobado') {
                                                                $tiene_no_aprobado = true;
                                                                $todos_aprobados = false;
                                                                break; // no puede ser aprobado ni por evaluar
                                                            } elseif ($estado === 'por evaluar' || $estado === '') {
                                                                $tiene_por_evaluar = true;
                                                                $todos_aprobados = false;
                                                            } elseif ($estado !== 'aprobado') {
                                                                $todos_aprobados = false;
                                                            }
                                                        }

                                                        if ($todos_aprobados) {
                                                            $estado_comp = 'Aprobado';
                                                            $clase_comp = 'status-approved';
                                                            $icono = 'fa-check-circle';
                                                        } elseif ($tiene_no_aprobado) {
                                                            $estado_comp = 'No Aprobado';
                                                            $clase_comp = 'status-rejected';
                                                            $icono = 'fa-times-circle';
                                                        } else {
                                                            $estado_comp = 'Por Evaluar';
                                                            $clase_comp = 'status-pending';
                                                            $icono = 'fa-clock';
                                                        }
                                                        ?>
                                                        <div class="competencia-header" data-bs-toggle="collapse" data-bs-target="#collapse-<?= md5($competencia) ?>">
                                                            <strong><?= htmlspecialchars($competencia) ?></strong>
                                                            <span class="result-status <?= $clase_comp ?>">
                                                                <i class="fas <?= $icono ?>"></i>
                                                                <?= $estado_comp ?>
                                                            </span>
                                                            <i class="fas fa-chevron-down float-end mt-1"></i>
                                                        </div>
                                                    <div class="collapse competencia-body" id="collapse-<?= md5($competencia) ?>">
                                                        <div class="results-header">
                                                            <span>Resultados de Aprendizaje</span>
                                                            <span>Instructor</span>
                                                            <span>Estado</span>
                                                        </div>
                                                        <?php foreach ($juicios_de_la_competencia as $j): ?>
                                                            <div class="result-item">
                                                                <div class="result-code">
                                                                    <div class="result-description"><?= htmlspecialchars($j['Resultado_aprendizaje'] ?? '') ?></div>
                                                                    <div class="result-date">
                                                                        <small><i class="fas fa-calendar"></i> <?= isset($j['Fecha_registro']) ? date('d/m/Y', strtotime($j['Fecha_registro'])) : 'N/A' ?></small>
                                                                    </div>
                                                                </div>
                                                                <div class="result-instructor">
                                                                    <i class="fas fa-user"></i>
                                                                    <?= htmlspecialchars($j['Funcionario_registro'] ?? 'N/A') ?>
                                                                </div>
                                                                <?php
                                                                $estado = strtolower(trim($j['Juicio'] ?? ''));
                                                                $clase = 'status-pending';
                                                                $texto = 'Por Evaluar';
                                                                $icono = 'fa-clock';
                                                                if ($estado === 'aprobado') {
                                                                    $clase = 'status-approved';
                                                                    $texto = 'Aprobado';
                                                                    $icono = 'fa-check-circle';
                                                                } elseif ($estado === 'no aprobado') {
                                                                    $clase = 'status-rejected';
                                                                    $texto = 'No Aprobado';
                                                                    $icono = 'fa-times-circle';
                                                                }
                                                                ?>
                                                                <div class="result-status <?= $clase ?>">
                                                                    <i class="fas <?= $icono ?>"></i>
                                                                    <?= $texto ?>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <div class="student-info-section">
        <div class="card">
            <div class="card-header">
                <span class="card-title">ESTADO DEL APRENDIZ</span>
            </div>
            <div class="card-content open">
                <div class="info-grid">
    <div class="info-item">
                <strong>Estado:</strong> 
               <span class="badge badge-<?= strtolower($estado_formacion) === 'activo' ? 'success' : 'secondary' ?>">
                    <?= htmlspecialchars($estado_formacion) ?>
                </span>
            </div>
            <div class="info-item">
                <strong>Documento:</strong> <?= !empty($aprendiz['N_Documento']) ? htmlspecialchars($aprendiz['N_Documento']) : 'N/A' ?>
            </div>
            <strong>Ficha:</strong> <?= htmlspecialchars($competencias_agrupadas[array_key_first($competencias_agrupadas)][0]['Numero_ficha'] ?? 'N/A')  ?>
            <div class="info-item">
                <strong>Última actualización:</strong> 
                <?= !empty($aprendiz['Fecha_registro']) ? date('d/m/Y H:i', strtotime($aprendiz['Fecha_registro'])) : 'N/A' ?>
            </div>
        </div>

                <?php
                $total_competencias = is_array($competencias_agrupadas) ? count($competencias_agrupadas) : 0;
                $total_resultados = 0;
                $aprobados_total = 0;
                $rechazados_total = 0;
                $pendientes_total = 0;

                $stats_por_categoria = [
                    'COMPETENCIAS TÉCNICAS' => ['total' => 0, 'aprobados' => 0],
                    'COMPETENCIAS TRANSVERSALES' => ['total' => 0, 'aprobados' => 0]
                ];

                foreach ($competencias_agrupadas as $competencia => $juicios) {
                    $primer_juicio = $juicios[0];
                    $categorizacion = categorizarCompetencia($competencia, $primer_juicio['Resultado_aprendizaje'] ?? '');
                    $categoria_stat = $categorizacion['categoria'];

                    foreach ($juicios as $juicio) {
                        $estado = strtolower(trim($juicio['Juicio'] ?? ''));
                        $total_resultados++;
                        $stats_por_categoria[$categoria_stat]['total']++;
                        if ($estado === 'aprobado') {
                            $aprobados_total++;
                            $stats_por_categoria[$categoria_stat]['aprobados']++;
                        } elseif ($estado === 'no aprobado') {
                            $rechazados_total++;
                        } elseif ($estado === 'por evaluar' || $estado === '') {
                            $pendientes_total++;
                        }
                    }
                }
                ?>
                <h6>Resumen de Competencias</h6>
                <div class="stats-grid">
                    <div class="stat-item"><span class="stat-number"><?= $total_competencias ?></span><span class="stat-label">Competencias</span></div>
                    <div class="stat-item"><span class="stat-number text-success"><?= $aprobados_total ?></span><span class="stat-label">Aprobados</span></div>
                    <div class="stat-item"><span class="stat-number text-danger"><?= $rechazados_total ?></span><span class="stat-label">No Aprobados</span></div>
                    <div class="stat-item"><span class="stat-number text-warning"><?= $pendientes_total ?></span><span class="stat-label">Por Evaluar</span></div>
                </div>
                <div class="category-stats">
                    <h6>Por Categoría</h6>
                    <div class="category-stats-grid">
                        <?php foreach (['COMPETENCIAS TÉCNICAS', 'COMPETENCIAS TRANSVERSALES'] as $cat_nombre): ?>
                            <div class="category-stat-item">
                                <div class="category-stat-header"><?= htmlspecialchars($cat_nombre) ?></div>
                                <div class="category-stat-numbers">
                                    <span class="text-success"><?= $stats_por_categoria[$cat_nombre]['aprobados'] ?></span> /
                                    <span><?= $stats_por_categoria[$cat_nombre]['total'] ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<div id="modalReporte" class="modal">
    <div class="modal-contenido">
        <span class="cerrar-modal" onclick="cerrarModal()">&times;</span>
        <h2>Reporte de Aprendiz</h2>

        <p><strong>Nombre:</strong> <?= htmlspecialchars($aprendiz['Nombre_aprendiz'] ?? '') ?> <?= htmlspecialchars($aprendiz['Apellido_aprendiz'] ?? '') ?></p>
        <p><strong>Documento:</strong> <?= htmlspecialchars($aprendiz['N_Documento'] ?? '') ?></p>
        <p><strong>Ficha:</strong> <?= htmlspecialchars($aprendiz['Numero_ficha'] ?? '') ?></p>
        <p><strong>Estado:</strong> <?= htmlspecialchars($aprendiz['Estado_formacion'] ?? '') ?></p>

        <!-- Formulario para nueva observación (PRIMER CUADRO) -->
        <form id="formObservacion" style="margin-bottom: 20px;">
            <input type="hidden" name="id_aprendiz" value="<?= $idAprendiz ?>">
            <textarea name="observacion" placeholder="Escribe una nueva observación..." rows="4" style="width:100%; resize:none;" maxlength="500" required></textarea>
            <div style="margin-top: 10px; text-align:right;">
                <button type="submit" class="btn btn-primary">Guardar observación</button>
            </div>
        </form>

        <!-- Historial de observaciones (SEGUNDO CUADRO) -->
        <h4>Historial de Observaciones</h4>
        <?php
        $observaciones = [];
        if (!empty($idAprendiz)) {
            // Traemos también el id de la observación y alias para nombre de usuario
                $stmt = $conn->prepare("SELECT o.id, o.observacion, o.fecha, 
                                               u.Nombre AS usuario_nombre, u.Apellido AS usuario_apellido
                                        FROM observaciones_aprendiz o 
                                        JOIN usuarios u ON o.id_usuario = u.Id_usuario 
                                        WHERE o.id_aprendiz = ?
                                        ORDER BY o.fecha DESC");

            $stmt->bind_param("i", $idAprendiz);
            $stmt->execute();
            $result = $stmt->get_result();
            $observaciones = $result->fetch_all(MYSQLI_ASSOC);
        }
        ?>

        <?php if (!empty($observaciones)): ?>
            <ul id="historialObservaciones" style="max-height: 200px; overflow-y: auto; list-style:none; padding:0;">
                <?php foreach ($observaciones as $obs): ?>
                    <li style="padding:8px 0;">
                        <strong><?= htmlspecialchars(($obs['usuario_nombre'] ?? $obs['Nombre'] ?? '') . ' ' . ($obs['usuario_apellido'] ?? $obs['Apellido'] ?? '')) ?></strong>
                        <em>(<?= $obs['fecha'] ?>):</em><br>
                        <span id="texto-<?= $obs['id'] ?>"><?= nl2br(htmlspecialchars($obs['observacion'])) ?></span>
                        <br>
                        <!-- guardamos el texto en base64 para evitar problemas con comillas/newlines en atributos -->
<?php if (isset($_SESSION['usuario']['rol']) && strtolower($_SESSION['usuario']['rol']) === 'administrador'): ?>
    <button 
        class="btn btn-sm btn-warning btn-editar" 
        data-id="<?= $obs['id'] ?>" 
        data-texto="<?= base64_encode($obs['observacion']) ?>">
        ✏ Editar
    </button>
<?php endif; ?>


                            
                                    
                    </li>
                    <hr>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p id="historialObservaciones">No hay observaciones registradas.</p>
        <?php endif; ?>

    </div>
</div>


</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/competencias.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.getElementById('formObservacion').addEventListener('submit', function (e) {
    e.preventDefault();

    const form = this;
    const formData = new FormData(form);

    fetch('functions/guardar_observacion.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(response => {
        if (response.success) {
            Swal.fire({
                icon: 'success',
                title: 'Observación guardada',
                text: 'La observación se ha guardado exitosamente.',
                confirmButtonText: 'OK'
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: response.message || 'No se pudo guardar la observación.',
            });
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error inesperado al guardar la observación.',
        });
    });
});

function b64DecodeUnicode(str) {
    try {
        return decodeURIComponent(Array.prototype.map.call(atob(str), function(c) {
            return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
        }).join(''));
    } catch (e) {
        // fallback (may break with Unicode chars)
        try { return atob(str); } catch (e2) { return ''; }
    }
}

document.querySelectorAll('.btn-editar').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.dataset.id;
        const textoB64 = btn.dataset.texto || '';
        const texto = textoB64 ? b64DecodeUnicode(textoB64) : '';

        Swal.fire({
            title: 'Editar observación',
            input: 'textarea',
            inputValue: texto,
            showCancelButton: true,
            confirmButtonText: 'Guardar',
            cancelButtonText: 'Cancelar',
            preConfirm: (value) => {
                if (!value || value.trim() === '') {
                    Swal.showValidationMessage('El texto no puede estar vacío');
                }
                return value;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('functions/editar_observacion.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${encodeURIComponent(id)}&texto=${encodeURIComponent(result.value)}`
                })
                .then(res => res.json())
                .then(response => {
                    if (response.success) {
                        Swal.fire('Éxito', response.message, 'success').then(() => {

                            const span = document.getElementById('texto-' + id);
                            if (span) {
                                span.innerHTML = response.updated_html ?? (result.value.replace(/\n/g, '<br>'));
                            }
                        });
                    } else {
                        Swal.fire('Error', response.message || 'No se pudo editar la observación', 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire('Error', 'Hubo un error al editar la observación', 'error');
                });
            }
        });
    });
});
</script>

</body>
</html>
