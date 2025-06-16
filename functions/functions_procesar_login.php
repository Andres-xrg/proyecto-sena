<?php
session_start();
require_once("../db/conexion.php");
require_once("../functions/historial.php"); // 👈 Asegúrate de que esta ruta sea válida

$email    = trim($_POST['email'] ?? '');
$password = trim($_POST['contraseña'] ?? '');

// Validación básica
if (empty($email) || empty($password)) {
    header("Location: /proyecto-sena/components/principales/login.php?status=1");
    exit;
}

// Buscar usuario por correo
$sql = "SELECT * FROM usuarios WHERE Email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $usuario = $result->fetch_assoc();

    // Verificar la contraseña
    if (password_verify($password, $usuario['Contraseña'])) {

        // ✅ Guardar datos del usuario en sesión correctamente
        $_SESSION['usuario'] = [
            'id'     => $usuario['Id_usuario'],  // ← importante para historial
            'email'  => $usuario['Email'],
            'nombre' => $usuario['nombre']
        ];

        // ✅ Registrar historial
        registrar_historial($conn, $usuario['Id_usuario'], 'Login', "El usuario inició sesión correctamente.");

        header("Location: /proyecto-sena/index.php?page=components/principales/welcome");
        exit;
    }
}

// ❌ Login fallido
header("Location: /proyecto-sena/components/principales/login.php?status=1");
exit;
?>
