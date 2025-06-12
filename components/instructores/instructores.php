<?php
require_once(__DIR__ . "/../../db/conexion.php");

// Validar conexión
if (!$conn) {
    die("Error de conexión a la base de datos: " . mysqli_connect_error());
}

// Consulta directa a la tabla instructores
$sql = "SELECT 
            Id_instructor, 
            nombre, 
            apellido, 
            Email, 
            T_documento, 
            N_documento, 
            N_Telefono, 
            Ficha, 
            Tipo_instructor
        FROM instructores";

$resultado = $conn->query($sql);

if (!$resultado) {
    die("Error en la consulta SQL: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Instructores</title>
    <link rel="stylesheet" href="assets/css/instructores.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
</head>
<body>
    <?php
require_once(__DIR__ . "/../../db/conexion.php");

// Validar conexión
if (!$conn) {
    die("Error de conexión a la base de datos: " . mysqli_connect_error());
}

// Consulta directa a la tabla instructores
$sql = "SELECT 
            Id_instructor, 
            nombre, 
            apellido, 
            Email, 
            T_documento, 
            N_documento, 
            N_Telefono, 
            Ficha, 
            Tipo_instructor
        FROM instructores";

$resultado = $conn->query($sql);

if (!$resultado) {
    die("Error en la consulta SQL: " . $conn->error);
}
?>

<div class="container">
    <div class="titulo">
        <h1 class="title">Instructores</h1>

        <!-- ✅ Mostrar mensaje si se actualizó correctamente -->
        <?php if (isset($_GET['success']) && $_GET['success'] === 'estado-cambiado'): ?>
            <div style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-top: 10px;">
                ✅ Estado del instructor actualizado correctamente.
            </div>
        <?php endif; ?>
    </div>

    <div class="instructores-list">
        <?php 
        $contador = 1;
        if ($resultado->num_rows > 0): 
            while ($instructor = $resultado->fetch_assoc()):
                $activo = $instructor['Tipo_instructor'] !== 'Inactivo';
                $claseCard = $activo ? '' : 'disabled';
                $textoEstado = $activo ? 'Activo' : 'Inactivo';
                $textoBoton = $activo ? 'Deshabilitar' : 'Habilitar';
                $claseBoton = $activo ? 'btn-deshabilitar' : 'btn-habilitar';
                $jefeFicha = !empty($instructor['Ficha']) ? 'Sí' : 'No';
        ?>
            <!-- INSTRUCTOR <?= $contador++ ?> -->
            <div class="instructor-card <?= $claseCard ?>">
                <div class="instructor-content">
                    <div class="avatar">
                        <div class="avatar-icon">👤</div>
                    </div>
                    <div class="instructor-info">
                        <div class="instructor-header">
                            <h3 class="instructor-name">
                                <?= htmlspecialchars($instructor['nombre'] ?? '') . ' ' . htmlspecialchars($instructor['apellido'] ?? '') ?>
                            </h3>
                            <form method="POST" action="functions/functions_instructores.php" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $instructor['Id_instructor'] ?>">
                                <input type="hidden" name="accion" value="<?= $textoBoton ?>">
                                <button type="submit" class="btn-estado <?= $claseBoton ?>">
                                    <?= $textoBoton ?>
                                </button>
                            </form>
                        </div>
                        <div class="instructor-details">
                            <div class="detail-item"><label>T. Documento</label><span><?= htmlspecialchars($instructor['T_documento'] ?? 'N/A') ?></span></div>
                            <div class="detail-item"><label>Num. Documento</label><span><?= htmlspecialchars($instructor['N_documento'] ?? 'N/A') ?></span></div>
                            <div class="detail-item"><label>Correo Instructor</label><span><?= htmlspecialchars($instructor['Email'] ?? 'N/A') ?></span></div>
                            <div class="detail-item"><label>N° Teléfono</label><span><?= htmlspecialchars($instructor['N_Telefono'] ?? 'N/A') ?></span></div>
                            <div class="detail-item estado-item"><label>Estado</label><span><?= $textoEstado ?></span></div>
                            <div class="detail-item"><label>Jefe de ficha</label><span><?= $jefeFicha ?></span></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; else: ?>
            <p>No hay instructores registrados.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
