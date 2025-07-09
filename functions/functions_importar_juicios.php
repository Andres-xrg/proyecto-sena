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

        $ultimo_nombre = '';
        $ultimo_apellido = '';
        $ultima_competencia = '';

        $registros_insertados = 0;
        $registros_omitidos = 0;

        for ($i = 13; $i < count($filas); $i++) {
            $fila = $filas[$i];

            $nombre = trim($fila[2] ?? '');
            $apellido = trim($fila[3] ?? '');
            $estado_formacion = trim($fila[4] ?? '');
            $competencia = trim($fila[5] ?? '');
            $resultado_aprendizaje = trim($fila[6] ?? '');
            $juicio = trim($fila[7] ?? '');
            $fecha = !empty($fila[9]) ? date('Y-m-d H:i:s', strtotime($fila[9])) : date('Y-m-d H:i:s');
            $funcionario = trim($fila[10] ?? '');

            // Reutilizar si vienen celdas vacías
            if ($nombre !== '') $ultimo_nombre = $nombre;
            else $nombre = $ultimo_nombre;

            if ($apellido !== '') $ultimo_apellido = $apellido;
            else $apellido = $ultimo_apellido;

            if ($competencia !== '') $ultima_competencia = $competencia;
            else $competencia = $ultima_competencia;

            // Validar que existan los campos claves
            if (!$nombre || !$apellido || !$competencia || !$resultado_aprendizaje) {
                $registros_omitidos++;
                continue;
            }

            // Buscar documento del aprendiz
            $buscar = $conn->prepare("SELECT a.N_Documento FROM ficha_aprendiz fa
                JOIN aprendices a ON fa.Id_aprendiz = a.Id_aprendiz
                WHERE fa.Id_ficha = ? AND a.nombre = ? AND a.apellido = ?");
            $buscar->bind_param("iss", $id_ficha, $nombre, $apellido);
            $buscar->execute();
            $res = $buscar->get_result();

            if ($res->num_rows === 0) {
                $registros_omitidos++;
                continue;
            }

            $documento = $res->fetch_assoc()['N_Documento'];

            // Insertar sin verificar duplicados (cada competencia + resultado_aprendizaje es único)
            $stmt = $conn->prepare("INSERT INTO juicios_evaluativos 
                (N_Documento, Nombre_aprendiz, Apellido_aprendiz, Estado_formacion, 
                Competencia, Resultado_aprendizaje, Juicio, 
                Numero_ficha, Programa_formacion, Fecha_registro, Funcionario_registro)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            if ($stmt === false) {
                die("❌ Error en prepare: " . $conn->error);
            }

            $stmt->bind_param(
                "issssssssss",
                $documento,
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
        }

       // Redirigir a la ficha
        header("Location: ../index.php?page=components/fichas/ficha_vista&id=$id_ficha");
        exit;
    } catch (Exception $e) {
        die("❌ Error al leer el archivo: " . $e->getMessage());
    }
} else {
    die("❌ Archivo no enviado.");
}
