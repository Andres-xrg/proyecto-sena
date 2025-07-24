<?php
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../functions/functions_porcentaje_competencia.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$id_ficha = $_GET['id'] ?? null;

if (!$id_ficha || !is_numeric($id_ficha)) {
    echo "<p style='color:red;'> No se ha especificado una ficha válida.</p>";
    exit;
}

// Obtener ficha con nombre del programa
$sql = "SELECT f.*, p.nombre_programa 
        FROM fichas f
        JOIN programas_formacion p ON f.Id_programa = p.Id_programa
        WHERE f.Id_ficha = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_ficha);
$stmt->execute();
$ficha = $stmt->get_result()->fetch_assoc();

if (!$ficha) {
    echo "<p style='color:red;'> No se encontró la ficha con ID $id_ficha.</p>";
    exit;
}

// Obtener aprendices
$sql_aprendices = "
    SELECT a.*
    FROM ficha_aprendiz fa
    JOIN aprendices a ON fa.Id_aprendiz = a.Id_aprendiz
    WHERE fa.Id_ficha = ?
    ORDER BY CAST(a.N_Documento AS UNSIGNED) ASC
";
$stmt2 = $conn->prepare($sql_aprendices);
$stmt2->bind_param("i", $id_ficha);
$stmt2->execute();
$aprendices = $stmt2->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ficha <?= htmlspecialchars($ficha['numero_ficha']) ?></title>
    <link rel="stylesheet" href="assets/css/fichas.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <script src="https://kit.fontawesome.com/your-kit-code.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
    <div class="main-card">
        <h1 class="header-title">Ficha N° <?= htmlspecialchars($ficha['numero_ficha']) ?></h1>

        <div class="form-controls">
            <div class="form-group">
                <label>Programa:</label>
                <p><?= htmlspecialchars($ficha['nombre_programa']) ?></p>
            </div>
            <div class="form-group">
                <label>Jornada:</label>
                <p><?= htmlspecialchars($ficha['Jornada']) ?></p>
            </div>
            <div class="form-group">
                <div class="search-box">
                    <input type="text" placeholder="Buscar..." id="searchInput">
                </div>
            </div>
            
            <div class="form-group">
                <?php if (isset($_SESSION['usuario']) && strtolower($_SESSION['usuario']['rol']) === 'administrador'): ?>
                    <form class="update-form" action="functions/functions_actualizar_juicios.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id_ficha" value="<?= $id_ficha ?>">
                        <input type="hidden" name="numero_ficha" value="<?= htmlspecialchars($ficha['numero_ficha']) ?>">
                        <input type="hidden" name="programa" value="<?= htmlspecialchars($ficha['nombre_programa']) ?>">
                        <input type="file" name="juicios" accept=".xlsx, .xls">
                        <button type="submit" class="btn-actualizar-juicios">
                            <i class="fas fa-upload"></i> Actualizar Juicios
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <h2 class="header-title">Aprendices</h2>

        <div class="students-list">
            <?php 
            $documentos_vistos = [];

            while ($a = $aprendices->fetch_assoc()):
                if (in_array($a['N_Documento'], $documentos_vistos)) continue;
                $documentos_vistos[] = $a['N_Documento'];

                $estado_stmt = $conn->prepare("SELECT Estado_formacion FROM juicios_evaluativos WHERE N_Documento = ? ORDER BY Fecha_registro DESC LIMIT 1");
                $estado_stmt->bind_param("s", $a['N_Documento']);
                $estado_stmt->execute();
                $estado_data = $estado_stmt->get_result()->fetch_assoc();
                $estado = strtolower($estado_data['Estado_formacion'] ?? 'sin estado');

                $badge_color = 'badge-gray';
                if ($estado === 'en formación') $badge_color = 'badge-green';
                elseif ($estado === 'trasladado') $badge_color = 'badge-blue';
                elseif ($estado === 'desertado') $badge_color = 'badge-red';

                $datos = obtener_porcentaje_aprobadas($a['N_Documento']);
                $porcentaje = $datos['porcentaje'];

                $color_barra = '#e53935';
                if ($porcentaje >= 70) $color_barra = '#2a7f00';
                elseif ($porcentaje >= 50) $color_barra = '#fbc02d';

                $tipo_doc = strtoupper($a['T_documento']);
            ?>
                <div class="student-card">
                    <div class="student-content">
                        <div class="avatar"><?= strtoupper(substr($a['nombre'], 0, 1)) ?></div>
                        <div class="student-info">
                            <div class="student-header">
                                <span class="student-name"><?= htmlspecialchars($a['nombre']) ?> <?= htmlspecialchars($a['apellido']) ?></span>
                                <div class="badges">
                                    <span class="badge <?= $badge_color ?>"><?= ucfirst($estado) ?></span>
                                </div>
                            </div>
                            <div class="student-details">
                                <div class="detail-item">
                                    <label>Documento</label>
                                    <p><?= htmlspecialchars($tipo_doc) ?> - <?= htmlspecialchars($a['N_Documento']) ?></p>
                                </div>
                                <div class="detail-item">
                                    <label>Correo</label>
                                    <p class="email"><?= htmlspecialchars($a['Email']) ?></p>
                                </div>
                                <div class="detail-item">
                                    <a class="percentage-btn" href="index.php?page=components/competencias/competencias&doc=<?= urlencode($a['N_Documento']) ?>">Ver Competencias</a>
                                </div>
                            </div>

                            <div class="detail-item" style="margin-top: 1rem;">
                                <label>Progreso de competencias aprobadas</label>
                                <div class="progress-bar" style="background: #eee; border-radius: 8px; overflow: hidden; height: 20px; width: 100%;">
                                    <div style="width: <?= $porcentaje ?>%; background: <?= $color_barra ?>; height: 100%; text-align: center; color: white; font-size: 0.8rem;">
                                        <?= $porcentaje ?>%
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endwhile; ?>

            <?php if ($aprendices->num_rows === 0): ?>
                <div class="empty-content">No hay aprendices registrados en esta ficha.</div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
<script src="/proyecto-sena/assets/js/fichas.js"></script>
</html>
