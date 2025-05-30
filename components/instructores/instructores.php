<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructores</title>
    <link rel="stylesheet" href="../../assets/css/instructores.css">
    <link rel="stylesheet" href="../../assets/css/header.css">
    <link rel="stylesheet" href="../../assets/css/footer.css">
</head>
<body>
    
    <?php include '../../includes/header.php'; ?>
    <div class="container">
        
        <div class="titulo">
            <h1 class="title">Instructores</h1>
            <button class="btn-registrar" onclick="registrarInstructor()">Registrar Instructor</button>
        </div>

        <!-- Lista de Instructores -->
        <div class="instructores-list">
            <!-- Instructor 1 -->
            <div class="instructor-card">
                <div class="instructor-content">
                    <!-- Avatar -->
                    <div class="avatar">
                        <div class="avatar-icon">👤</div>
                    </div>

                    <!-- Info del Instructor -->
                    <div class="instructor-info">
                        <div class="instructor-header">
                            <h3 class="instructor-name">Instructor-1</h3>
                            <button class="btn-estado btn-deshabilitar" onclick="toggleInstructor(this, 'Instructor-1')">
                                Deshabilitar
                            </button>
                        </div>

                        <!-- Campos de información -->
                        <div class="instructor-details">
                            <div class="detail-item">
                                <label>T. Documento</label>
                                <span>C.C</span>
                            </div>
                            <div class="detail-item">
                                <label>Num. Documento</label>
                                <span>12345678</span>
                            </div>
                            <div class="detail-item">
                                <label>Correo Instructor</label>
                                <span>instructor1@sena.edu.co</span>
                            </div>
                            <div class="detail-item">
                                <label>N° Teléfono</label>
                                <span>3001234567</span>
                            </div>
                            <div class="detail-item">
                                <label>Estado</label>
                                <span>Activo</span>
                            </div>
                            <div class="detail-item">
                                <label>Jefe de ficha</label>
                                <span>Sí</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Instructor 2 (Deshabilitado) -->
            <div class="instructor-card disabled">
                <div class="instructor-content">
                    <!-- Avatar -->
                    <div class="avatar">
                        <div class="avatar-icon">👤</div>
                    </div>

                    <!-- Info del Instructor -->
                    <div class="instructor-info">
                        <div class="instructor-header">
                            <h3 class="instructor-name">Instructor-2</h3>
                            <button class="btn-estado btn-habilitar" onclick="toggleInstructor(this, 'Instructor-2')">
                                Habilitar
                            </button>
                        </div>

                        <!-- Campos de información -->
                        <div class="instructor-details">
                            <div class="detail-item">
                                <label>T. Documento</label>
                                <span>C.C</span>
                            </div>
                            <div class="detail-item">
                                <label>Num. Documento</label>
                                <span>87654321</span>
                            </div>
                            <div class="detail-item">
                                <label>Correo Instructor</label>
                                <span>instructor2@sena.edu.co</span>
                            </div>
                            <div class="detail-item">
                                <label>N° Teléfono</label>
                                <span>3009876543</span>
                            </div>
                            <div class="detail-item">
                                <label>Estado</label>
                                <span>Inactivo</span>
                            </div>
                            <div class="detail-item">
                                <label>Jefe de ficha</label>
                                <span>No</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Instructor 3 -->
            <div class="instructor-card">
                <div class="instructor-content">
                    <!-- Avatar -->
                    <div class="avatar">
                        <div class="avatar-icon">👤</div>
                    </div>

                    <!-- Info del Instructor -->
                    <div class="instructor-info">
                        <div class="instructor-header">
                            <h3 class="instructor-name">Instructor-3</h3>
                            <button class="btn-estado btn-deshabilitar" onclick="toggleInstructor(this, 'Instructor-3')">
                                Deshabilitar
                            </button>
                        </div>

                        <!-- Campos de información -->
                        <div class="instructor-details">
                            <div class="detail-item">
                                <label>T. Documento</label>
                                <span>C.C</span>
                            </div>
                            <div class="detail-item">
                                <label>Num. Documento</label>
                                <span>11223344</span>
                            </div>
                            <div class="detail-item">
                                <label>Correo Instructor</label>
                                <span>instructor3@sena.edu.co</span>
                            </div>
                            <div class="detail-item">
                                <label>N° Teléfono</label>
                                <span>3005566778</span>
                            </div>
                            <div class="detail-item">
                                <label>Estado</label>
                                <span>Activo</span>
                            </div>
                            <div class="detail-item">
                                <label>Jefe de ficha</label>
                                <span>No</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../../assets/js/instructores.js"></script>
</body>
    <?php include '../../includes/footer.php'; ?>

</html>