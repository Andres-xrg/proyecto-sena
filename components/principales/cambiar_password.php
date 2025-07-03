<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}
?>

<?php include '../../includes/header.php'; ?>

<div class="container mt-4">
    <h2>Cambiar contraseña</h2>
    <form action="../../functions/procesar_cambio_password.php" method="POST">
        <div class="form-group">
            <label>Contraseña actual</label>
            <input type="password" name="actual" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Nueva contraseña</label>
            <input type="password" name="nueva" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Confirmar nueva contraseña</label>
            <input type="password" name="confirmar" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Actualizar contraseña</button>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>
