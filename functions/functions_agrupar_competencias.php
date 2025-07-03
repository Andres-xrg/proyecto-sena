<?php

function categorizarCompetencia($competencia, $resultado_aprendizaje) {
    $texto_completo = strtolower(($competencia ?? '') . ' ' . ($resultado_aprendizaje ?? ''));

    // Palabras clave de competencias transversales
    if (preg_match('/ingles|english|idioma|matematica|calculo|algebra|estadistica|fisica|mecanica|electronica|investigacion|investigar|metodologia|etica|valores|moral|responsabilidad|social|comunicacion|interaccion|liderazgo|trabajo.*equipo|emprendimiento|empresa|negocio|emprender/i', $texto_completo)) {
        return ['categoria' => 'COMPETENCIAS TRANSVERSALES', 'materia' => 'COMPETENCIAS TRANSVERSALES'];
    }

    // Todo lo demás se considera competencia técnica
    return ['categoria' => 'COMPETENCIAS TÉCNICAS', 'materia' => 'COMPETENCIAS TÉCNICAS'];
}

function agruparCompetencias($resultado) {
    $competencias_agrupadas = [];
    while ($j = $resultado->fetch_assoc()) {
        $competencia = $j['Competencia'];
        if (!isset($competencias_agrupadas[$competencia])) {
            $competencias_agrupadas[$competencia] = [];
        }
        $competencias_agrupadas[$competencia][] = $j;
    }

    // Estructura base solo con las dos categorías
    $materias_organizadas = [
        'COMPETENCIAS TÉCNICAS' => ['COMPETENCIAS TÉCNICAS' => []],
        'COMPETENCIAS TRANSVERSALES' => ['COMPETENCIAS TRANSVERSALES' => []]
    ];

    foreach ($competencias_agrupadas as $competencia => $juicios) {
        if (is_array($juicios) && !empty($juicios)) {
            $primer_juicio = $juicios[0];
            $resultado_aprendizaje = $primer_juicio['Resultado_aprendizaje'] ?? '';
            $categorizacion = categorizarCompetencia($competencia, $resultado_aprendizaje);

            $categoria = $categorizacion['categoria'];
            $materia = $categorizacion['materia'];

            $materias_organizadas[$categoria][$materia][$competencia] = $juicios;
        }
    }

    return [$competencias_agrupadas, $materias_organizadas];
}
