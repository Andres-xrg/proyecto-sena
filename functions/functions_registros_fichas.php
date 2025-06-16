<?php
require_once 'db/conexion.php';
require_once 'functions/historial.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numero_ficha = $_POST["numero_ficha"];
    $programa     = $_POST["programa"];
    $horas        = $_POST["horas_totales"];
    $jornada      = $_POST["Jornada"];
    $jefe_nombre  = $_POST["jefeGrupo"];

    // Buscar el ID del jefe por su nombre
    $query = "SELECT Id_instructor FROM instructores WHERE nombre = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $jefe_nombre);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $jefe = $resultado->fetch_assoc();

    if (!$jefe) {
        echo "⚠️ Error: no se encontró al jefe de grupo con ese nombre.";
        exit;
    }

    $id_jefe = $jefe["Id_instructor"];

    // Insertar ficha
    $sql = "INSERT INTO fichas (numero_ficha, programa_formación, Horas_Totales, Jornada, Estado_ficha, Jefe_grupo)
            VALUES (?, ?, ?, ?, 'Activo', ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisi", $numero_ficha, $programa, $horas, $jornada, $id_jefe);

    if ($stmt->execute()) {
        // ✅ Registrar historial (con o sin sesión activa)
        $usuario_id = $_SESSION['usuario']['id'] ?? 0;
        $desc = "Se creó la ficha $numero_ficha del programa '$programa' con jornada '$jornada' y jefe '$jefe_nombre'.";
        registrar_historial($conn, $usuario_id, 'Registro de ficha', $desc);

        header("Location: index.php?page=components/Fichas/listar_fichas&mensaje=creada");
        exit;
    } else {
        echo "❌ Error al registrar la ficha: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
