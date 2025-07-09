<?php

function obtener_porcentaje_aprobadas($documento) {
    require __DIR__ . '/../db/conexion.php';

    $stmt = $conn->prepare("SELECT Juicio FROM juicios_evaluativos WHERE N_Documento = ?");
    $stmt->bind_param("s", $documento);
    $stmt->execute();
    $res = $stmt->get_result();

    // Contadores por estado
    $total_resultados = 0;
    $aprobados = 0;
    $no_aprobados = 0;
    $pendientes = 0;

    while ($row = $res->fetch_assoc()) {
        $juicio = strtolower(trim($row['Juicio'] ?? ''));
        $juicio = str_ireplace(['á','é','í','ó','ú'], ['a','e','i','o','u'], $juicio); // quitar tildes si hay

        if ($juicio === 'aprobado') {
            $aprobados++;
        } elseif ($juicio === 'no aprobado') {
            $no_aprobados++;
        } elseif ($juicio === 'por evaluar' || $juicio === '') {
            $pendientes++;
        }

        $total_resultados++;
    }

    // Evitar división por cero
    $porcentaje = ($total_resultados > 0) ? round(($aprobados / $total_resultados) * 100) : 0;

    return [
        'porcentaje' => $porcentaje,
        'aprobadas' => $aprobados,
        'no_aprobadas' => $no_aprobados,
        'pendientes' => $pendientes,
        'total_resultados' => $total_resultados
    ];
}
?>
