<?php
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

// Volver al inicio del resultado 
$resultado->data_seek(0);

// Agrupar y categorizar competencias usando la función externa
list($competencias_agrupadas, $materias_organizadas) = agruparCompetencias($resultado);
?>
<!-- PHP -->

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
    
    <!-- Barra de búsqueda -->
    <div class="search-section">
        <div class="search-container">
            <div class="search-input-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" placeholder="Buscar competencias..." id="searchInput">
            </div>
            <button class="btn-generate-report" onclick="generarReporte()">
                <i class="fas fa-file-alt"></i>
                GENERAR REPORTE
            </button>
        </div>
    </div>

    <div class="content-grid">
        <?php foreach ($materias_organizadas as $categoria => $materias_categoria): ?>
        <div class="category-column">
            <div class="card category-card">
                <div class="card-header category-header">
                    <span class="card-title"><?= htmlspecialchars($categoria) ?></span>
                </div>
                <div class="card-content category-content">
                    <?php if (is_array($materias_categoria) && !empty($materias_categoria)): ?>
                        <?php foreach ($materias_categoria as $materia => $competencias_materia): ?>
                            <?php if (is_array($competencias_materia) && !empty($competencias_materia)): ?>
                            <div class="subject-section">
                                <div class="subject-header" data-bs-toggle="collapse" data-bs-target="#collapse-<?= md5($categoria . $materia) ?>" aria-expanded="false">
                                    <span class="subject-title"><?= htmlspecialchars($materia) ?></span>
                                    <div class="subject-stats">
                                        <?php
                                        $total_resultados = 0;
                                        $aprobados = 0;
                                        foreach ($competencias_materia as $competencia => $juicios) {
                                            if (is_array($juicios)) {
                                                foreach ($juicios as $juicio) {
                                                    if (is_array($juicio)) {
                                                        $total_resultados++;
                                                        if (isset($juicio['Juicio']) && strtolower($juicio['Juicio']) === 'cumple') {
                                                            $aprobados++;
                                                        }
                                                    }
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
                                        <?php foreach ($competencias_materia as $competencia => $juicios): ?>
                                            <?php if (is_array($juicios)): ?>
                                                <?php foreach ($juicios as $j): ?>
                                                    <?php if (is_array($j)): ?>
                                                    <div class="result-item" data-competencia="<?= strtolower(($j['Competencia'] ?? '') . ' ' . ($j['Resultado_aprendizaje'] ?? '')) ?>">
                                                        <div class="result-code">
                                                            <strong><?= htmlspecialchars($j['Competencia'] ?? '') ?></strong>
                                                            <div class="result-description">
                                                                <?= htmlspecialchars($j['Resultado_aprendizaje'] ?? '') ?>
                                                            </div>
                                                            <div class="result-date">
                                                                <small><i class="fas fa-calendar"></i> <?= isset($j['Fecha_registro']) ? date('d/m/Y', strtotime($j['Fecha_registro'])) : 'N/A' ?></small>
                                                            </div>
                                                        </div>
                                                        <div class="result-instructor">
                                                            <i class="fas fa-user"></i>
                                                            <?= htmlspecialchars($j['Funcionario_registro'] ?? 'N/A') ?>
                                                        </div>
                                                        <?php
                                                            $estado = strtolower($j['Juicio'] ?? '');
                                                            $clase = 'status-pending';
                                                            $texto = 'Por Evaluar';
                                                            $icono = 'fa-clock';
                                                            if ($estado === 'cumple') {
                                                                $clase = 'status-approved';
                                                                $texto = 'Aprueba';
                                                                $icono = 'fa-check-circle';
                                                            } elseif ($estado === 'no cumple') {
                                                                $clase = 'status-rejected';
                                                                $texto = 'No Aprueba';
                                                                $icono = 'fa-times-circle';
                                                            }
                                                        ?>
                                                        <div class="result-status <?= $clase ?>">
                                                            <i class="fas <?= $icono ?>"></i>
                                                            <?= $texto ?>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Mostrar mensaje cuando no hay competencias en esta categoría -->
                        <div class="empty-category">
                            <div class="empty-icon">
                                <i class="fas fa-inbox"></i>
                            </div>
                            <div class="empty-message">
                                <h6>Sin competencias registradas</h6>
                                <p>No se encontraron competencias de tipo "<?= htmlspecialchars($categoria) ?>" para este aprendiz.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Información del aprendiz -->
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
                    
                    // Contar por categoría
                    $stats_por_categoria = [
                        'COMPETENCIAS' => ['total' => 0, 'aprobados' => 0],
                        'COMPETENCIAS TRANSVERSALES' => ['total' => 0, 'aprobados' => 0]
                    ];
                    
                    if (is_array($competencias_agrupadas)) {
                        foreach ($competencias_agrupadas as $competencia => $juicios) {
                            if (is_array($juicios)) {
                                $primer_juicio = $juicios[0];
                                if (is_array($primer_juicio)) {
                                    $categorizacion = categorizarCompetencia($competencia, $primer_juicio['Resultado_aprendizaje'] ?? '');
                                    $categoria_stat = $categorizacion['categoria'];
                                    
                                    foreach ($juicios as $juicio) {
                                        if (is_array($juicio)) {
                                            $total_resultados++;
                                            $stats_por_categoria[$categoria_stat]['total']++;
                                            
                                            $estado = strtolower($juicio['Juicio'] ?? '');
                                            if ($estado === 'cumple') {
                                                $aprobados_total++;
                                                $stats_por_categoria[$categoria_stat]['aprobados']++;
                                            } elseif ($estado === 'no cumple') {
                                                $rechazados_total++;
                                            } else {
                                                $pendientes_total++;
                                            }
                                        }
                                    }
                                }
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
                            <span class="stat-label">Rechazados</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number text-warning"><?= $pendientes_total ?></span>
                            <span class="stat-label">Pendientes</span>
                        </div>
                    </div>
                    
                    <!-- Estadísticas por categoría -->
                    <div class="category-stats">
                        <h6>Por Categoría</h6>
                        <div class="category-stats-grid">
                            <?php foreach ($stats_por_categoria as $cat_nombre => $cat_stats): ?>
                            <div class="category-stat-item">
                                <div class="category-stat-header"><?= htmlspecialchars($cat_nombre) ?></div>
                                <div class="category-stat-numbers">
                                    <span class="text-success"><?= $cat_stats['aprobados'] ?></span>
                                    /
                                    <span><?= $cat_stats['total'] ?></span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/competencias.js"></script>

</body>
</html>