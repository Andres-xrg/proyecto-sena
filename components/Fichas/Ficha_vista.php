<?php
require_once 'db/conexion.php';

$id_ficha = $_GET['id'] ?? null;

if (!$id_ficha || !is_numeric($id_ficha)) {
    echo "<p style='color:red;'>⚠️ No se ha especificado una ficha válida.</p>";
    exit;
}

// Obtener la ficha
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

// Obtener aprendices
$sql_aprendices = "
    SELECT a.*, u.Email, u.N_Telefono
    FROM ficha_aprendiz fa
    JOIN aprendices a ON fa.Id_aprendiz = a.Id_aprendiz
    JOIN usuarios u ON a.Id_usuario = u.Id_usuario
    WHERE fa.Id_ficha = ?";
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
</head>
<body>
    <div class="container">
        <h1>Ficha N° <?= htmlspecialchars($ficha['numero_ficha']) ?></h1>
        <p><strong>Programa:</strong> <?= htmlspecialchars($ficha['programa_formación']) ?></p>
        <p><strong>Jornada:</strong> <?= htmlspecialchars($ficha['Jornada']) ?></p>
        <p><strong>Horas Totales:</strong> <?= htmlspecialchars($ficha['Horas_Totales']) ?></p>

        <h2>Aprendices</h2>
        <?php if ($aprendices->num_rows > 0): ?>
            <?php while ($aprendiz = $aprendices->fetch_assoc()): ?>
                <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
                    <strong><?= htmlspecialchars($aprendiz['Nombre']) ?> <?= htmlspecialchars($aprendiz['Apellido']) ?></strong><br>
                    Documento: <?= htmlspecialchars($aprendiz['T_documento']) ?> - <?= htmlspecialchars($aprendiz['N_documento']) ?><br>
                    Correo: <?= htmlspecialchars($aprendiz['Email']) ?><br>
                    Teléfono: <?= htmlspecialchars($aprendiz['N_Telefono']) ?><br>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No hay aprendices asociados a esta ficha.</p>
        <?php endif; ?>
    </div>
</body>
</html>
