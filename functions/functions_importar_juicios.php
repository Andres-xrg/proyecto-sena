<?php

require_once '../db/conexion.php';
require _DIR_ . '/../vendor/autoload.php';

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

        $ultimo_nombre = '';
        $ultimo_apellido = '';
        $ultima_competencia = '';

        $registros_insertados = 0;
        $registros_omitidos = 0;

        for ($i = 13; $i < count($filas); $i++) {
            $fila = $filas[$i];

            $tipo_documento = strtoupper(trim($fila[0] ?? ''));
            $n_documento = trim($fila[1] ?? '');
            $nombre = trim($fila[2] ?? '');
            $apellido = trim($fila[3] ?? '');
            $estado_formacion = trim($fila[4] ?? '');
            $competencia = trim($fila[5] ?? '');
            $resultado_aprendizaje = trim($fila[6] ?? '');
            $juicio = trim($fila[7] ?? '');
            $fecha = !empty($fila[9]) ? date('Y-m-d H:i:s', strtotime($fila[9])) : date('Y-m-d H:i:s');
            $funcionario = trim($fila[10] ?? '');
            $telefono = ''; // Opcional

            if ($nombre !== '') $ultimo_nombre = $nombre;
            else $nombre = $ultimo_nombre;

            if ($apellido !== '') $ultimo_apellido = $apellido;
            else $apellido = $ultimo_apellido;

            if ($competencia !== '') $ultima_competencia = $competencia;
            else $competencia = $ultima_competencia;

            if (!$nombre || !$apellido || !$competencia || !$resultado_aprendizaje || !$n_documento || !$tipo_documento) {
                $registros_omitidos++;
                continue;
            }

            // Verificar si aprendiz ya existe
            $buscar = $conn->prepare("SELECT Id_aprendiz, T_documento FROM aprendices WHERE N_Documento = ?");
            $buscar->bind_param("s", $n_documento);
            $buscar->execute();
            $res = $buscar->get_result();

            if ($res->num_rows === 0) {
                // Insertar nuevo aprendiz
                $email = strtolower(str_replace(' ', '', $nombre)) . '@soysena.edu.co';

                $insert_aprendiz = $conn->prepare("INSERT INTO aprendices 
                    (nombre, apellido, T_documento, N_Documento, Email, N_Telefono) 
                    VALUES (?, ?, ?, ?, ?, ?)");
                $insert_aprendiz->bind_param("ssssss", $nombre, $apellido, $tipo_documento, $n_documento, $email, $telefono);

                if ($insert_aprendiz->execute()) {
                    $id_aprendiz = $conn->insert_id;

                    $asociar = $conn->prepare("INSERT INTO ficha_aprendiz (Id_ficha, Id_aprendiz) VALUES (?, ?)");
                    $asociar->bind_param("ii", $id_ficha, $id_aprendiz);
                    $asociar->execute();
                } else {
                    $registros_omitidos++;
                    continue;
                }
            } else {
                $row = $res->fetch_assoc();
                $id_aprendiz = $row['Id_aprendiz'];
                $tipo_actual = strtoupper(trim($row['T_documento']));

                // Actualizar tipo documento si cambió
                if ($tipo_actual !== $tipo_documento) {
                    $actualizar = $conn->prepare("UPDATE aprendices SET T_documento = ? WHERE Id_aprendiz = ?");
                    $actualizar->bind_param("si", $tipo_documento, $id_aprendiz);
                    $actualizar->execute();
                }

                // Verificar asociación a la ficha
                $verificar_asociacion = $conn->prepare("SELECT 1 FROM ficha_aprendiz WHERE Id_ficha = ? AND Id_aprendiz = ?");
                $verificar_asociacion->bind_param("ii", $id_ficha, $id_aprendiz);
                $verificar_asociacion->execute();
                $asociado = $verificar_asociacion->get_result();

                if ($asociado->num_rows === 0) {
                    $asociar = $conn->prepare("INSERT INTO ficha_aprendiz (Id_ficha, Id_aprendiz) VALUES (?, ?)");
                    $asociar->bind_param("ii", $id_ficha, $id_aprendiz);
                    $asociar->execute();
                }
            }

            // Verificar si ya existe el mismo juicio para evitar duplicados
            $verificar_juicio = $conn->prepare("SELECT Id_juicio FROM juicios_evaluativos 
                WHERE N_Documento = ? AND Competencia = ? AND Resultado_aprendizaje = ? AND Juicio = ? AND Numero_ficha = ?");
            $verificar_juicio->bind_param("sssss", $n_documento, $competencia, $resultado_aprendizaje, $juicio, $numero_ficha);
            $verificar_juicio->execute();
            $res_juicio = $verificar_juicio->get_result();

            if ($res_juicio->num_rows === 0) {
                // Insertar nuevo juicio evaluativo
                $stmt = $conn->prepare("INSERT INTO juicios_evaluativos 
                    (N_Documento, Nombre_aprendiz, Apellido_aprendiz, Estado_formacion, 
                    Competencia, Resultado_aprendizaje, Juicio, 
                    Numero_ficha, Programa_formacion, Fecha_registro, Funcionario_registro)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                $stmt->bind_param(
                    "issssssssss",
                    $n_documento,
                    $nombre,
                    $apellido,
                    $estado_formacion,
                    $competencia,
                    $resultado_aprendizaje,
                    $juicio,
                    $numero_ficha,
                    $programa,
                    $fecha,
                    $funcionario
                );

                if ($stmt->execute()) {
                    $registros_insertados++;
                } else {
                    $registros_omitidos++;
                }
            } else {
                $registros_omitidos++;
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