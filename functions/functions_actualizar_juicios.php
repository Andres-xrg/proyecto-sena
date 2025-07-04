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

            if (!$nombre || !$apellido || !$competencia || !$resultado_aprendizaje) continue;

            // Buscar documento del aprendiz
            $buscar = $conn->prepare("SELECT a.N_Documento FROM ficha_aprendiz fa
                JOIN aprendices a ON fa.Id_aprendiz = a.Id_aprendiz
                WHERE fa.Id_ficha = ? AND a.nombre = ? AND a.apellido = ?");
            $buscar->bind_param("iss", $id_ficha, $nombre, $apellido);
            $buscar->execute();
            $res = $buscar->get_result();
            if ($res->num_rows === 0) continue;

            $documento = $res->fetch_assoc()['N_Documento'];

            // Verificar si ya existe ese juicio exacto
            $verifica = $conn->prepare("SELECT 1 FROM juicios_evaluativos 
                WHERE Numero_ficha = ? AND N_Documento = ? AND Competencia = ? AND Resultado_aprendizaje = ?");
            $verifica->bind_param("ssss", $numero_ficha, $documento, $competencia, $resultado_aprendizaje);
            $verifica->execute();
            $existe = $verifica->get_result()->num_rows;

            if ($existe > 0) {
                // Actualizar juicio existente
                $update = $conn->prepare("UPDATE juicios_evaluativos 
                    SET Juicio = ?, Estado_formacion = ?, Fecha_registro = ?, Funcionario_registro = ?
                    WHERE Numero_ficha = ? AND N_Documento = ? AND Competencia = ? AND Resultado_aprendizaje = ?");
                $update->bind_param("ssssssss", 
                    $juicio, 
                    $estado_formacion, 
                    $fecha, 
                    $funcionario,
                    $numero_ficha,
                    $documento,
                    $competencia,
                    $resultado_aprendizaje
                );
                $update->execute();
            }
        }

        // Redirigir a la misma ficha
        header("Location: ../index.php?page=components/fichas/ficha_vista&id=$id_ficha");
        exit;
    } catch (Exception $e) {
        die("❌ Error al leer el archivo: " . $e->getMessage());
    }
} else {
    die("❌ Archivo no enviado.");
}
