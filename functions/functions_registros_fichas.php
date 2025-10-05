<?php
require_once __DIR__ . '/../db/conexion.php';
require_once __DIR__ . '/historial.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $numero_ficha = $_POST["numero_ficha"];
    $id_programa  = $_POST["programa"];
    $jornada      = $_POST["Jornada"];
    $id_jefe      = $_POST["jefeGrupo"];
    $tipo_oferta  = $_POST["tipo_oferta"];

    // Validar si el número de ficha ya existe
    $verificar = $conn->prepare("SELECT 1 FROM fichas WHERE numero_ficha = ?");
    $verificar->bind_param("s", $numero_ficha);
    $verificar->execute();
    $resultado = $verificar->get_result();

    if ($resultado->num_rows > 0) {
        header("Location: /proyecto-sena/index.php?page=components/registros/registro_fichas&error=ficha-repetida");
        exit;
    }

    // Insertar ficha
    $sql = "INSERT INTO fichas (numero_ficha, Id_programa, Jornada, Estado_ficha, Jefe_grupo, tipo_oferta)
            VALUES (?, ?, ?, 'Activo', ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisis", $numero_ficha, $id_programa, $jornada, $id_jefe, $tipo_oferta);

    if ($stmt->execute()) {
        $id_ficha_insertada = $conn->insert_id;
        $usuario_id = $_SESSION['usuario']['id'] ?? 0;
        registrar_historial($conn, $usuario_id, 'Registro de ficha', "Ficha $numero_ficha creada");

        // Procesar Excel
        if (isset($_FILES['juicios']) && $_FILES['juicios']['error'] === UPLOAD_ERR_OK) {
            $archivoExcel = $_FILES['juicios']['tmp_name'];

            try {
                $spreadsheet = IOFactory::load($archivoExcel);
                $hoja = $spreadsheet->getActiveSheet();
                $fila_inicio = 14;

                foreach ($hoja->getRowIterator($fila_inicio) as $fila) {
                    $celdas = $fila->getCellIterator();
                    $celdas->setIterateOnlyExistingCells(false);

                    $filaDatos = [];
                    foreach ($celdas as $celda) {
                        $filaDatos[] = trim((string)$celda->getValue());
                    }

                    if (count($filaDatos) < 11) continue;

                    list($_tipo_doc, $documento, $nombre, $apellido, $estado, $competencia, $resultado_aprendizaje, $juicio, $_omitido1, $fecha_juicio, $funcionario) = $filaDatos;

                    if (!isset($documento) || !is_numeric($documento)) continue;

                    $tipo_doc = strtoupper(trim($_tipo_doc));
                    $email = strtolower(str_replace(' ', '', $nombre)) . "@sena.edu.co";
                    $telefono = "No disponible";

                    // Verificar aprendiz
                    $verificar = $conn->prepare("SELECT Id_aprendiz FROM aprendices WHERE N_Documento = ?");
                    $verificar->bind_param("s", $documento);
                    $verificar->execute();
                    $res = $verificar->get_result();

                    if ($res->num_rows === 0) {
                        $insert_ap = $conn->prepare("INSERT INTO aprendices (T_documento, N_documento, nombre, apellido, Email, N_Telefono) VALUES (?, ?, ?, ?, ?, ?)");
                        $insert_ap->bind_param("ssssss", $tipo_doc, $documento, $nombre, $apellido, $email, $telefono);
                        $insert_ap->execute();
                        $id_aprendiz = $insert_ap->insert_id;
                    } else {
                        $id_aprendiz = $res->fetch_assoc()['Id_aprendiz'];
                    }

                    // Asociar aprendiz
                    $asociar = $conn->prepare("INSERT IGNORE INTO ficha_aprendiz (Id_ficha, Id_aprendiz) VALUES (?, ?)");
                    $asociar->bind_param("ii", $id_ficha_insertada, $id_aprendiz);
                    $asociar->execute();

                    // Juicio evaluativo
                    $verifica_juicio = $conn->prepare("SELECT 1 FROM juicios_evaluativos 
                        WHERE Numero_ficha = ? AND N_Documento = ? AND Competencia = ? AND Resultado_aprendizaje = ?");
                    $verifica_juicio->bind_param("ssss", $numero_ficha, $documento, $competencia, $resultado_aprendizaje);
                    $verifica_juicio->execute();
                    $juicio_existe = $verifica_juicio->get_result();

                    if ($juicio_existe->num_rows === 0) {
                        $insert_juicio = $conn->prepare("INSERT INTO juicios_evaluativos (
                            Numero_ficha, N_Documento, Nombre_aprendiz, Apellido_aprendiz,
                            Estado_formacion, Competencia, Resultado_aprendizaje,
                            Juicio, Fecha_registro, Funcionario_registro
                        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                        $now = date('Y-m-d H:i:s');
                        $insert_juicio->bind_param("ssssssssss", $numero_ficha, $documento, $nombre, $apellido, $estado, $competencia, $resultado_aprendizaje, $juicio, $now, $funcionario);
                        $insert_juicio->execute();
                    }
                }
            } catch (Exception $e) {
                header("Location: /proyecto-sena/index.php?page=components/registros/registro_fichas&error=excel");
                exit;
            }
        }

        // Redirigir a la vista de la ficha
        header("Location: /proyecto-sena/index.php?page=components/Fichas/Ficha_vista&id_ficha=$id_ficha_insertada&success=ficha-creada");
        exit();

    } else {
        header("Location: /proyecto-sena/index.php?page=components/registros/registro_fichas&error=insertar-ficha");
        exit;
    }

    $stmt->close();
    $conn->close();
}
