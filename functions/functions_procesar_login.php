<?php
session_start();
require_once '../db/conexion.php';
require_once '../functions/historial.php';

// Recibir datos
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['contraseña'] ?? '');

// Validar campos vacíos
if (empty($email) || empty($password)) {
    header("Location: ../components/principales/login.php?status=vacio");
    exit;
}

// Función para buscar usuario/instructor
function buscarUsuario($conn, $email) {
    $tablas = ['usuarios', 'instructores'];
    foreach ($tablas as $tabla) {
        $sql = "SELECT * FROM $tabla WHERE Email = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $data = $result->fetch_assoc();
            $data['tabla'] = $tabla;
            return $data;
        }
    }
    return null;
}

// Buscar usuario/instructor
$usuario = buscarUsuario($conn, $email);

if (!$usuario) {
    header("Location: ../components/principales/login.php?status=correo");
    exit;
}

// Validar contraseña
if (!password_verify($password, $usuario['Contraseña'])) {
    header("Location: ../components/principales/login.php?status=contrasena");
    exit;
}

// Validar estado activo
if ((isset($usuario['estado']) && $usuario['estado'] == 0)) {
    header("Location: ../components/principales/login.php?status=deshabilitado");
    exit;
}

// Validar sesión única (prevención de otro dispositivo)
$current_session = session_id();
if (!empty($usuario['session_id']) && $usuario['session_id'] !== $current_session) {
    $last_activity = strtotime($usuario['last_activity'] ?? '1970-01-01 00:00:00');
    if ($last_activity && (time() - $last_activity) < 60) {
        registrar_historial($conn, $usuario['Id_usuario'], 'Logout', 'Se cerró sesión por iniciar sesión en otro dispositivo.');
        header("Location: ../components/principales/login.php?status=otro_dispositivo");
        exit;
    }
}

// Generar session_id único
session_regenerate_id(true);
$new_session_id = session_id();

// Guardar session_id + last_activity en DB
$sql_update = "UPDATE {$usuario['tabla']} SET session_id = ?, last_activity = NOW() WHERE Id_usuario = ?";
$stmt_update = $conn->prepare($sql_update);
$stmt_update->bind_param("si", $new_session_id, $usuario['Id_usuario']);
$stmt_update->execute();

// Guardar info en $_SESSION
$_SESSION['usuario'] = [
    'id'         => $usuario['Id_usuario'],
    'nombre'     => $usuario['nombre'],
    'email'      => $usuario['Email'],
    'rol'        => $usuario['Rol'] ?? 'instructor',
    'session_id' => $new_session_id,
    'tabla'      => $usuario['tabla']
];

// Si es aprendiz, guardar Id_aprendiz en sesión
if (($usuario['Rol'] ?? '') === 'aprendiz') {
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

// Registrar historial
registrar_historial($conn, $usuario['Id_usuario'], 'Login', "El usuario inició sesión correctamente.");

// Redirigir al welcome
header("Location: /proyecto-sena/index.php?page=components/principales/welcome");
exit;
?>
