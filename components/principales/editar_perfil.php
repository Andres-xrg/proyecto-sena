<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../db/conexion.php';

// Verificar si hay sesión activa
$usuario = $_SESSION['usuario'] ?? null;

if (!$usuario) {
    echo "Error: No has iniciado sesión.";
    exit;
}

$id_usuario = $usuario['id'];

$sql = "SELECT * FROM usuarios WHERE Id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$datos = $result->fetch_assoc();

if (!$datos) {
    echo "Usuario no encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil de Usuario</title>
    <link rel="stylesheet" href="assets/css/editar_perfil.css">
    <link rel="stylesheet" href="assets/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h2>Editar Perfil</h2>
    <form class="form-edit" action="../../functions/procesar_editar_perfil_usuario.php" method="POST">
        <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">

        <div class="mb-3">
            <label>Nombre:</label>
            <input type="text" class="form-control" name="nombre" value="<?= $datos['nombre'] ?>" required>
        </div>

        <div class="mb-3">
            <label>Apellido:</label>
            <input type="text" class="form-control" name="apellido" value="<?= $datos['apellido'] ?? '' ?>" required>
        </div>

        <div class="mb-3">
            <label>Nº Teléfono:</label>
            <input type="text" class="form-control" name="N_telefono" value="<?= $datos['N_telefono'] ?? '' ?>" required>
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