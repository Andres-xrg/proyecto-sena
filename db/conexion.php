<?php
$host = "localhost";
$usuario = "juan_admin";
$password = "juanes2Soto";
$base_datos = "juan_proyecto_formativo";

$conn = new mysqli($host, $usuario, $password, $base_datos);

if ($conn->connect_error) {
    header('Content-Type: application/json');
    echo json_encode([
        "active" => false,
        "error" => "Error de conexión a la base de datos: " . $conn->connect_error,
        "timestamp" => time()
    ]);
    exit;
}
?>
