<!-- components/principales/reset_password.php -->
<?php
require_once '../../db/conexion.php';

$token = $_GET['token'] ?? '';
$valido = false;

// Verificar si el token existe y no ha expirado
if (!empty($token)) {
    $stmt = $conn->prepare("SELECT Id_usuario FROM usuarios WHERE token_recuperacion = ? AND token_expiracion > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $valido = true;
    }
}
?>
<link rel="stylesheet" href="../../assets/css/reset_password.css">
<div class="container">
    <?php if ($valido): ?>
        <h2>Restablecer contraseña</h2>
        <form action="../../functions/actualizar_password.php" method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <div class="form-group">
                <label>Nueva contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Confirmar contraseña</label>
                <input type="password" name="confirmar" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Actualizar contraseña</button>
        </form>
    <?php else: ?>
        <p>Token inválido o expirado.</p>
    <?php endif; ?>
</div>
