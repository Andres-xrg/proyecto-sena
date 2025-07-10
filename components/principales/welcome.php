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
        <!-- BOTÓN AGREGADO -->
        <button class="edit-carousel-btn" onclick="startEditProcess()">
            <i class="fas fa-edit"></i>
            Editar Carrusel
        </button>
        <!-- FIN BOTÓN -->
        
        <h1 class="welcome-title"><?= $translations['welcome'] ?></h1>
        <p class="welcome-subtitle"><?= $translations['explore_programs'] ?></p>
    </div>
        
    <div class="carousel-container">
        <button class="nav-button prev" id="prevBtn">
            <i class="fas fa-chevron-left"></i>
        </button>
                
        <div class="carousel-wrapper">
            <div class="carousel-track" id="carouselTrack">
                <div class="carousel-slide active">
                    <img src="assets/img/software_1.jpeg" alt="Programación" class="carousel-image" id="carousel-img-0">
                    <div class="slide-overlay">
                        <h3 id="carousel-title-0">Titulo</h3>
                        <p id="carousel-desc-0">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Enim aspernatur.</p>
                    </div>
                </div>
                <div class="carousel-slide">
                    <img src="assets/img/software_2.jpeg" alt="Tecnología" class="carousel-image" id="carousel-img-1">
                    <div class="slide-overlay">
                        <h3 id="carousel-title-1">Titulo</h3>
                        <p id="carousel-desc-1">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Enim aspernatur.</p>
                       </div>
                </div>
                <div class="carousel-slide">
                    <img src="assets/img/software_3.jpeg" alt="Innovación" class="carousel-image" id="carousel-img-2">
                    <div class="slide-overlay">
                        <h3 id="carousel-title-2">Titulo</h3>
                        <p id="carousel-desc-2">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Enim aspernatur.</p>
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
            <h3>Titulo</h3>
            <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Enim aspernatur.</p>                
        </div>
        <div class="feature-card">
            <i class="fas fa-database"></i>
            <h3>Titulo</h3>
            <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Enim aspernatur.</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-mobile-alt"></i>
            <h3>Titulo</h3>
            <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Enim aspernatur.</p>                
        </div>
    </div>
</div>

<!-- MODALES AGREGADOS -->
<!-- MODALES COMPLETOS CON LÓGICA ACTUALIZADA -->

<!-- Modal Slide 1 -->
<div id="editModal1" class="edit-modal">
    <div class="edit-modal-content">
        <div class="edit-modal-header">
            <h2>Editar Slide 1</h2>
            <button class="edit-close-btn" onclick="closeModal(1)">&times;</button>
        </div>
        <form class="edit-form">
            <div class="edit-form-group">
                <label>Imagen:</label>
                <input type="file" id="image1" accept="image/*" class="edit-file-input">
            </div>
            <div class="edit-form-group">
                <label>Título (Español):</label>
                <input type="text" id="titleEs1" class="edit-text-input">
            </div>
            <div class="edit-form-group">
                <label>Título (Inglés):</label>
                <input type="text" id="titleEn1" class="edit-text-input">
            </div>
            <div class="edit-form-group">
                <label>Descripción (Español):</label>
                <textarea id="descEs1" class="edit-textarea"></textarea>
            </div>
            <div class="edit-form-group">
                <label>Descripción (Inglés):</label>
                <textarea id="descEn1" class="edit-textarea"></textarea>
            </div>
            <div class="edit-form-actions">
                <button type="button" onclick="saveSlide(1)" class="edit-save-btn">Guardar y Continuar</button>
                <button type="button" onclick="cancelAndNext(1)" class="edit-cancel-btn">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Slide 2 -->
<div id="editModal2" class="edit-modal">
    <div class="edit-modal-content">
        <div class="edit-modal-header">
            <h2>Editar Slide 2</h2>
            <button class="edit-close-btn" onclick="closeModal(2)">&times;</button>
        </div>
        <form class="edit-form">
            <div class="edit-form-group">
                <label>Imagen:</label>
                <input type="file" id="image2" accept="image/*" class="edit-file-input">
            </div>
            <div class="edit-form-group">
                <label>Título (Español):</label>
                <input type="text" id="titleEs2" class="edit-text-input">
            </div>
            <div class="edit-form-group">
                <label>Título (Inglés):</label>
                <input type="text" id="titleEn2" class="edit-text-input">
            </div>
            <div class="edit-form-group">
                <label>Descripción (Español):</label>
                <textarea id="descEs2" class="edit-textarea"></textarea>
            </div>
            <div class="edit-form-group">
                <label>Descripción (Inglés):</label>
                <textarea id="descEn2" class="edit-textarea"></textarea>
            </div>
            <div class="edit-form-actions">
                <button type="button" onclick="saveSlide(2)" class="edit-save-btn">Guardar y Continuar</button>
                <button type="button" onclick="cancelAndNext(2)" class="edit-cancel-btn">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Slide 3 -->
<div id="editModal3" class="edit-modal">
    <div class="edit-modal-content">
        <div class="edit-modal-header">
            <h2>Editar Slide 3</h2>
            <button class="edit-close-btn" onclick="closeModal(3)">&times;</button>
        </div>
        <form class="edit-form">
            <div class="edit-form-group">
                <label>Imagen:</label>
                <input type="file" id="image3" accept="image/*" class="edit-file-input">
            </div>
            <div class="edit-form-group">
                <label>Título (Español):</label>
                <input type="text" id="titleEs3" class="edit-text-input">
            </div>
            <div class="edit-form-group">
                <label>Título (Inglés):</label>
                <input type="text" id="titleEn3" class="edit-text-input">
            </div>
            <div class="edit-form-group">
                <label>Descripción (Español):</label>
                <textarea id="descEs3" class="edit-textarea"></textarea>
            </div>
            <div class="edit-form-group">
                <label>Descripción (Inglés):</label>
                <textarea id="descEn3" class="edit-textarea"></textarea>
            </div>
            <div class="edit-form-actions">
                <button type="button" onclick="saveSlide(3)" class="edit-save-btn">Finalizar</button>
                <button type="button" onclick="closeModal(3)" class="edit-cancel-btn">Cancelar</button>
            </div>
        </form>
    </div>
</div>
<!-- FIN MODALES -->
<script src="assets/js/welcome.js"></script>
</body>
</html>