<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once 'db/conexion.php';
require_once 'functions/historial.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numero_ficha = $_POST["numero_ficha"];
    $programa     = $_POST["programa"];
    $horas        = $_POST["horas_totales"];
    $jornada      = $_POST["Jornada"];
    $id_jefe      = intval($_POST["jefeGrupo"]);

    $sql = "INSERT INTO fichas (numero_ficha, programa_formación, Horas_Totales, Jornada, Estado_ficha, Jefe_grupo)
            VALUES (?, ?, ?, ?, 'Activo', ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisi", $numero_ficha, $programa, $horas, $jornada, $id_jefe);

    if ($stmt->execute()) {
        $id_ficha_insertada = $conn->insert_id;

        $usuario_id = $_SESSION['usuario']['id'] ?? 0;
        $desc = "Se creó la ficha $numero_ficha del programa '$programa' con jornada '$jornada' y jefe con ID $id_jefe.";
        registrar_historial($conn, $usuario_id, 'Registro de ficha', $desc);

        if (isset($_FILES['juicios']) && $_FILES['juicios']['error'] === UPLOAD_ERR_OK) {
            $archivoExcel = $_FILES['juicios']['tmp_name'];

            try {
                $spreadsheet = IOFactory::load($archivoExcel);
                $hoja = $spreadsheet->getActiveSheet();

                foreach ($hoja->getRowIterator(2) as $fila) {
                    $celdas = $fila->getCellIterator();
                    $celdas->setIterateOnlyExistingCells(false);

                    $filaDatos = [];
                    foreach ($celdas as $celda) {
                        $filaDatos[] = $celda->getValue();
                    }

                    if (count($filaDatos) >= 3) {
                        $nombre    = trim($filaDatos[0]);
                        $documento = trim($filaDatos[1]);
                        $juicio    = trim($filaDatos[2]);

                        $apellido = '';
                        $estado = 'En formación';
                        $competencia = '';
                        $resultado_aprendizaje = '';
                        $tipo_doc = 'C.C';
                        $id_usuario = 1; // ajustar si se desea vincular con usuarios reales

                        // Insertar en juicios_evaluativos
                        if (!empty($nombre) && !empty($documento) && !empty($juicio)) {
                            $stmtJuicio = $conn->prepare("INSERT INTO juicios_evaluativos 
                                (N_Documento, Nombre_aprendiz, Apellido_aprendiz, Estado_formacion, 
                                 Competencia, Resultado_aprendizaje, Juicio, 
                                 Numero_ficha, Programa_formacion, Fecha_registro)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

                            $stmtJuicio->bind_param("issssssss", $documento, $nombre, $apellido, $estado,
                                                      $competencia, $resultado_aprendizaje, $juicio,
                                                      $numero_ficha, $programa);
                            $stmtJuicio->execute();
                        }

                        // Verificar si aprendiz ya existe
                        $stmtCheck = $conn->prepare("SELECT Id_aprendiz FROM aprendices WHERE N_documento = ?");
                        $stmtCheck->bind_param("s", $documento);
                        $stmtCheck->execute();
                        $resultCheck = $stmtCheck->get_result();

                        if ($row = $resultCheck->fetch_assoc()) {
                            $id_aprendiz = $row['Id_aprendiz'];
                        } else {
                            // Insertar aprendiz
                            $stmtInsertAprendiz = $conn->prepare("INSERT INTO aprendices (Id_usuario, Nombre, Apellido, T_documento, N_documento) VALUES (?, ?, ?, ?, ?)");
                            $stmtInsertAprendiz->bind_param("issss", $id_usuario, $nombre, $apellido, $tipo_doc, $documento);
                            $stmtInsertAprendiz->execute();
                            $id_aprendiz = $conn->insert_id;
                        }

                        // Vincular aprendiz a la ficha
                        $stmtVincular = $conn->prepare("INSERT IGNORE INTO ficha_aprendiz (Id_ficha, Id_aprendiz) VALUES (?, ?)");
                        $stmtVincular->bind_param("ii", $id_ficha_insertada, $id_aprendiz);
                        $stmtVincular->execute();
                    }
                }
            } catch (Exception $e) {
                echo "⚠️ Error al procesar el archivo Excel: " . $e->getMessage();
                exit;
            }
        }

        header("Location: index.php?page=components/Fichas/Ficha_vista&id=" . $id_ficha_insertada);
        exit;

    } else {
        echo "❌ Error al registrar la ficha: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
