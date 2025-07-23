<?php
session_start();
require_once '../db/conexion.php';
require_once '../functions/historial.php';

$email = trim($_POST['email'] ?? '');
$password = trim($_POST['contraseña'] ?? '');

if (empty($email) || empty($password)) {
    header("Location: ../components/principales/login.php?status=vacio");
    exit;
}

$sql = "SELECT * FROM usuarios WHERE Email = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $usuario = $result->fetch_assoc();

    if (password_verify($password, $usuario['Contraseña'])) {
        $_SESSION['usuario'] = [
            'id'     => $usuario['Id_usuario'],
            'nombre' => $usuario['nombre'],
            'email'  => $usuario['Email'],
            'rol'    => $usuario['Rol']
        ];

        if ($usuario['Rol'] === 'aprendiz') {
            $sql_apr = "SELECT Id_aprendiz FROM aprendices WHERE Id_usuario = ?";
            $stmt_apr = $conn->prepare($sql_apr);
            $stmt_apr->bind_param("i", $usuario['Id_usuario']);
            $stmt_apr->execute();
            $result_apr = $stmt_apr->get_result();

            if ($result_apr->num_rows > 0) {
                $aprendiz = $result_apr->fetch_assoc();
                $_SESSION['Id_aprendiz'] = $aprendiz['Id_aprendiz'];
            }
        }

        registrar_historial($conn, $usuario['Id_usuario'], 'Login', "El usuario inició sesión correctamente.");
        header("Location: /proyecto-sena/index.php?page=components/principales/welcome");
        exit;

    } else {
        header("Location: ../components/principales/login.php?status=contrasena");
        exit;
    }

} else {
    header("Location: ../components/principales/login.php?status=correo");
    exit;
}
