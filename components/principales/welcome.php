<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/proyecto-sena/assets/css/welcome.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Welcome</title>
</head>
<body>
<div class="welcome-container">
    <div class="welcome-header">
        <!-- Botón solo visible si hay sesión -->
        <?php if (isset($_SESSION['usuario'])): ?>
        <button class="edit-carousel-btn" onclick="startEditProcess()">
            <i class="fas fa-edit"></i>
            Editar Carrusel
        </button>
        <?php endif; ?>

        <h1 class="welcome-title"><?= $translations['welcome'] ?? 'Bienvenido' ?></h1>
        <p class="welcome-subtitle"><?= $translations['explore_programs'] ?? 'Explora nuestros programas' ?></p>
    </div>

    <div class="carousel-container">
        <button class="nav-button prev" id="prevBtn">
            <i class="fas fa-chevron-left"></i>
        </button>

        <div class="carousel-wrapper">
            <div class="carousel-track" id="carouselTrack">
                <div class="carousel-slide active">
                    <img src="/proyecto-sena/assets/img/software_1.jpeg" alt="Programación" class="carousel-image" id="carousel-img-0">
                    <div class="slide-overlay">
                        <h3 id="carousel-title-0">Título</h3>
                        <p id="carousel-desc-0">Lorem ipsum dolor sit amet, consectetur.</p>
                    </div>
                </div>
                <div class="carousel-slide">
                    <img src="/proyecto-sena/assets/img/software_2.jpeg" alt="Tecnología" class="carousel-image" id="carousel-img-1">
                    <div class="slide-overlay">
                        <h3 id="carousel-title-1">Título</h3>
                        <p id="carousel-desc-1">Lorem ipsum dolor sit amet, consectetur.</p>
                    </div>
                </div>
                <div class="carousel-slide">
                    <img src="/proyecto-sena/assets/img/software_3.jpeg" alt="Innovación" class="carousel-image" id="carousel-img-2">
                    <div class="slide-overlay">
                        <h3 id="carousel-title-2">Título</h3>
                        <p id="carousel-desc-2">Lorem ipsum dolor sit amet, consectetur.</p>
                    </div>
                </div>
            </div>
        </div>

        <button class="nav-button next" id="nextBtn">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>

    <div class="pagination" id="pagination">
        <div class="pagination-dot active" data-slide="0"></div>
        <div class="pagination-dot" data-slide="1"></div>
        <div class="pagination-dot" data-slide="2"></div>
    </div>

    <div class="features-grid">
        <div class="feature-card">
            <i class="fas fa-code"></i>
            <h3>Desarrollo</h3>
            <p>Aprende a construir software moderno con herramientas actuales.</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-database"></i>
            <h3>Base de Datos</h3>
            <p>Domina el diseño y administración de bases de datos.</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-mobile-alt"></i>
            <h3>Aplicaciones Móviles</h3>
            <p>Desarrolla apps para Android y otras plataformas móviles.</p>
        </div>
    </div>
</div>

<!-- Modales de edición -->
<?php for ($i = 1; $i <= 3; $i++): ?>
<div id="editModal<?= $i ?>" class="edit-modal">
    <div class="edit-modal-content">
        <div class="edit-modal-header">
            <h2>Editar Slide <?= $i ?></h2>
            <button class="edit-close-btn" onclick="closeModal(<?= $i ?>)">&times;</button>
        </div>
        <form class="edit-form">
            <div class="edit-form-group">
                <label>Imagen:</label>
                <input type="file" id="image<?= $i ?>" accept="image/*" class="edit-file-input">
            </div>
            <div class="edit-form-group">
                <label>Título (Español):</label>
                <input type="text" id="titleEs<?= $i ?>" class="edit-text-input">
            </div>
            <div class="edit-form-group">
                <label>Título (Inglés):</label>
                <input type="text" id="titleEn<?= $i ?>" class="edit-text-input">
            </div>
            <div class="edit-form-group">
                <label>Descripción (Español):</label>
                <textarea id="descEs<?= $i ?>" class="edit-textarea"></textarea>
            </div>
            <div class="edit-form-group">
                <label>Descripción (Inglés):</label>
                <textarea id="descEn<?= $i ?>" class="edit-textarea"></textarea>
            </div>
            <div class="edit-form-actions">
                <button type="button" onclick="saveSlide(<?= $i ?>)" class="edit-save-btn">
                    <?= $i < 3 ? 'Guardar y Continuar' : 'Finalizar' ?>
                </button>
                <button type="button" onclick="<?= $i < 3 ? 'cancelAndNext(' . $i . ')' : 'closeModal(' . $i . ')' ?>" class="edit-cancel-btn">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>
<?php endfor; ?>

<!-- Script -->
<script src="/proyecto-sena/assets/js/welcome.js"></script>
</body>
</html>
