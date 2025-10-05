<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../db/conexion.php';

// -----------------------------
// Validar si hay usuario logueado
// -----------------------------
if (!isset($_SESSION['usuario'])) {
    header("Location: /proyecto-sena/index.php?page=components/principales/login");
    exit;
}

// -----------------------------
// Información de sesión actual
// -----------------------------
$current_session = session_id();
$rol = strtolower($_SESSION['usuario']['rol'] ?? '');
$usuario_id = 0;
$tabla = null;
$id_col = null;

// -----------------------------
// Determinar tabla y columna de ID según rol
// -----------------------------
switch ($rol) {
    case 'instructor':
        $tabla = 'instructores';
        $id_col = 'Id_instructor';
        $usuario_id = $_SESSION['usuario']['Id_instructor'] ?? 0;
        break;

    case 'administrador':
        // Administrador: no se valida estado ni session_id
        $usuario_id = $_SESSION['usuario']['Id_usuario'] ?? 0;
        break;

    default:
        // Usuario normal
        $tabla = 'usuarios';
        $id_col = 'Id_usuario';
        $usuario_id = $_SESSION['usuario']['Id_usuario'] ?? 0;
        break;
}

// -----------------------------
// Función para destruir sesión de forma segura
// -----------------------------
function destruirSesion() {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}

// -----------------------------
// Validaciones para usuarios e instructores
// -----------------------------
if ($rol !== 'administrador' && $usuario_id > 0 && $tabla && $id_col) {

    // Actualizar session_id en DB
    $sql_update = "UPDATE $tabla SET session_id = ? WHERE $id_col = ?";
    $stmt_update = $conn->prepare($sql_update);
    if ($stmt_update) {
        $stmt_update->bind_param("si", $current_session, $usuario_id);
        $stmt_update->execute();
    }

    // Obtener estado y session_id desde DB
    $sql = "SELECT estado, session_id FROM $tabla WHERE $id_col = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        destruirSesion();
        die("Error en consulta SQL: " . $conn->error);
    }
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // Usuario o instructor no encontrado
        destruirSesion();
        header("Location: /proyecto-sena/index.php?page=components/principales/login&status=deshabilitado");
        exit;
    }

    $user_db = $result->fetch_assoc();

    // Validar estado
    if ($user_db['estado'] == 0) {
        destruirSesion();
        header("Location: /proyecto-sena/index.php?page=components/principales/login&status=deshabilitado");
        exit;
    }

    // Validación de otro dispositivo
    if ($user_db['session_id'] !== $current_session) {
        destruirSesion();
        header("Location: /proyecto-sena/index.php?page=components/principales/login&status=otro_dispositivo");
        exit;
    }
}

?>
