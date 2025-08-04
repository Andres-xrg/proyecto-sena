<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../db/conexion.php';
$config = include(__DIR__ . '/../../functions/lang.php');

$lang_code = $config['lang_code'] ?? 'es';
$t = $config['translations'] ?? [];

// Verificar si el usuario ha iniciado sesión
$usuario = $_SESSION['usuario'] ?? null;

if (!$usuario || !isset($usuario['id'])) {
    echo $t['error_no_sesion'] ?? 'Error: No has iniciado sesión.';
    exit;
}

$id_usuario = (int) $usuario['id'];

// Consultar los datos del usuario
$sql = "SELECT nombre, apellido, N_telefono, Email FROM usuarios WHERE Id_usuario = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo $t['error_preparar_consulta'] ?? "Error al preparar la consulta.";
    exit;
}

$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$datos = $result->fetch_assoc();

if (!$datos) {
    echo $t['usuario_no_encontrado'] ?? "Usuario no encontrado.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $translations['edit_profile'] ?></title>
    <link rel="stylesheet" href="assets/css/editar_perfil.css">
    <link rel="stylesheet" href="assets/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h2 class="mb-4"><?= $translations['edit_profile'] ?></h2>

        <form class="form-edit" action="/proyecto-sena/functions/procesar_editar_perfil_usuario.php" method="POST">

        <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($id_usuario) ?>">

        <div class="mb-3">
            <label for="nombre"><?= $translations['name'] ?>:</label>
            <input type="text" id="nombre" class="form-control" name="nombre" value="<?= htmlspecialchars($datos['nombre']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="apellido"><?= $translations['lastname'] ?>:</label>
            <input type="text" id="apellido" class="form-control" name="apellido" value="<?= htmlspecialchars($datos['apellido'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="N_telefono"><?= $translations['phone'] ?>:</label>
            <input type="text" id="N_telefono" class="form-control" name="N_telefono" value="<?= htmlspecialchars($datos['N_telefono'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="Email"><?= $translations['email'] ?>:</label>
            <input type="email" id="Email" class="form-control" name="Email" value="<?= htmlspecialchars($datos['Email']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="nueva_contrasena"><?= $translations['new_password'] ?>:</label>
            <input type="password" id="nueva_contrasena" class="form-control" name="nueva_contrasena">
        </div>

        <div class="mb-3">
            <label for="confirmar_contrasena"><?= $translations['confirm_password'] ?>:</label>
            <input type="password" id="confirmar_contrasena" class="form-control" name="confirmar_contrasena">
        </div>

        <button type="submit" class="btn btn-primary"><?= $translations['save_changes'] ?></button>
    </form>
</body>
</html>
