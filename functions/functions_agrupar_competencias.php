<?php

function categorizarCompetencia($competencia, $resultado_aprendizaje) {
    $competencia_lower = strtolower($competencia ?? '');
    $resultado_lower = strtolower($resultado_aprendizaje ?? '');
    $texto_completo = $competencia_lower . ' ' . $resultado_lower;

    // Patrones para COMPETENCIAS TÉCNICAS
    if (preg_match('/analisis|análisis|requerimiento|levantamiento|especificacion/i', $texto_completo)) {
        return ['categoria' => 'COMPETENCIAS', 'materia' => 'FASE DE ANÁLISIS'];
    }
    if (preg_match('/base.*datos|bd|database|sql|mysql|postgresql/i', $texto_completo)) {
        return ['categoria' => 'COMPETENCIAS', 'materia' => 'BASE DE DATOS'];
    }
    if (preg_match('/programacion|programar|codigo|software|aplicacion|desarrollo/i', $texto_completo)) {
        return ['categoria' => 'COMPETENCIAS', 'materia' => 'DESARROLLO DE SOFTWARE'];
    }
    if (preg_match('/web|html|css|javascript|frontend|backend/i', $texto_completo)) {
        return ['categoria' => 'COMPETENCIAS', 'materia' => 'DESARROLLO WEB'];
    }
    if (preg_match('/red|redes|networking|tcp|ip|protocolo/i', $texto_completo)) {
        return ['categoria' => 'COMPETENCIAS', 'materia' => 'REDES'];
    }
    if (preg_match('/seguridad|security|ciberseguridad/i', $texto_completo)) {
        return ['categoria' => 'COMPETENCIAS', 'materia' => 'SEGURIDAD INFORMÁTICA'];
    }
    if (preg_match('/proyecto|propuesta|plan|planificacion/i', $texto_completo)) {
        return ['categoria' => 'COMPETENCIAS', 'materia' => 'ELABORACIÓN DE LA PROPUESTA'];
    }
    if (preg_match('/informe|documento|documentacion|reporte/i', $texto_completo)) {
        return ['categoria' => 'COMPETENCIAS', 'materia' => 'INFORME DE REQUERIMIENTOS'];
    }

    // Patrones para COMPETENCIAS TRANSVERSALES
    if (preg_match('/ingles|english|idioma/i', $texto_completo)) {
        return ['categoria' => 'COMPETENCIAS TRANSVERSALES', 'materia' => 'INGLÉS'];
    }
    if (preg_match('/matematica|calculo|algebra|estadistica/i', $texto_completo)) {
        return ['categoria' => 'COMPETENCIAS TRANSVERSALES', 'materia' => 'MATEMÁTICAS'];
    }
    if (preg_match('/fisica|mecanica|electronica/i', $texto_completo)) {
        return ['categoria' => 'COMPETENCIAS TRANSVERSALES', 'materia' => 'FÍSICA'];
    }
    if (preg_match('/investigacion|investigar|metodologia/i', $texto_completo)) {
        return ['categoria' => 'COMPETENCIAS TRANSVERSALES', 'materia' => 'INVESTIGACIÓN'];
    }
    if (preg_match('/etica|valores|moral|responsabilidad|social/i', $texto_completo)) {
        return ['categoria' => 'COMPETENCIAS TRANSVERSALES', 'materia' => 'ÉTICA Y VALORES'];
    }
    if (preg_match('/comunicacion|interaccion|social|liderazgo|trabajo.*equipo/i', $texto_completo)) {
        return ['categoria' => 'COMPETENCIAS TRANSVERSALES', 'materia' => 'MANEJO E INTERACCIÓN SOCIAL'];
    }
    if (preg_match('/emprendimiento|empresa|negocio|emprender/i', $texto_completo)) {
        return ['categoria' => 'COMPETENCIAS TRANSVERSALES', 'materia' => 'EMPRENDIMIENTO'];
    }

    // Si no coincide con ningún patrón, categorizar por código de competencia
    if (preg_match('/^2[0-9]{8}/', $competencia)) {
        return ['categoria' => 'COMPETENCIAS', 'materia' => 'OTRAS COMPETENCIAS TÉCNICAS'];
    }

    // Por defecto, competencias transversales
    return ['categoria' => 'COMPETENCIAS TRANSVERSALES', 'materia' => 'OTRAS COMPETENCIAS'];
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

    // Inicializar estructura con ambas categorías SIEMPRE
    $materias_organizadas = [
        'COMPETENCIAS' => [],
        'COMPETENCIAS TRANSVERSALES' => []
    ];

    // Agrupar competencias por categoría y materia
    if (is_array($competencias_agrupadas) && !empty($competencias_agrupadas)) {
        foreach ($competencias_agrupadas as $competencia => $juicios) {
            if (is_array($juicios) && !empty($juicios)) {
                $primer_juicio = $juicios[0];
                if (is_array($primer_juicio)) {
                    $resultado_aprendizaje = $primer_juicio['Resultado_aprendizaje'] ?? '';
                    $categorizacion = categorizarCompetencia($competencia, $resultado_aprendizaje);

                    $categoria = $categorizacion['categoria'];
                    $materia = $categorizacion['materia'];

                    if (!isset($materias_organizadas[$categoria][$materia])) {
                        $materias_organizadas[$categoria][$materia] = [];
                    }

                    $materias_organizadas[$categoria][$materia][$competencia] = $juicios;
                }
            }
        }
    }

    // Ordenar las materias dentro de cada categoría
    foreach ($materias_organizadas as &$categoria) {
        if (is_array($categoria) && !empty($categoria)) {
            ksort($categoria);
        }
    }

    return [$competencias_agrupadas, $materias_organizadas];
}