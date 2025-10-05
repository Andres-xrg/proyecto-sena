<?php
session_start();
require_once '../db/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_POST['id_usuario'] ?? null;
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $N_Telefono = trim($_POST['N_telefono'] ?? '');
    $Email = trim($_POST['Email'] ?? '');
    $nueva_contrasena = $_POST['nueva_contrasena'] ?? '';
    $confirmar_contrasena = $_POST['confirmar_contrasena'] ?? '';

    if (!$id_usuario) {
        header("Location: /proyecto-sena/index.php?page=components/principales/editar_perfil&success=0&error=" . urlencode("Error: Usuario no válido."));
        exit;
    }

    // 🔹 Validaciones de nombre y apellido (solo letras y espacios)
    if (!preg_match("/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/", $nombre)) {
        header("Location: /proyecto-sena/index.php?page=components/principales/editar_perfil&success=0&error=" . urlencode("El nombre solo puede contener letras."));
        exit;
    }

    if (!preg_match("/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/", $apellido)) {
        header("Location: /proyecto-sena/index.php?page=components/principales/editar_perfil&success=0&error=" . urlencode("El apellido solo puede contener letras."));
        exit;
    }

    // 🔹 Validación de teléfono (solo números positivos)
    if (!preg_match("/^[0-9]+$/", $N_Telefono) || $N_Telefono < 0) {
        header("Location: /proyecto-sena/index.php?page=components/principales/editar_perfil&success=0&error=" . urlencode("El teléfono solo puede contener números positivos."));
        exit;
    }

    // 🔹 Validación de correo (formato válido)
    if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        header("Location: /proyecto-sena/index.php?page=components/principales/editar_perfil&success=0&error=" . urlencode("El correo ingresado no es válido."));
        exit;
    }

    // 1. Actualizar datos básicos del usuario
    $sql_update = "UPDATE usuarios 
                   SET nombre = ?, apellido = ?, N_Telefono = ?, Email = ? 
                   WHERE Id_usuario = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("ssssi", $nombre, $apellido, $N_Telefono, $Email, $id_usuario);
    $stmt->execute();

    // 2. Actualizar contraseña si se proporciona
    if (!empty($nueva_contrasena)) {
        if ($nueva_contrasena === $confirmar_contrasena) {
            $hash = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
            $stmt_pass = $conn->prepare("UPDATE usuarios SET Contraseña = ? WHERE Id_usuario = ?");
            $stmt_pass->bind_param("si", $hash, $id_usuario);
            $stmt_pass->execute();
        } else {
            header("Location: /proyecto-sena/index.php?page=components/principales/editar_perfil&success=0&error=" . urlencode("Las contraseñas no coinciden."));
            exit;
        }
    }

    // 3. Actualizar datos en sesión
    $_SESSION['usuario']['nombre'] = $nombre;
    $_SESSION['usuario']['apellido'] = $apellido;
    $_SESSION['usuario']['Email'] = $Email;
    $_SESSION['usuario']['N_telefono'] = $N_Telefono;

    // 4. Redirigir con éxito
    header("Location: /proyecto-sena/index.php?page=components/principales/editar_perfil&success=1");
    exit;
} else {
    header("Location: /proyecto-sena/index.php?page=components/principales/editar_perfil&success=0&error=" . urlencode("Acceso no permitido."));
    exit;
}
