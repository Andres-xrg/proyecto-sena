<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../db/conexion.php';
$config = include(__DIR__ . '/../../functions/lang.php');
require_once __DIR__ . '/../../functions/autenticacion_login.php';

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
<html lang="<?= htmlspecialchars($lang_code) ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $t['edit_profile'] ?? 'Editar Perfil' ?></title>
    <link rel="stylesheet" href="assets/css/editar_perfil.css">
    <link rel="stylesheet" href="assets/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="container mt-5">
    <h2 class="mb-4"><?= $t['edit_profile'] ?? 'Editar Perfil' ?></h2>

    <form class="form-edit" action="/proyecto-sena/functions/procesar_editar_perfil.php" method="POST">

        <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($id_usuario) ?>">

        <div class="mb-3">
            <label for="nombre"><?= $t['name'] ?? 'Nombre' ?>:</label>
            <input type="text" id="nombre" class="form-control" 
                   name="nombre" value="<?= htmlspecialchars($datos['nombre']) ?>" 
                   required>
        </div>

        <div class="mb-3">
            <label for="apellido"><?= $t['lastname'] ?? 'Apellido' ?>:</label>
            <input type="text" id="apellido" class="form-control" 
                   name="apellido" value="<?= htmlspecialchars($datos['apellido'] ?? '') ?>" 
                   required>
        </div>

        <div class="mb-3">
            <label for="N_telefono"><?= $t['phone'] ?? 'Teléfono' ?>:</label>
            <input type="number" id="N_telefono" class="form-control" 
                   name="N_telefono" value="<?= htmlspecialchars($datos['N_telefono'] ?? '') ?>" 
                   required min="0" step="1">
        </div>

        <div class="mb-3">
            <label for="Email"><?= $t['email'] ?? 'Correo' ?>:</label>
            <input type="email" id="Email" class="form-control" 
                   name="Email" value="<?= htmlspecialchars($datos['Email']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="nueva_contrasena"><?= $t['new_password'] ?? 'Nueva Contraseña' ?>:</label>
            <input type="password" id="nueva_contrasena" class="form-control" name="nueva_contrasena">
        </div>

        <div class="mb-3">
            <label for="confirmar_contrasena"><?= $t['confirm_password'] ?? 'Confirmar Contraseña' ?>:</label>
            <input type="password" id="confirmar_contrasena" class="form-control" name="confirmar_contrasena">
        </div>

        <button type="submit" class="btn btn-primary"><?= $t['save_changes'] ?? 'Guardar Cambios' ?></button>
    </form>

    <?php if (isset($_GET['success'])): ?>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        <?php if ($_GET['success'] == 1): ?>
            Swal.fire({
                icon: "success",
                title: "✅ Perfil actualizado correctamente",
                showConfirmButton: false,
                timer: 2000
            });
        <?php else: ?>
            Swal.fire({
                icon: "error",
                title: "❌ Error al actualizar el perfil",
                confirmButtonText: "Ok"
            });
        <?php endif; ?>

        // Limpiar la URL
        if (window.history.replaceState) {
            const url = new URL(window.location.href);
            url.searchParams.delete("success");
            window.history.replaceState({}, document.title, url.toString());
        }
    });
    </script>
    <?php endif; ?>

<script>
// Validación en tiempo real
document.getElementById("N_telefono").addEventListener("input", function() {
    const valor = this.value;
    if (valor < 0) {
        Swal.fire({
            icon: "error",
            title: "Número inválido",
            text: "El número de teléfono no puede ser negativo."
        });
        this.value = "";
    }
});

document.getElementById("nombre").addEventListener("input", function() {
    this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, "");
});

document.getElementById("apellido").addEventListener("input", function() {
    this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, "");
});
</script>
</body>
</html>
