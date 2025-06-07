<?php
session_start();
require_once("../db/conexion.php");

$email = trim($_POST['email'] ?? '');
$password = trim($_POST['contraseña'] ?? '');


// if (!file_exists(__FILE__)) {
//     die("⚠️ El archivo actual no se encuentra.");
// }

// Validación básica
if (empty($email) || empty($password)) {
    header("Location: /proyecto-sena/components/principales/login.php?status=1");
    exit;
}

// Buscar usuario por correo (no incluir la contraseña aquí)
$sql = "SELECT * FROM usuarios WHERE Email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $usuario = $result->fetch_assoc();

    // Verificar la contraseña encriptada
    if (password_verify($password, $usuario['Contraseña'])) {
        $_SESSION['usuario'] = $usuario['Email'];
        $_SESSION['nombre'] = $usuario['nombre']; // opcional
        
        header("Location: /proyecto-sena/index.php?page=components/principales/welcome");
        exit;
    } else {
        // Contraseña incorrecta
        header("Location: /proyecto-sena/components/principales/login.php?status=1");
        exit;
    }
} else {
    // Usuario no encontrado
    header("Location: /proyecto-sena/components/principales/login.php?status=1");
    exit;
}
?>
