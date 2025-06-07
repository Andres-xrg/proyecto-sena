<?php
session_start();

// Conexión a la base de datos
include '../db/conexion.php'; // Asegúrate de que esta ruta es correcta

// Recibir datos del formulario
$email = $_POST['email'];
$password = $_POST['contraseña'];

// Consulta SQL (ajusta a tu estructura de base de datos)
$sql = "SELECT * FROM usuarios WHERE email = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $usuario = $result->fetch_assoc();
    
    if (password_verify($password, $usuario['contraseña'])) {
        $_SESSION['usuario'] = [
            'id' => $usuario['id'],
            'nombre' => $usuario['nombre'],
            'email' => $usuario['email']
        ];
        header("Location: /proyecto-sena/index.php?page=components/principales/welcome");
        exit;
    }
}

// Si llega aquí, el login falló
header("Location: ../components/principales/login.php?status=1");
exit;
?>
