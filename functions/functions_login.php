<?php
session_start();
require_once '../db/conexion.php';
require_once '../functions/historial.php';

$email = $_POST['email'] ?? '';
$password = $_POST['contraseña'] ?? '';

// Buscar el usuario por correo
$sql = "SELECT * FROM usuarios WHERE Email = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $usuario = $result->fetch_assoc();

    if (password_verify($password, $usuario['Contraseña'])) {

        // Generar un session_id único
        session_regenerate_id(true);
        $new_session_id = session_id();

        // Guardar session_id en la DB (sobrescribe sesión anterior)
        $sql_update = "UPDATE usuarios SET session_id = ? WHERE Id_usuario = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("si", $new_session_id, $usuario['Id_usuario']);
        $stmt_update->execute();

        // Guardar info en $_SESSION
        $_SESSION['usuario'] = [
            'id'         => $usuario['Id_usuario'],
            'nombre'     => $usuario['nombre'],
            'email'      => $usuario['Email'],
            'rol'        => $usuario['Rol'],
            'session_id' => $new_session_id
        ];

        // Si es aprendiz
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

        // Redirigir al welcome
        header("Location: /proyecto-sena/index.php?page=components/principales/welcome");
        exit;

    } else {
        header("Location: ../components/principales/login.php?status=contrasena");
        exit;
    }
}

header("Location: ../components/principales/login.php?status=correo");
exit;
