<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/welcome.css">
    <link rel="stylesheet" href="../../assets/css/header.css">
    <link rel="stylesheet" href="../../assets/css/footer.css">
    <title>Welcome</title>
</head>
<?php include '../../includes/header-secundario.php'; ?>
<body>
<div class="welcome-container">
    <h1 class="welcome-title">Bienvenido</h1>
    
    <div class="carousel-container">
        <button class="nav-button prev">
            <img src="/proyecto-sena/assets/img/arrow-right.png" alt="Anterior" class="arrow">
        </button>
        
        <div class="carousel-wrapper">
            <div class="side-image"></div>
            <div class="main-image"></div>
            <div class="side-image"></div>
        </div>
        
        <button class="nav-button next">
            <img src="/proyecto-sena/assets/img/arrow-left.png" alt="Siguiente" class="arrow">
        </button>
    </div>
    
    <div class="pagination">
        <div class="pagination-dot"></div>
        <div class="pagination-dot"></div>
        <div class="pagination-dot"></div>
    </div>
</div>

<script src="../../assets/js/welcome.js"></script>
</body>
<?php include '../../includes/footer.php'; ?>
</html>