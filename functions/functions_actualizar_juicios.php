<?php
require_once '../db/conexion.php';
require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['juicios'])) {
    $archivo = $_FILES['juicios']['tmp_name'];
    $id_ficha = $_POST['id_ficha'] ?? null;
    $numero_ficha = $_POST['numero_ficha'] ?? null;
    $programa = $_POST['programa'] ?? null;

    if (!$id_ficha || !$numero_ficha || !$programa) {
        die("❌ Datos de ficha incompletos.");
    }

    try {
        $spreadsheet = IOFactory::load($archivo);
        $hoja = $spreadsheet->getActiveSheet();
        $filas = $hoja->toArray();

        $ultimo_tipo = '';
        $ultimo_nombre = '';
        $ultimo_apellido = '';
        $ultima_competencia = '';

        for ($i = 13; $i < count($filas); $i++) {
            $fila = $filas[$i];

            $tipo_documento = strtoupper(trim($fila[0] ?? ''));
            $documento = trim($fila[1] ?? '');
            $nombre = trim($fila[2] ?? '');
            $apellido = trim($fila[3] ?? '');
            $estado_formacion = trim($fila[4] ?? '');
            $competencia = trim($fila[5] ?? '');
            $resultado_aprendizaje = trim($fila[6] ?? '');
            $juicio = trim($fila[7] ?? '');
            $fecha = !empty($fila[9]) ? date('Y-m-d H:i:s', strtotime($fila[9])) : date('Y-m-d H:i:s');
            $funcionario = trim($fila[10] ?? '');

            if ($tipo_documento !== '') $ultimo_tipo = $tipo_documento;
            else $tipo_documento = $ultimo_tipo;

            if ($nombre !== '') $ultimo_nombre = $nombre;
            else $nombre = $ultimo_nombre;

            if ($apellido !== '') $ultimo_apellido = $apellido;
            else $apellido = $ultimo_apellido;

            if ($competencia !== '') $ultima_competencia = $competencia;
            else $competencia = $ultima_competencia;

            if (!$nombre || !$apellido || !$competencia || !$resultado_aprendizaje || !$documento || !$tipo_documento) continue;

            // Verificar si el aprendiz ya existe
            $verificar_aprendiz = $conn->prepare("SELECT Id_aprendiz FROM aprendices WHERE T_documento = ? AND N_documento = ?");
            if (!$verificar_aprendiz) die("❌ Error en prepare (verificar_aprendiz): " . $conn->error);
            $verificar_aprendiz->bind_param("ss", $tipo_documento, $documento);
            $verificar_aprendiz->execute();
            $res_aprendiz = $verificar_aprendiz->get_result();

            if ($res_aprendiz->num_rows === 0) {
                // Insertar nuevo aprendiz con correo
                $email = strtolower(str_replace(' ', '', $nombre)) . '@soysena.edu.co';
                $insert_aprendiz = $conn->prepare("INSERT INTO aprendices (T_Documento, N_documento, nombre, apellido, Email, N_Telefono) VALUES (?, ?, ?, ?, ?, '')");
                if (!$insert_aprendiz) die("❌ Error en prepare (insert_aprendiz): " . $conn->error);
                $insert_aprendiz->bind_param("sssss", $tipo_documento, $documento, $nombre, $apellido, $email);
                $insert_aprendiz->execute();
                $id_aprendiz = $insert_aprendiz->insert_id;
            } else {
                // Actualizar nombre, apellido y correo si han cambiado
                $email = strtolower(str_replace(' ', '', $nombre)) . '@soysena.edu.co';
                $id_aprendiz = $res_aprendiz->fetch_assoc()['Id_aprendiz'];
                $update_aprendiz = $conn->prepare("UPDATE aprendices SET nombre = ?, apellido = ?, Email = ? WHERE Id_aprendiz = ?");
                if (!$update_aprendiz) die("❌ Error en prepare (update_aprendiz): " . $conn->error);
                $update_aprendiz->bind_param("sssi", $nombre, $apellido, $email, $id_aprendiz);
                $update_aprendiz->execute();
            }

            // Asociar aprendiz a la ficha
            $asociar = $conn->prepare("INSERT IGNORE INTO ficha_aprendiz (Id_ficha, Id_aprendiz) VALUES (?, ?)");
            if (!$asociar) die("❌ Error en prepare (asociar): " . $conn->error);
            $asociar->bind_param("ii", $id_ficha, $id_aprendiz);
            $asociar->execute();

            // Verificar si ya existe el juicio
            $verifica = $conn->prepare("SELECT Juicio, Estado_formacion FROM juicios_evaluativos WHERE Numero_ficha = ? AND N_Documento = ? AND Competencia = ? AND Resultado_aprendizaje = ?");
            if (!$verifica) die("❌ Error en prepare (verifica): " . $conn->error);
            $verifica->bind_param("ssss", $numero_ficha, $documento, $competencia, $resultado_aprendizaje);
            $verifica->execute();
            $resultado_existente = $verifica->get_result();

            if ($resultado_existente->num_rows > 0) {
                $existente = $resultado_existente->fetch_assoc();
                if ($existente['Juicio'] !== $juicio || $existente['Estado_formacion'] !== $estado_formacion) {
                    $update = $conn->prepare("UPDATE juicios_evaluativos SET Juicio = ?, Estado_formacion = ?, Fecha_registro = ?, Funcionario_registro = ? WHERE Numero_ficha = ? AND N_Documento = ? AND Competencia = ? AND Resultado_aprendizaje = ?");
                    if (!$update) die("❌ Error en prepare (update): " . $conn->error);
                    $update->bind_param("ssssssss", $juicio, $estado_formacion, $fecha, $funcionario, $numero_ficha, $documento, $competencia, $resultado_aprendizaje);
                    $update->execute();
                }
            } else {
$insert = $conn->prepare("INSERT INTO juicios_evaluativos (
    N_Documento, Nombre_aprendiz, Apellido_aprendiz, Estado_formacion,
    Competencia, Resultado_aprendizaje, Juicio,
    Numero_ficha, Programa_formacion, Fecha_registro, Funcionario_registro
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$insert->bind_param("sssssssssss", $documento, $nombre, $apellido, $estado_formacion, $competencia, $resultado_aprendizaje, $juicio, $numero_ficha, $programa, $fecha, $funcionario);
            }
        }

        header("Location: ../index.php?page=components/fichas/ficha_vista&id=$id_ficha");
        exit;

    } catch (Exception $e) {
        die("❌ Error al leer el archivo: " . $e->getMessage());
    }
} else {
    die("❌ Archivo no enviado.");
}
