<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/programas_formacion.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <title>SENA - Plataforma Educativa</title>

</head>
<body>
    <main class="main-content">
        <div class="content-area">
            <div class="programs-grid">
                <!-- Tecnólogo Card -->
                <div class="program-card">
                    <!-- Rectángulo superior con contenido -->
                    <div>
                        <div class="card-header" onclick="tecnologo()">
                            <div class="card-icon" ></div>
                            <div>
                                <div class="card-title" >Tecnólogo</div>
                                <div class="card-subtitle">Análisis y Desarrollo de Software</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Rectángulo inferior separado para imágenes -->
                    <div class="image-section">
                        <div class="photos-grid">
                            <img class="photo-placeholder" src="assets/img/tecnologo.jpg" alt="">
                        </div>
                    </div>
                </div>

                <!-- Técnico Card -->
                <div class="program-card">
                    <!-- Rectángulo superior con contenido -->
                    <div>
                        <div class="card-header" onclick="tecnico()">
                            <div class="card-icon"></div>
                            <div>
                                <div class="card-title">Técnico</div>
                                <div class="card-subtitle">Programación de Software</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Rectángulo inferior separado para imágenes -->
                    <div class="image-section">
                        <div class="photos-grid">
                            <img class="photo-placeholder" src="assets/img/tecnico.jpg" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <aside class="sidebar">
            <button class="sidebar-button" onclick="registrarFicha()">
                <div class="button-icon" ></div>
                Registrar Ficha
            </button>
            <button class="sidebar-button" onclick="registrarInstructor()">
                <div class="button-icon" ></div>
                Registrar Instructor
            </button>
            <button class="sidebar-button" onclick="registrarAprendiz()">
                <div class="button-icon"></div>
                Registrar Aprendiz
            </button>
        </aside>
    </main>
</body>
    <script src="assets/js/registros.js"></script>
</html>