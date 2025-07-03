<?php
if (!ACCESO_PERMITIDO){
    header("Location: /proyecto-sena/components/principales/login.php");
}
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../functions/functions_agrupar_competencias.php';

$documento = $_GET['doc'] ?? null;

if (!$documento) {
    echo "<p style='color:red;'>Documento no válido.</p>";
    exit;
}

// Buscar juicios del aprendiz
$sql = "
    SELECT *
    FROM juicios_evaluativos
    WHERE N_Documento = ?
    ORDER BY Competencia, Resultado_aprendizaje, Fecha_registro DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $documento);
$stmt->execute();
$resultado = $stmt->get_result();

$aprendiz = $resultado->fetch_assoc();

if (!$aprendiz) {
    echo "<p style='color:red;'>No hay competencias registradas para este aprendiz.</p>";
    exit;
}

$resultado->data_seek(0);
list($competencias_agrupadas, $materias_organizadas) = agruparCompetencias($resultado);
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
    <title>Juicios de <?= htmlspecialchars($aprendiz['Nombre_aprendiz'] ?? '') ?></title>
</head>
<body>
<main class="main-content">
    <h1 class="page-title">Juicios Evaluativos de <?= htmlspecialchars($aprendiz['Nombre_aprendiz'] ?? '') ?> <?= htmlspecialchars($aprendiz['Apellido_aprendiz'] ?? '') ?></h1>

    <div class="search-section">
        <div class="search-container">
            <button class="btn-generate-report" onclick="abrirModal()">
                <i class="fas fa-file-alt"></i>
                GENERAR REPORTE
            </button>
            <input type="text" class="form-control observaciones-aprendiz" readonly
                value="<?= htmlspecialchars($aprendiz['Observaciones'] ?? 'Sin observaciones') ?>">
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
                                                        if ($estado === 'aprobado') {
                                                            $aprobados++;
                                                        }
                                                    }
                                                }
                                                ?>
                                                <span class="stats-badge"><?= $aprobados ?>/<?= $total_resultados ?></span>
                                                <i class="fas fa-chevron-down collapse-icon"></i>
                                            </div>
                                        </div>
                                        <div class="collapse subject-content" id="collapse-<?= md5($categoria . $materia) ?>">
                                            <div class="results-container">
                                                <div class="results-header">
                                                    <span>Resultados de Aprendizaje</span>
                                                    <span>Instructor</span>
                                                    <span>Estado</span>
                                                </div>
                                                <?php foreach ($competencias_materia as $juicios): ?>
                                                    <?php foreach ($juicios as $j): ?>
                                                        <div class="result-item" data-competencia="<?= strtolower(($j['Competencia'] ?? '') . ' ' . ($j['Resultado_aprendizaje'] ?? '')) ?>">
                                                            <div class="result-code">
                                                                <strong><?= htmlspecialchars($j['Competencia'] ?? '') ?></strong>
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
                        <span class="badge badge-<?= strtolower($aprendiz['Estado_formacion'] ?? '') === 'activo' ? 'success' : 'secondary' ?>">
                            <?= htmlspecialchars($aprendiz['Estado_formacion'] ?? 'N/A') ?>
                        </span>
                    </div>
                    <div class="info-item">
                        <strong>Documento:</strong> <?= htmlspecialchars($aprendiz['N_Documento'] ?? 'N/A') ?>
                    </div>
                    <div class="info-item">
                        <strong>Ficha:</strong> <?= htmlspecialchars($aprendiz['Numero_ficha'] ?? 'N/A') ?>
                    </div>
                    <div class="info-item">
                        <strong>Última actualización:</strong> <?= isset($aprendiz['Fecha_registro']) ? date('d/m/Y H:i', strtotime($aprendiz['Fecha_registro'])) : 'N/A' ?>
                    </div>
                </div>

                <!-- Resumen estadístico -->
                <div class="stats-summary">
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
                        <div class="stat-item">
                            <span class="stat-number"><?= $total_competencias ?></span>
                            <span class="stat-label">Competencias</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number text-success"><?= $aprobados_total ?></span>
                            <span class="stat-label">Aprobados</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number text-danger"><?= $rechazados_total ?></span>
                            <span class="stat-label">No Aprobados</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number text-warning"><?= $pendientes_total ?></span>
                            <span class="stat-label">Por Evaluar</span>
                        </div>
                    </div>

                    <div class="category-stats">
                        <h6>Por Categoría</h6>
                        <div class="category-stats-grid">
                            <?php foreach (['COMPETENCIAS TÉCNICAS', 'COMPETENCIAS TRANSVERSALES'] as $cat_nombre): ?>
                                <?php 
                                    $aprob = $stats_por_categoria[$cat_nombre]['aprobados'] ?? 0;
                                    $total = $stats_por_categoria[$cat_nombre]['total'] ?? 0;
                                ?>
                                <div class="category-stat-item">
                                    <div class="category-stat-header"><?= htmlspecialchars($cat_nombre) ?></div>
                                    <div class="category-stat-numbers">
                                        <span class="text-success"><?= $aprob ?></span> /
                                        <span><?= $total ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
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
            <p><strong>Observaciones:</strong></p>
            <textarea readonly rows="4" style="width:100%; resize:none;" maxlength="500"><?= htmlspecialchars($aprendiz['Observaciones'] ?? 'Sin observaciones') ?></textarea>
            <div style="margin-top: 20px; text-align:right;">
                <a href="generar_reporte.php?doc=<?= $documento ?>" target="_blank" class="btn btn-success">Descargar PDF</a>
            </div>
        </div>
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/competencias.js"></script>
</body>
</html>