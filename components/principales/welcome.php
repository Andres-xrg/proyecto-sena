
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/welcome.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <title>Welcome</title>
</head>
<body>

<div class="welcome-container">
    <div class="welcome-header">
        <h1 class="welcome-title"><?= $translations['welcome'] ?></h1>
        <p class="welcome-subtitle">Explora nuestros programas de formación tecnológica</p>
    </div>
    
    <div class="carousel-container">
        <button class="nav-button prev" id="prevBtn">
            <i class="fas fa-chevron-left"></i>
        </button>
        
        <div class="carousel-wrapper">
            <div class="carousel-track" id="carouselTrack">
                <div class="carousel-slide active">
                    <img src="assets/img/software_1.jpeg" alt="Programación" class="carousel-image">
                    <div class="slide-overlay">
                        <h3>Desarrollo de Software</h3>
                        <p>Aprende los lenguajes más demandados</p>
                    </div>
                </div>
                <div class="carousel-slide">
                    <img src="assets/img/software_2.jpeg" alt="Tecnología" class="carousel-image">
                    <div class="slide-overlay">
                        <h3>Tecnologías Emergentes</h3>
                        <p>Mantente al día con las últimas tendencias</p>
                    </div>
                </div>
                <div class="carousel-slide">
                    <img src="assets/img/software_3.jpeg" alt="Innovación" class="carousel-image">
                    <div class="slide-overlay">
                        <h3>Innovación Digital</h3>
                        <p>Transforma ideas en soluciones reales</p>
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
            <h3>Programación</h3>
            <p>Domina los lenguajes más populares del mercado</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-database"></i>
            <h3>Bases de Datos</h3>
            <p>Gestiona información de manera eficiente</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-mobile-alt"></i>
            <h3>Desarrollo Móvil</h3>
            <p>Crea aplicaciones para dispositivos móviles</p>
        </div>
    </div>
</div>

<script src="assets/js/welcome.js"></script>
</body>
</html>