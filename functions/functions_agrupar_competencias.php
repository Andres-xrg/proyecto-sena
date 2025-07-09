<?php

function categorizarCompetencia($competencia, $resultado_aprendizaje) {
    $texto_completo = strtolower(($competencia ?? '') . ' ' . ($resultado_aprendizaje ?? ''));

    // Palabras clave de competencias transversales
    if (preg_match('/ingles|english|idioma|matematica|calculo|algebra|estadistica|fisica|mecanica|electronica|investigacion|investigar|metodologia|etica|valores|moral|responsabilidad|social|comunicacion|interaccion|liderazgo|trabajo.*equipo|emprendimiento|empresa|negocio|emprender/i', $texto_completo)) {
        return ['categoria' => 'COMPETENCIAS TRANSVERSALES', 'materia' => 'COMPETENCIAS TRANSVERSALES'];
    }

    // Todo lo demás es técnico
    return ['categoria' => 'COMPETENCIAS TÉCNICAS', 'materia' => 'COMPETENCIAS TÉCNICAS'];
}

function agruparCompetencias($resultado) {
    $competencias_agrupadas = [];
    $materias_organizadas = [
        'COMPETENCIAS TÉCNICAS' => [],
        'COMPETENCIAS TRANSVERSALES' => []
    ];

    while ($j = $resultado->fetch_assoc()) {
        $competencia = $j['Competencia'] ?? '';
        $resultado_aprendizaje = $j['Resultado_aprendizaje'] ?? '';

        // Agrupar por competencia
        $competencias_agrupadas[$competencia][] = $j;

        // Clasificación
        $categorizacion = categorizarCompetencia($competencia, $resultado_aprendizaje);
        $categoria = $categorizacion['categoria'];
        $materia = $categorizacion['materia'];

        // Inicializar si no existe
        if (!isset($materias_organizadas[$categoria][$materia])) {
            $materias_organizadas[$categoria][$materia] = [];
        }
        if (!isset($materias_organizadas[$categoria][$materia][$competencia])) {
            $materias_organizadas[$categoria][$materia][$competencia] = [];
        }

        // Agregar el resultado a la competencia
        $materias_organizadas[$categoria][$materia][$competencia][] = $j;
    }

    return [$competencias_agrupadas, $materias_organizadas];
}
