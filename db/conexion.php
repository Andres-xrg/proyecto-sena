<?php
$host = "localhost";          // Servidor (deja "localhost" si usas WAMP/XAMPP)
$usuario = "root";            // Usuario (por defecto "root")
$password = "";               // Contraseña (vacía por defecto en WAMP)
$base_datos = "proyecto_formativo"; // 🔁 Asegúrate de que este sea el nombre correcto de tu base de datos

$conn = new mysqli($host, $usuario, $password, $base_datos);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
