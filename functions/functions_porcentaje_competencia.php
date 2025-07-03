<?php

function obtener_porcentaje_aprobadas($documento) {
    require __DIR__ . '/../db/conexion.php';

    $stmt = $conn->prepare("SELECT Competencia, Juicio FROM juicios_evaluativos WHERE N_Documento = ?");
    $stmt->bind_param("s", $documento);
    $stmt->execute();
    $res = $stmt->get_result();

    $total = 0;
    $aprobadas = 0;
    $no_aprobadas = 0;
    $pendientes = 0;
    $competencias_unicas = [];

    while ($row = $res->fetch_assoc()) {
        $comp = $row['Competencia'];
        $juicio = strtolower(trim($row['Juicio'] ?? ''));

        if (!isset($competencias_unicas[$comp])) {
            $competencias_unicas[$comp] = $juicio;

            if ($juicio === 'aprobado') $aprobadas++;
            elseif ($juicio === 'no aprobado') $no_aprobadas++;
            elseif ($juicio === 'por evaluar' || $juicio === '') $pendientes++;

            $total++;
        }
    }

    $porcentaje = ($total > 0) ? round(($aprobadas / $total) * 100) : 0;

    return [
        'porcentaje' => $porcentaje,
        'aprobadas' => $aprobadas,
        'no_aprobadas' => $no_aprobadas,
        'pendientes' => $pendientes,
        'total' => $total
    ];
}
?>
