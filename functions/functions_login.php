<?php
session_start();
require_once '../db/conexion.php';
require_once '../functions/historial.php';

$email = $_POST['email'];
$password = $_POST['contraseña'];

// Buscar el usuario por correo
$sql = "SELECT * FROM usuarios WHERE Email = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si existe
if ($result->num_rows === 1) {
    $usuario = $result->fetch_assoc();

    // Verificar contraseña
    if (password_verify($password, $usuario['Contraseña'])) {
        $_SESSION['usuario'] = [
            'id'     => $usuario['Id_usuario'],
            'nombre' => $usuario['nombre'],
            'email'  => $usuario['Email']
        ];

        // Buscar ID del aprendiz (si aplica)
        $sql_apr = "SELECT Id_aprendiz FROM aprendices WHERE Id_usuario = ?";
        $stmt_apr = $conn->prepare($sql_apr);
        $stmt_apr->bind_param("i", $usuario['Id_usuario']);
        $stmt_apr->execute();
        $result_apr = $stmt_apr->get_result();

        if ($result_apr->num_rows > 0) {
            $aprendiz = $result_apr->fetch_assoc();
            $_SESSION['Id_aprendiz'] = $aprendiz['Id_aprendiz'];
        }

        // Registrar historial
        registrar_historial($conn, $usuario['Id_usuario'], 'Login', "El usuario inició sesión correctamente.");

        // Redirigir al inicio
        header("Location: /proyecto-sena/index.php?page=components/principales/welcome");
        exit;
    }
}

// Si no coincide o no existe el usuario
header("Location: ../components/principales/login.php?status=1");
exit;
