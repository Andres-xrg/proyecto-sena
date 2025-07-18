<?php
$host = "localhost";
$usuario = "root";
$password = "123456";
$password = "123456789";
$base_datos = "proyecto_formativo";

$conn = new mysqli($host, $usuario, $password, $base_datos);

if ($conn->connect_error) {
    error_log("Error de conexión: " . $conn->connect_error); 
    exit(); 
}
?>
