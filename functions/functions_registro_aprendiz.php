<?php
require_once '../db/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre         = trim($_POST['nombre'] ?? '');
    $apellido       = trim($_POST['apellido'] ?? '');
    $telefono       = trim($_POST['telefono'] ?? '');
    $documento      = trim($_POST['numeroDocumento'] ?? '');
    $tipoDocumento  = trim($_POST['tipoDocumento'] ?? '');
    $correo         = trim($_POST['Email'] ?? '');
    $numeroFicha    = trim($_POST['ficha'] ?? '');

    // Validar que ningún campo esté vacío
    if (empty($nombre) || empty($apellido) || empty($telefono) || empty($documento) || empty($tipoDocumento) || empty($correo) || empty($numeroFicha)) {
        header("Location: ../components/registros/registro_aprendices.php?msg=campos_vacios");
        exit;
    }

    // Verificar si la ficha existe
    $stmtFicha = $conn->prepare("SELECT Id_ficha FROM fichas WHERE numero_ficha = ?");
    $stmtFicha->bind_param("s", $numeroFicha);
    $stmtFicha->execute();
    $resultFicha = $stmtFicha->get_result();

    if ($resultFicha->num_rows === 0) {
        header("Location: ../components/registros/registro_aprendices.php?msg=ficha_no_encontrada");
        exit;
    }

    $rowFicha = $resultFicha->fetch_assoc();
    $idFicha = $rowFicha['Id_ficha'];

    // Insertar aprendiz
    $stmtAprendiz = $conn->prepare("INSERT INTO aprendices (Nombre, Apellido, T_documento, N_documento) VALUES (?, ?, ?, ?)");
    $stmtAprendiz->bind_param("ssss", $nombre, $apellido, $tipoDocumento, $documento);

    if (!$stmtAprendiz->execute()) {
        header("Location: ../components/registros/registro_aprendices.php?msg=error_aprendiz");
        exit;
    }

    $idAprendiz = $conn->insert_id;

    // Insertar en ficha_aprendiz
    $stmtRelacion = $conn->prepare("INSERT INTO ficha_aprendiz (Id_ficha, Id_aprendiz) VALUES (?, ?)");
    $stmtRelacion->bind_param("ii", $idFicha, $idAprendiz);
    $stmtRelacion->execute();


    if ($resultUsuario->num_rows === 0) {
        $stmtInsertUser = $conn->prepare("INSERT INTO usuarios (N_Documento, N_Telefono, Email, nombre) VALUES (?, ?, ?, ?)");
        $stmtInsertUser->bind_param("ssss", $documento, $telefono, $correo, $nombre);
        $stmtInsertUser->execute();
    }

    header("Location: ../components/registros/registro_aprendices.php?msg=registro_exitoso");
    exit;
}
?>
