<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Rutas absolutas seguras -->
    <link rel="stylesheet" href="/proyecto-sena/assets/css/header.css">
    <link rel="stylesheet" href="/proyecto-sena/assets/css/footer.css">
    <link rel="stylesheet" href="/proyecto-sena/assets/css/programas_formacion.css">

    <title>SENA - Plataforma Educativa</title>
</head>
<body>
    <main class="programs-main-content">
        <div class="programs-content-area">
            <div class="programs-grid">
                <!-- Tecnólogo -->
                <div class="program-card">
                    <div>
                        <div class="card-header" onclick="tecnologo()">
                            <div class="card-icon"></div>
                            <div>
                                <div class="card-title"><?= $translations['technologist'] ?></div>
                                <div class="card-subtitle"><?= $translations['software_dev'] ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="image-section">
                        <div class="photos-grid">
                            <div class="photo-placeholder">
                                <img src="assets/img/tecnologo.jpg" alt="Tecnólogo">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Técnico -->
                <div class="program-card">
                    <div>
                        <div class="card-header" onclick="tecnico()">
                            <div class="card-icon"></div>
                            <div>
                                <div class="card-title"><?= $translations['technician'] ?></div>
                                <div class="card-subtitle"><?= $translations['software_programming'] ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="image-section">
                        <div class="photos-grid">
                            <div class="photo-placeholder">
                                <img src="assets/img/tecnico.jpg" alt="Técnico">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de registro -->
        <aside class="register-buttons-panel">
            <button class="register-btn" onclick="registrarFicha()">
                <div class="register-btn-icon"></div>
                <?= $translations['register_ficha'] ?>
            </button>
            <button class="register-btn" onclick="registrarInstructor()">
                <div class="register-btn-icon"></div>
                <?= $translations['register_instructor'] ?>
            </button>
            <button class="register-btn" onclick="registrarAprendiz()">
                <div class="register-btn-icon"></div>
                <?= $translations['register_apprentices'] ?>
            </button>
        </aside>
    </main>

    <!-- Script de registros -->
    <script src="assets/js/registros.js"></script>
</body>
</html>
