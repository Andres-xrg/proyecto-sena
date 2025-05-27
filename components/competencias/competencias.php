<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../assets/css/competencias.css">
    <link rel="stylesheet" href="../../assets/css/header.css">
    <link rel="stylesheet" href="../../assets/css/footer.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plataforma Educativa SENA</title>
</head>
<body>

    <?php include '../../includes/header.php'; ?>
    
    <main class="main-content">
        <h1 class="page-title">Juicios Evaluativos (Nombre Aprendiz)</h1>

        <div class="search-section">
            <div class="search-container">
                <input type="text" placeholder="Buscar..." class="search-input">
                <img src="/proyecto-sena/assets/img/search.png" alt="Search" class="search-icon">
            </div>
            <button class="generate-report-btn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                GENERAR REPORTE
            </button>
        </div>

        <!-- Content Grid -->
        <div class="content-grid">
            <!-- Left Column -->
            <div class="left-column">
                <!-- Competencias -->
                <div class="card">
                    <div class="card-header" onclick="toggleCard('competencias')">
                        <span class="card-title">COMPETENCIAS</span>
                        <svg class="chevron" id="chevron-competencias" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6,9 12,15 18,9"></polyline>
                        </svg>
                    </div>
                    <div class="card-content open" id="content-competencias">
                        <div class="section-title">FASE DE ANALISIS</div>
                        <div class="result-item">
                            <span class="result-code">A01- Resultado-1</span>
                            <span class="result-instructor">Yuely Adriana Arce</span>
                            <span class="result-status status-approved">Aprueba</span>
                        </div>
                        <div class="result-item">
                            <span class="result-code">A02- Resultado-2</span>
                            <span class="result-instructor">Diego Marin</span>
                            <span class="result-status status-rejected">No Aprueba</span>
                        </div>
                        <div class="result-item">
                            <span class="result-code">A03- Resultado-3</span>
                            <span class="result-instructor">Yuely Adriana Arce</span>
                            <span class="result-status status-rejected">No Aprueba</span>
                        </div>
                        <div class="result-item">
                            <span class="result-code">A04- Resultado-4</span>
                            <span class="result-instructor">Diego Marin</span>
                            <span class="result-status status-pending">Por Evaluar</span>
                        </div>
                    </div>
                </div>

                <!-- Informe de Requerimientos -->
                <div class="card">
                    <div class="card-header" onclick="toggleCard('informe')">
                        <span class="card-title">INFORME DE REQUERIMIENTOS</span>
                        <svg class="chevron" id="chevron-informe" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6,9 12,15 18,9"></polyline>
                        </svg>
                    </div>
                    <div class="card-content" id="content-informe">
                        <div class="placeholder-content">Contenido de Informe de Requerimientos</div>
                    </div>
                </div>

                <!-- Inglés -->
                <div class="card">
                    <div class="card-header" onclick="toggleCard('ingles')">
                        <span class="card-title">INGLES</span>
                        <svg class="chevron" id="chevron-ingles" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6,9 12,15 18,9"></polyline>
                        </svg>
                    </div>
                    <div class="card-content" id="content-ingles">
                        <div class="placeholder-content">Contenido de Inglés</div>
                    </div>
                </div>

                <!-- Base de Datos -->
                <div class="card">
                    <div class="card-header" onclick="toggleCard('basedatos')">
                        <span class="card-title">BASE DE DATOS</span>
                        <svg class="chevron" id="chevron-basedatos" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6,9 12,15 18,9"></polyline>
                        </svg>
                    </div>
                    <div class="card-content" id="content-basedatos">
                        <div class="placeholder-content">Contenido de Base de Datos</div>
                    </div>
                </div>

                <!-- Elaboración de la Propuesta -->
                <div class="card">
                    <div class="card-header" onclick="toggleCard('propuesta')">
                        <span class="card-title">ELABORACION DE LA PROPUESTA</span>
                        <svg class="chevron" id="chevron-propuesta" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6,9 12,15 18,9"></polyline>
                        </svg>
                    </div>
                    <div class="card-content" id="content-propuesta">
                        <div class="placeholder-content">Contenido de Elaboración de la Propuesta</div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="right-column">
                <!-- Competencias Transversales -->
                <div class="card">
                    <div class="card-header" onclick="toggleCard('transversales')">
                        <span class="card-title">COMPETENCIAS TRANSVERSALES</span>
                        <svg class="chevron" id="chevron-transversales" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6,9 12,15 18,9"></polyline>
                        </svg>
                    </div>
                    <div class="card-content open" id="content-transversales">
                        <div class="section-title">MANEJO E INTERACCION SOCIAL</div>
                        <div class="result-item">
                            <span class="result-code">A01- Resultado-1</span>
                            <span class="result-instructor">Instructor transversal</span>
                            <span class="result-status status-approved">Aprueba</span>
                        </div>
                        <div class="result-item">
                            <span class="result-code">A02- Resultado-2</span>
                            <span class="result-instructor">Instructor transversal</span>
                            <span class="result-status status-rejected">No Aprueba</span>
                        </div>
                        <div class="result-item">
                            <span class="result-code">A03- Resultado-3</span>
                            <span class="result-instructor">Instructor transversal</span>
                            <span class="result-status status-approved">Aprueba</span>
                        </div>
                        <div class="result-item">
                            <span class="result-code">A04- Resultado-4</span>
                            <span class="result-instructor">Instructor transversal</span>
                            <span class="result-status status-approved">Aprueba</span>
                        </div>
                    </div>
                </div>

                <!-- Matemáticas -->
                <div class="card">
                    <div class="card-header" onclick="toggleCard('matematicas')">
                        <span class="card-title">MATEMATICAS</span>
                        <svg class="chevron" id="chevron-matematicas" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6,9 12,15 18,9"></polyline>
                        </svg>
                    </div>
                    <div class="card-content" id="content-matematicas">
                        <div class="placeholder-content">Contenido de Matemáticas</div>
                    </div>
                </div>

                <!-- Física -->
                <div class="card">
                    <div class="card-header" onclick="toggleCard('fisica')">
                        <span class="card-title">FISICA</span>
                        <svg class="chevron" id="chevron-fisica" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6,9 12,15 18,9"></polyline>
                        </svg>
                    </div>
                    <div class="card-content" id="content-fisica">
                        <div class="placeholder-content">Contenido de Física</div>
                    </div>
                </div>

                <!-- Investigación -->
                <div class="card">
                    <div class="card-header" onclick="toggleCard('investigacion')">
                        <span class="card-title">INVESTIGACION</span>
                        <svg class="chevron" id="chevron-investigacion" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6,9 12,15 18,9"></polyline>
                        </svg>
                    </div>
                    <div class="card-content" id="content-investigacion">
                        <div class="placeholder-content">Contenido de Investigación</div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" onclick="toggleCard('etica')">
                        <span class="card-title">ETICA Y VALORES</span>
                        <svg class="chevron" id="chevron-etica" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6,9 12,15 18,9"></polyline>
                        </svg>
                    </div>
                    <div class="card-content" id="content-etica">
                        <div class="placeholder-content">Contenido de Ética y Valores</div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="../../assets/js/competencias.js"></script>
    <?php include '../../includes/footer.php'; ?>
</body>
</html>