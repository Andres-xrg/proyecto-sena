<?php
require_once __DIR__ . '/../../db/conexion.php';

$id_ficha = $_GET['id'] ?? null;

if (!$id_ficha || !is_numeric($id_ficha)) {
    echo "<p style='color:red;'>⚠️ No se ha especificado una ficha válida.</p>";
    exit;
}

// Obtener ficha
$sql = "SELECT * FROM fichas WHERE Id_ficha = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_ficha);
$stmt->execute();
$resultado = $stmt->get_result();
$ficha = $resultado->fetch_assoc();

if (!$ficha) {
    echo "<p style='color:red;'>❌ No se encontró la ficha con ID $id_ficha.</p>";
    exit;
}

// Obtener aprendices de la ficha
$sql_aprendices = "
    SELECT a.*
    FROM ficha_aprendiz fa
    JOIN aprendices a ON fa.Id_aprendiz = a.Id_aprendiz
    WHERE fa.Id_ficha = ?";
$stmt2 = $conn->prepare($sql_aprendices);
$stmt2->bind_param("i", $id_ficha);
$stmt2->execute();
$aprendices = $stmt2->get_result();

// Obtener juicios por número de ficha
$sql_juicios = "
    SELECT *
    FROM juicios_evaluativos
    WHERE Numero_ficha = ?";
$stmt3 = $conn->prepare($sql_juicios);

if (!$stmt3) {
    echo "<p style='color:red;'>❌ Error preparando stmt de juicios: " . $conn->error . "</p>";
    exit;
}

$numero_ficha_consulta = $ficha['numero_ficha'];
$stmt3->bind_param("s", $numero_ficha_consulta);
$stmt3->execute();
$juicios = $stmt3->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ficha <?= htmlspecialchars($ficha['numero_ficha']) ?></title>
    <link rel="stylesheet" href="assets/css/fichas.css">
    <link rel="stylesheet" href="assets/css/footer.css">
</head>
<body>
    <div class="container">
        <h1>Ficha N° <?= htmlspecialchars($ficha['numero_ficha']) ?></h1>
        <p><strong>Programa:</strong> <?= htmlspecialchars($ficha['programa_formación']) ?></p>
        <p><strong>Jornada:</strong> <?= htmlspecialchars($ficha['Jornada']) ?></p>
        <p><strong>Horas Totales:</strong> <?= htmlspecialchars($ficha['Horas_Totales']) ?></p>

        <h2>Aprendices</h2>
        <?php if ($aprendices->num_rows > 0): ?>
            <?php while ($a = $aprendices->fetch_assoc()): ?>
                <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
                    <strong><?= htmlspecialchars($a['nombre'] ?? 'Sin nombre') ?> <?= htmlspecialchars($a['apellido'] ?? '') ?></strong><br>
                    Documento: <?= htmlspecialchars($a['T_documento'] ?? '-') ?> - <?= htmlspecialchars($a['N_Documento'] ?? '-') ?><br>
                    Correo: <?= htmlspecialchars($a['Email'] ?? 'No disponible') ?><br>
                    Teléfono: <?= htmlspecialchars($a['N_Telefono'] ?? 'No disponible') ?><br>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No hay aprendices asociados a esta ficha.</p>
        <?php endif; ?>

        <h2>Juicios Evaluativos (desde Excel)</h2>
        <p style="color:blue;"><strong>Ficha buscada:</strong> <?= htmlspecialchars($numero_ficha_consulta) ?></p>
        <p style="color:blue;"><strong>Total juicios encontrados:</strong> <?= $juicios->num_rows ?></p>

        <?php if ($juicios->num_rows > 0): ?>
            <?php while ($j = $juicios->fetch_assoc()): ?>
                <div style="border: 1px solid #ddd; padding: 10px; margin-bottom: 10px;">
                    <strong><?= htmlspecialchars($j['Nombre_aprendiz'] ?? '') ?> <?= htmlspecialchars($j['Apellido_aprendiz'] ?? '') ?></strong><br>
                    Documento: <?= htmlspecialchars($j['N_Documento']) ?><br>
                    Estado: <?= htmlspecialchars($j['Estado_formacion']) ?><br>
                    Competencia: <?= htmlspecialchars($j['Competencia']) ?><br>
                    Resultado de Aprendizaje: <?= htmlspecialchars($j['Resultado_aprendizaje']) ?><br>
                    Juicio: <strong><?= htmlspecialchars($j['Juicio']) ?></strong><br>
                    Funcionario que registró: <?= htmlspecialchars($j['Funcionario_registro'] ?? 'N/A') ?><br>
                    Fecha Registro: <?= htmlspecialchars($j['Fecha_registro']) ?><br>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No hay juicios evaluativos registrados para esta ficha.</p>
        <?php endif; ?>
    </div>
</body>
</html>
