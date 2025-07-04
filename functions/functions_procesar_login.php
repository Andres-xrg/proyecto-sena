<?php
session_start();
require_once("../db/conexion.php");
require_once("../functions/historial.php");

$email    = trim($_POST['email'] ?? '');
$password = trim($_POST['contraseña'] ?? '');

// Validación básica
if (empty($email) || empty($password)) {
    header("Location: /proyecto-sena/components/principales/login.php?status=vacio");
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

    // Verificar contraseña
    if (password_verify($password, $usuario['Contraseña'])) {
        // Guardar sesión
        $_SESSION['usuario'] = [
            'id'     => $usuario['Id_usuario'],
            'email'  => $usuario['Email'],
            'nombre' => $usuario['nombre']
        ];

        // Intentar cargar Id_aprendiz (si el usuario está relacionado con un aprendiz)
        $sql_aprendiz = "SELECT Id_aprendiz FROM aprendices WHERE Id_usuario = ?";
        $stmt_apr = $conn->prepare($sql_aprendiz);
        $stmt_apr->bind_param("i", $usuario['Id_usuario']);
        $stmt_apr->execute();
        $result_apr = $stmt_apr->get_result();

        if ($result_apr->num_rows > 0) {
            $aprendiz = $result_apr->fetch_assoc();
            $_SESSION['Id_aprendiz'] = $aprendiz['Id_aprendiz'];
        }

        registrar_historial($conn, $usuario['Id_usuario'], 'Login', "El usuario inició sesión correctamente.");
        header("Location: /proyecto-sena/index.php?page=components/principales/welcome");
        exit;

    } else {
        // ❗ Corrección: usar 'contrasena' (sin ñ)
        header("Location: /proyecto-sena/components/principales/login.php?status=contrasena");
        exit;
    }

} else {
    header("Location: /proyecto-sena/components/principales/login.php?status=correo");
    exit;
}
