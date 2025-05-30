
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Fichas de Estudiantes</title>
    <link rel="stylesheet" href="../../assets/css/fichas.css">
    <link rel="stylesheet" href="../../assets/css/header.css">
    <link rel="stylesheet" href="../../assets/css/footer.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<?php include '../../includes/header.php'; ?>

    <div class="container">
        <div class="main-card">
            
            <h1 class="header-title">Ficha {{N° 123456}}</h1>

            <!-- Tabs -->
            <div class="tabs">
                <div class="tab-list">
                    <button class="tab-button active" onclick="openTab(event, 'competencias')">
                        Competencias General
                    </button>
                    <button class="tab-button" onclick="openTab(event, 'trimestre')">
                        N° Trimestre
                    </button>
                    <button class="tab-button" onclick="openTab(event, 'juicios')">
                        Juicios Evaluativos
                    </button>
                </div>

                <!-- Tab Content: Competencias -->
                <div id="competencias" class="tab-content active">
                    <!-- Form Controls -->
                    <div class="form-controls">
                        <div class="form-group">
                            <label for="trimestre">Trimestre (N°)</label>
                            <select id="trimestre" name="trimestre">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="jornada">Jornada(Diurna, Mixta, Nocturna)</label>
                            <select id="jornada" name="jornada">
                                <option value="Diurna">Diurna</option>
                                <option value="Mixta">Mixta</option>
                                <option value="Nocturna">Nocturna</option>
                            </select>
                        </div>
                    </div>

                    <!-- Students List -->
                    <div class="students-list">
                        <!-- Estudiante 1 -->
                        <div class="student-card">
                            <div class="student-content">
                                <!-- Avatar -->
                                <div class="avatar">PP</div>

                                <!-- Student Info -->
                                <div class="student-info">
                                    <div class="student-header">
                                        <h3 class="student-name">Pepito Perez</h3>
                                        <div class="badges">
                                            <span class="badge badge-green" >Competencias</span>
                                            <span class="badge badge-blue">Traslado</span>
                                        </div>
                                    </div>

                                    <!-- Student Details Grid -->
                                    <div class="student-details">
                                        <div class="detail-item">
                                            <label>T. Documento</label>
                                            <p>C.C</p>
                                        </div>
                                        <div class="detail-item">
                                            <label>Num. Documento</label>
                                            <p>1234567890</p>
                                        </div>
                                        <div class="detail-item">
                                            <label>Correo Aprendiz</label>
                                            <p class="email">pepito.perez@email.com</p>
                                        </div>
                                        <div class="detail-item">
                                            <label>N° Teléfono</label>
                                            <p>3001234567</p>
                                        </div>
                                        <div class="detail-item">
                                            <label>Estado</label>
                                            <span class="badge badge-green">Activo</span>
                                        </div>
                                        <div class="detail-item">
                                            <button class="percentage-btn">Porcentaje 85%</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Estudiante 2 -->
                        <div class="student-card">
                            <div class="student-content">
                                <!-- Avatar -->
                                <div class="avatar">PP</div>

                                <!-- Student Info -->
                                <div class="student-info">
                                    <div class="student-header">
                                        <h3 class="student-name">Pepito Perez</h3>
                                        <div class="badges">
                                            <span class="badge badge-green">Competencias</span>
                                            <span class="badge badge-red">Trasladado</span>
                                        </div>
                                    </div>

                                    <!-- Student Details Grid -->
                                    <div class="student-details">
                                        <div class="detail-item">
                                            <label>T. Documento</label>
                                            <p>C.C</p>
                                        </div>
                                        <div class="detail-item">
                                            <label>Num. Documento</label>
                                            <p>1234567891</p>
                                        </div>
                                        <div class="detail-item">
                                            <label>Correo Aprendiz</label>
                                            <p class="email">pepito.perez2@email.com</p>
                                        </div>
                                        <div class="detail-item">
                                            <label>N° Teléfono</label>
                                            <p>3001234568</p>
                                        </div>
                                        <div class="detail-item">
                                            <label>Estado</label>
                                            <span class="badge badge-gray">Inactivo</span>
                                        </div>
                                        <div class="detail-item">
                                            <button class="percentage-btn">Porcentaje 72%</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Estudiante 3 -->
                        <div class="student-card">
                            <div class="student-content">
                                <!-- Avatar -->
                                <div class="avatar">PP</div>

                                <!-- Student Info -->
                                <div class="student-info">
                                    <div class="student-header">
                                        <h3 class="student-name">Pepito Perez</h3>
                                        <div class="badges">
                                            <span class="badge badge-green">Competencias</span>
                                        </div>
                                    </div>

                                    <!-- Student Details Grid -->
                                    <div class="student-details">
                                        <div class="detail-item">
                                            <label>T. Documento</label>
                                            <p>C.C</p>
                                        </div>
                                        <div class="detail-item">
                                            <label>Num. Documento</label>
                                            <p>1234567892</p>
                                        </div>
                                        <div class="detail-item">
                                            <label>Correo Aprendiz</label>
                                            <p class="email">pepito.perez3@email.com</p>
                                        </div>
                                        <div class="detail-item">
                                            <label>N° Teléfono</label>
                                            <p>3001234569</p>
                                        </div>
                                        <div class="detail-item">
                                            <label>Estado</label>
                                            <span class="badge badge-green">Activo</span>
                                        </div>
                                        <div class="detail-item">
                                            <button class="percentage-btn">Porcentaje 90%</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab Content: Trimestre -->
                <div id="trimestre" class="tab-content">
                    <div class="empty-content">
                        <p>Contenido del trimestre - Por implementar</p>
                    </div>
                </div>

                <!-- Tab Content: Juicios -->
                <div id="juicios" class="tab-content">
                    <div class="empty-content">
                        <p>Contenido de juicios evaluativos - Por implementar</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

    <?php include '../../includes/footer.php'; ?>

</html>