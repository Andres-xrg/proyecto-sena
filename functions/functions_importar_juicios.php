<?php
require_once '../db/conexion.php';
require '../libs/PhpSpreadsheet/Spreadsheet.php';
require '../libs/PhpSpreadsheet/IOFactory.php';

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

        foreach ($filas as $fila) {
            $competencia = $fila[0] ?? null;
            $resultado_aprendizaje = $fila[1] ?? null;
            $juicio = $fila[2] ?? null;
            $fecha = !empty($fila[3]) ? date('Y-m-d H:i:s', strtotime($fila[3])) : null;
            $instructor = $fila[4] ?? null;

            // Si tu Excel incluye nombre, apellido y documento, reemplaza estas líneas
            $nombre = "Nombre";
            $apellido = "Apellido";
            $documento = 0;

            // Validación del juicio
            if (strtoupper($juicio) === 'APROBADO' || strtoupper($juicio) === 'NO APROBADO' || strtoupper($juicio) === 'POR EVALUAR') {
                $stmt = $conn->prepare("INSERT INTO juicios_evaluativos 
                    (N_Documento, Nombre_aprendiz, Apellido_aprendiz, Estado_formacion, 
                     Competencia, Resultado_aprendizaje, Juicio, 
                     Numero_ficha, Programa_formacion, Fecha_registro)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                $estado = 'En formacion';
                $stmt->bind_param("isssssssss", $documento, $nombre, $apellido, $estado,
                                  $competencia, $resultado_aprendizaje, $juicio,
                                  $numero_ficha, $programa, $fecha);
                $stmt->execute();
            }
        }

        header("Location: ../index.php?page=components/ficha_detalle&id=$id_ficha&mensaje=importado");
        exit;
    } catch (Exception $e) {
        die("❌ Error al leer el archivo: " . $e->getMessage());
    }
} else {
    die("❌ Archivo no enviado.");
}
?>