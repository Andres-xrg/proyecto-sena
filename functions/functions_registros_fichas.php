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
    $programa     = $_POST["programa"];
    $horas        = $_POST["horas_totales"];
    $jornada      = $_POST["Jornada"];
    $id_jefe      = $_POST["jefeGrupo"];

    $sql = "INSERT INTO fichas (numero_ficha, programa_formación, Horas_Totales, Jornada, Estado_ficha, Jefe_grupo)
            VALUES (?, ?, ?, ?, 'Activo', ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisi", $numero_ficha, $programa, $horas, $jornada, $id_jefe);

    if ($stmt->execute()) {
        $id_ficha_insertada = $conn->insert_id;
        $usuario_id = $_SESSION['usuario']['id'] ?? 0;
        registrar_historial($conn, $usuario_id, 'Registro de ficha', "Ficha $numero_ficha creada");

        if (isset($_FILES['juicios']) && $_FILES['juicios']['error'] === UPLOAD_ERR_OK) {
            $archivoExcel = $_FILES['juicios']['tmp_name'];

            try {
                $spreadsheet = IOFactory::load($archivoExcel);
                $hoja = $spreadsheet->getActiveSheet();

                foreach ($hoja->getRowIterator(14) as $fila) {
                    $celdas = $fila->getCellIterator();
                    $celdas->setIterateOnlyExistingCells(false);

                    $filaDatos = [];
                    foreach ($celdas as $celda) {
                        $filaDatos[] = trim((string)$celda->getValue());
                    }

                    if (count($filaDatos) < 11) continue;

                    list($_tipo_doc, $documento, $nombre, $apellido, $estado, $competencia, $resultado_aprendizaje, $juicio, $_omitido1, $fecha_juicio, $funcionario) = $filaDatos;

                    if (!is_numeric($documento) || intval($documento) === 0) continue;

                    $tipo_doc = "C.C";
                    $email = strtolower(str_replace(' ', '', $nombre)) . "@sena.edu.co";
                    $telefono = "No disponible";

                    // Verificar aprendiz
                    $verificar = $conn->prepare("SELECT Id_aprendiz FROM aprendices WHERE N_Documento = ?");
                    $verificar->bind_param("s", $documento);
                    $verificar->execute();
                    $res = $verificar->get_result();

                    if ($res->num_rows === 0) {
                        $insert_ap = $conn->prepare("INSERT INTO aprendices (T_documento, N_Documento, nombre, apellido, Email, N_Telefono) VALUES (?, ?, ?, ?, ?, ?)");
                        $insert_ap->bind_param("ssssss", $tipo_doc, $documento, $nombre, $apellido, $email, $telefono);
                        $insert_ap->execute();
                        $id_aprendiz = $insert_ap->insert_id;
                    } else {
                        $id_aprendiz = $res->fetch_assoc()['Id_aprendiz'];
                    }

                    // Asociar aprendiz con ficha
                    $asociar = $conn->prepare("INSERT IGNORE INTO ficha_aprendiz (Id_ficha, Id_aprendiz) VALUES (?, ?)");
                    $asociar->bind_param("ii", $id_ficha_insertada, $id_aprendiz);
                    $asociar->execute();

                    // Insertar juicio
                    $insert_juicio = $conn->prepare("INSERT INTO juicios_evaluativos (
                        Numero_ficha, N_Documento, Nombre_aprendiz, Apellido_aprendiz,
                        Estado_formacion, Competencia, Resultado_aprendizaje,
                        Juicio, Fecha_registro, Funcionario_registro
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                    $now = date('Y-m-d H:i:s');
                    $insert_juicio->bind_param("ssssssssss", $numero_ficha, $documento, $nombre, $apellido, $estado, $competencia, $resultado_aprendizaje, $juicio, $now, $funcionario);
                    $insert_juicio->execute();
                }

            } catch (Exception $e) {
                echo "<p style='color:red;'>❌ Error al procesar el Excel: " . $e->getMessage() . "</p>";
                exit;
            }
        }

        // 🔁 Redirigir como antes
        header("Location: index.php?page=components/Fichas/Ficha_vista&id=" . $id_ficha_insertada);
        exit;

    } else {
        echo "<p style='color:red;'>❌ Error al registrar ficha: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
}
