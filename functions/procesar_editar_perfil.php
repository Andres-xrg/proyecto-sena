<?php
session_start();
require_once '../db/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_aprendiz = $_POST['id_aprendiz'];

    // Datos del formulario
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $T_documento = $_POST['T_documento'];
    $N_Documento = trim($_POST['N_Documento']);
    $N_Telefono = trim($_POST['N_Telefono']);
    $Email = trim($_POST['Email']);
    $nueva_contrasena = $_POST['nueva_contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];

    // 1. Actualizar tabla aprendices
    $sql = "UPDATE aprendices SET nombre = ?, apellido = ?, T_documento = ?, N_Documento = ?, N_Telefono = ?, Email = ? WHERE Id_aprendiz = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $nombre, $apellido, $T_documento, $N_Documento, $N_Telefono, $Email, $id_aprendiz);
    $stmt->execute();

    // 2. Obtener el Id_usuario vinculado
    $stmt_id = $conn->prepare("SELECT Id_usuario FROM aprendices WHERE Id_aprendiz = ?");
    $stmt_id->bind_param("i", $id_aprendiz);
    $stmt_id->execute();
    $result = $stmt_id->get_result();
    $fila = $result->fetch_assoc();
    $id_usuario = $fila['Id_usuario'];

    // 3. Actualizar tabla usuarios también
    $sql_update_user = "UPDATE usuarios SET nombre = ?, apellido = ?, T_Documento = ?, N_Documento = ?, N_Telefono = ?, Email = ? WHERE Id_usuario = ?";
    $stmt_user = $conn->prepare($sql_update_user);
    $stmt_user->bind_param("ssssssi", $nombre, $apellido, $T_documento, $N_Documento, $N_Telefono, $Email, $id_usuario);
    $stmt_user->execute();

    // 4. Actualizar contraseña si se solicita
    if (!empty($nueva_contrasena)) {
        if ($nueva_contrasena === $confirmar_contrasena) {
            $hash = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
            $stmt_pass = $conn->prepare("UPDATE usuarios SET Contraseña = ? WHERE Id_usuario = ?");
            $stmt_pass->bind_param("si", $hash, $id_usuario);
            $stmt_pass->execute();
        } else {
            echo "<script>alert('Las contraseñas no coinciden.'); history.back();</script>";
            exit;
        }
    }

    // 5. Actualizar nombre en la sesión
    $_SESSION['usuario']['nombre'] = $nombre;

    // 6. Redirigir al área de bienvenida
header("Location: /proyecto-sena/index.php?page=components/principales/welcome&success=1");
    exit;
} else {
    echo "Acceso no permitido.";
}
