<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../../functions/historial.php';
require_once __DIR__ . '/../../db/conexion.php';

// Determinar idioma
$idioma = $_SESSION['lang'] ?? 'es';
$t = include __DIR__ . '/../../lang/' . $idioma . '.php';

// Cargar slides desde la base de datos
$slides = [];
$query = $conn->query("SELECT * FROM slider ORDER BY id DESC LIMIT 3");

while ($row = $query->fetch_assoc()) {
    $slides[] = $row;
}

// Rellenar si hay menos de 3
while (count($slides) < 3) {
    $slides[] = [
        'id_slider' => '',
        'titulo_es' => '',
        'titulo_en' => '',
        'descripcion_es' => '',
        'descripcion_en' => '',
        'imagen' => ''
    ];
}
?>
<!DOCTYPE html>
<html lang="<?= $idioma ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $t['welcome'] ?? 'Bienvenido' ?></title>
    <link rel="stylesheet" href="/proyecto-sena/assets/css/welcome.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<div class="welcome-container">
    <div class="welcome-header">
        <?php if (isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] === 'administrador'): ?>
        <button class="edit-carousel-btn" onclick="startEditProcess()">
            <i class="fas fa-edit"></i> <?= $t['edit_carousel'] ?? 'Editar Carrusel' ?>
        </button>
        <?php endif; ?>

        <h1 class="welcome-title"><?= $t['welcome'] ?? 'Bienvenido' ?></h1>
        <p class="welcome-subtitle"><?= $t['explore_programs'] ?? 'Explora nuestros programas' ?></p>
    </div>

    <div class="carousel-container">
        <button class="nav-button prev" id="prevBtn"><i class="fas fa-chevron-left"></i></button>
        <div class="carousel-wrapper">
            <div class="carousel-track" id="carouselTrack">
                <?php foreach ($slides as $index => $row): 
                    $titulo = $idioma === 'en' ? $row['titulo_en'] : $row['titulo_es'];
                    $descripcion = $idioma === 'en' ? $row['descripcion_en'] : $row['descripcion_es'];
                ?>
                <div class="carousel-slide<?= $index === 0 ? ' active' : '' ?>">
                    <?php if (!empty($row['imagen'])): ?>
                        <img src="/proyecto-sena/assets/slider/<?= htmlspecialchars($row['imagen']) ?>" alt="Slide" class="carousel-image">
                    <?php endif; ?>
                    <div class="slide-overlay">
                        <h3><?= htmlspecialchars($titulo) ?></h3>
                        <p><?= htmlspecialchars($descripcion) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <button class="nav-button next" id="nextBtn"><i class="fas fa-chevron-right"></i></button>
    </div>

    <div class="pagination" id="pagination">
        <?php foreach ($slides as $index => $_): ?>
            <div class="pagination-dot<?= $index === 0 ? ' active' : '' ?>" data-slide="<?= $index ?>"></div>
        <?php endforeach; ?>
    </div>
    
    <div class="features-grid">
        <div class="feature-card">
            <i class="fas fa-code"></i>
            <h3><?= $t['development'] ?></h3>
            <p><?= $t['development_desc'] ?></p>
        </div>
        <div class="feature-card">
            <i class="fas fa-database"></i>
            <h3><?= $t['database'] ?></h3>
            <p><?= $t['database_desc'] ?></p>
        </div>
        <div class="feature-card">
            <i class="fas fa-mobile-alt"></i>
            <h3><?= $t['mobile_apps'] ?></h3>
            <p><?= $t['mobile_apps_desc'] ?></p>
        </div>
    </div>

    <!-- Modales de edición (solo para administrador) -->
    <?php if (isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] === 'administrador'): ?>
        <?php for ($i = 0; $i < 3; $i++): 
            $slide = $slides[$i];
            $id = $slide['id_slider'] ?? '';
        ?>
        <div id="editModal<?= $i + 1 ?>" class="edit-modal">
            <div class="edit-modal-content">
                <div class="edit-modal-header">
                    <h2><?= $t['edit_slide'] ?? 'Editar Slide' ?> <?= $i + 1 ?></h2>
                    <button class="edit-close-btn" onclick="closeModal(<?= $i + 1 ?>)">&times;</button>
                </div>
                <form class="edit-form" action="/proyecto-sena/functions/guardar_slider.php?page=components/principales/welcome&edit=1" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id_slider" value="<?= htmlspecialchars($id) ?>">

                    <div class="edit-form-group">
                        <label><?= $t['image'] ?? 'Imagen' ?>:</label>
                        <input type="file" name="imagen" accept="image/*" class="edit-file-input">
                        <?php if (!empty($slide['imagen'])): ?>
                            <p><?= $t['current'] ?? 'Actual' ?>: <strong><?= htmlspecialchars($slide['imagen']) ?></strong></p>
                            <img src="/proyecto-sena/assets/slider/<?= htmlspecialchars($slide['imagen']) ?>" width="100">
                        <?php endif; ?>
                    </div>

                    <div class="edit-form-group">
                        <label><?= $t['title_es'] ?? 'Título (Español)' ?>:</label>
                        <input type="text" name="titulo_es" class="edit-text-input" value="<?= htmlspecialchars($slide['titulo_es']) ?>">
                    </div>
                    <div class="edit-form-group">
                        <label><?= $t['title_en'] ?? 'Título (Inglés)' ?>:</label>
                        <input type="text" name="titulo_en" class="edit-text-input" value="<?= htmlspecialchars($slide['titulo_en']) ?>">
                    </div>
                    <div class="edit-form-group">
                        <label><?= $t['desc_es'] ?? 'Descripción (Español)' ?>:</label>
                        <textarea name="descripcion_es" class="edit-textarea"><?= htmlspecialchars($slide['descripcion_es']) ?></textarea>
                    </div>
                    <div class="edit-form-group">
                        <label><?= $t['desc_en'] ?? 'Descripción (Inglés)' ?>:</label>
                        <textarea name="descripcion_en" class="edit-textarea"><?= htmlspecialchars($slide['descripcion_en']) ?></textarea>
                    </div>

                    <div class="edit-form-actions">
                        <button type="submit" name="<?= $i < 2 ? 'continuar' : 'finalizar' ?>" class="edit-save-btn">
                            <?= $i < 2 ? ($t['save_continue'] ?? 'Guardar y Continuar') : ($t['finish'] ?? 'Finalizar') ?>
                        </button>
                        <button type="button" onclick="<?= $i < 2 ? 'cancelAndNext(' . ($i + 1) . ')' : 'closeModal(' . ($i + 1) . ')' ?>" class="edit-cancel-btn">
                            <?= $t['cancel'] ?? 'Cancelar' ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php endfor; ?>
    <?php endif; ?>
</div>

<script src="/proyecto-sena/assets/js/welcome.js"></script>

<?php if (isset($_GET['edit']) && isset($_SESSION['slide_step'])): ?>
<script>
    window.addEventListener('DOMContentLoaded', () => {
        openModal(<?= (int) $_SESSION['slide_step'] ?>);
    });
</script>
<?php endif; ?>
</body>
</html>
