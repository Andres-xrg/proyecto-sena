<?php
session_start();
require_once '../db/conexion.php';
require_once '../functions/historial.php';

$email = $_POST['email'];
$password = $_POST['contraseña'];

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
            'email'  => $usuario['Email']
        ];

        registrar_historial($conn, $usuario['Id_usuario'], 'Login', "El usuario inició sesión correctamente.");

        header("Location: /proyecto-sena/index.php?page=components/principales/welcome");
        exit;
    }
}

header("Location: ../components/principales/login.php?status=1");
exit;
