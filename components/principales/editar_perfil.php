<?php
// if (!ACCESO_PERMITIDO){
//     header("Location: /proyecto-sena/components/principales/login.php");
// }
session_start();
require_once '../../db/conexion.php';

$id_aprendiz = $_SESSION['Id_aprendiz'] ?? null;

if (!$id_aprendiz) {
    echo "Error: No has iniciado sesión.";
    exit;
}
$sql = "SELECT a.*, u.contraseña FROM aprendices a 
        LEFT JOIN usuarios u ON a.Id_usuario = u.Id_usuario
        WHERE a.Id_aprendiz = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_aprendiz);
$stmt->execute();
$result = $stmt->get_result();
$datos = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="../../assets/css/editar_perfil.css">
    <link rel="stylesheet" href="../../assets/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h2>Editar Perfil</h2>
    <form action="../../functions/procesar_editar_perfil.php" method="POST">
        <input type="hidden" name="id_aprendiz" value="<?= $id_aprendiz ?>">

        <div class="mb-3">
            <label>Nombre:</label>
            <input type="text" class="form-control" name="nombre" value="<?= $datos['nombre'] ?>" required>
        </div>

        <div class="mb-3">
            <label>Apellido:</label>
            <input type="text" class="form-control" name="apellido" value="<?= $datos['apellido'] ?>" required>
        </div>

        <div class="mb-3">
            <label>Tipo de Documento:</label>
            <select name="T_documento" class="form-control" required>
                <option value="C.C" <?= $datos['T_documento'] == 'C.C' ? 'selected' : '' ?>>C.C</option>
                <option value="T.I" <?= $datos['T_documento'] == 'T.I' ? 'selected' : '' ?>>T.I</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Número de Documento:</label>
            <input type="text" class="form-control" name="N_Documento" value="<?= $datos['N_Documento'] ?>" required>
        </div>

        <div class="mb-3">
            <label>Teléfono:</label>
            <input type="text" class="form-control" name="N_Telefono" value="<?= $datos['N_Telefono'] ?>" required>
        </div>

        <div class="mb-3">
            <label>Email:</label>
            <input type="email" class="form-control" name="Email" value="<?= $datos['Email'] ?>" required>
        </div>

        <div class="mb-3">
            <label>Nueva contraseña (opcional):</label>
            <input type="password" class="form-control" name="nueva_contrasena">
        </div>

        <div class="mb-3">
            <label>Confirmar nueva contraseña:</label>
            <input type="password" class="form-control" name="confirmar_contrasena">
        </div>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>
</body>
</html>
