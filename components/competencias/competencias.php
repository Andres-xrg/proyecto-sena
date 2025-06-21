<?php
require_once __DIR__ . '/../../db/conexion.php';

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
    ORDER BY Fecha_registro DESC";
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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="assets/css/competencias.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juicios de <?= htmlspecialchars($aprendiz['Nombre_aprendiz']) ?></title>
</head>
<body>
<main class="main-content">
    <h1 class="page-title">Juicios Evaluativos de <?= htmlspecialchars($aprendiz['Nombre_aprendiz']) ?> <?= htmlspecialchars($aprendiz['Apellido_aprendiz']) ?></h1>

    <div class="content-grid">
        <div class="left-column">
            <div class="card">
                <div class="card-header">
                    <span class="card-title">COMPETENCIAS</span>
                </div>
                <div class="card-content open" id="content-competencias">
                    <?php while ($j = $resultado->fetch_assoc()): ?>
                        <div class="result-item">
                            <span class="result-code"><?= htmlspecialchars($j['Competencia']) ?> - <?= htmlspecialchars($j['Resultado_aprendizaje']) ?></span>
                            <span class="result-instructor"><?= htmlspecialchars($j['Funcionario_registro']) ?></span>
                            <?php
                                $estado = strtolower($j['Juicio']);
                                $clase = 'status-pending';
                                if ($estado === 'cumple') $clase = 'status-approved';
                                elseif ($estado === 'no cumple') $clase = 'status-rejected';
                            ?>
                            <span class="result-status <?= $clase ?>">
                                <?= ($estado === 'cumple') ? 'Aprueba' : (($estado === 'no cumple') ? 'No Aprueba' : 'Por Evaluar') ?>
                            </span>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>

        <div class="right-column">
            <div class="card">
                <div class="card-header">
                    <span class="card-title">ESTADO DEL APRENDIZ</span>
                </div>
                <div class="card-content open">
                    <p><strong>Estado:</strong> <?= htmlspecialchars($aprendiz['Estado_formacion']) ?></p>
                    <p><strong>Documento:</strong> <?= htmlspecialchars($aprendiz['N_Documento']) ?></p>
                    <p><strong>Última actualización:</strong> <?= htmlspecialchars($aprendiz['Fecha_registro']) ?></p>
                </div>
            </div>
        </div>
    </div>
</main>
</body>
</html>