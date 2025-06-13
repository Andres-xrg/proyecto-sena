<!-- <?php
// require_once '../../db/conexion.php'; // ajusta la ruta según tu estructura

// Obtener el ID de la ficha desde la URL
$id_ficha = $_GET['id'] ?? null;

if (!$id_ficha) {
    echo "Ficha no especificada.";
    exit;
}

// Obtener datos de la ficha
$sql = "SELECT * FROM fichas WHERE Id_ficha = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_ficha);
$stmt->execute();
$resultado = $stmt->get_result();
$ficha = $resultado->fetch_assoc();

// Obtener aprendices asociados a esta ficha
$sql_aprendices = "
SELECT a.*, u.Email, u.N_Telefono
FROM ficha_aprendiz fa
JOIN aprendices a ON fa.Id_aprendiz = a.Id_aprendiz
JOIN usuarios u ON a.Id_usuario = u.Id_usuario
WHERE fa.Id_ficha = ?";
$stmt2 = $conn->prepare($sql_aprendices);
$stmt2->bind_param("i", $id_ficha);
$stmt2->execute();
$aprendices = $stmt2->get_result();
?> -->

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Fichas de Estudiantes</title>
    <link rel="stylesheet" href="assets/css/fichas.css">
     <link rel="stylesheet" href="assets/css/footer.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

    <div class="container">
        <div class="main-card">

            <h1 class="header-title">Ficha {{N° 123456}}</h1>

            <!-- Tabs -->
            <div class="tabs">
                <div class="tab-list">
                    <button class="tab-button active" onclick="competencias_generales()">
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
                                <option value="5">5</option>
                                <option value="6">6</option>
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
                    <!-- Estudiante 1 -->
                        <div class="student-card">
                            <div class="student-content">
                                <div class="avatar">PP</div>
                                <div class="student-info">
                                    <div class="student-header">
                                        <h3 class="student-name">Pepito Perez</h3>
                                        <div class="badges">
                                            <button class="badge badge-green" onclick="competencias_aprendiz()">Competencias</button>
                                            <button class="badge badge-blue traslado-btn" onclick="cambiarEstadoTraslado(this)">Traslado</button>
                                        </div>
                                    </div>
                                    <div class="student-details">
                                        <div class="detail-item"><label>T. Documento</label><p>C.C</p></div>
                                        <div class="detail-item"><label>Num. Documento</label><p>1234567890</p></div>
                                        <div class="detail-item"><label>Correo Aprendiz</label><p class="email">pepito.perez@email.com</p></div>
                                        <div class="detail-item"><label>N° Teléfono</label><p>3001234567</p></div>
                                        <div class="detail-item">
                                            <label>Estado</label>
                                            <button class="badge badge-green estado-btn" onclick="cambiarEstadoActivo(this)">Activo</button>
                                        </div>
                                        <div class="detail-item"><button class="percentage-btn">Porcentaje 85%</button></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Estudiante 2 -->
                        <div class="student-card">
                                <div class="student-content">
                                    <div class="avatar">PP</div>
                                    <div class="student-info">
                                        <div class="student-header">
                                            <h3 class="student-name">Pepito Perez</h3>
                                            <div class="badges">
                                                <button class="badge badge-green" onclick="competencias_aprendiz()">Competencias</button>
                                                <button class="badge badge-red traslado-btn" onclick="cambiarEstadoTraslado(this)">Trasladado</button>
                                            </div>
                                        </div>
                                        <div class="student-details">
                                            <div class="detail-item"><label>T. Documento</label><p>C.C</p></div>
                                            <div class="detail-item"><label>Num. Documento</label><p>1234567891</p></div>
                                            <div class="detail-item"><label>Correo Aprendiz</label><p class="email">pepito.perez2@email.com</p></div>
                                            <div class="detail-item"><label>N° Teléfono</label><p>3001234568</p></div>
                                            <div class="detail-item">
                                                <label>Estado</label>
                                            <button class="badge badge-gray estado-btn" onclick="cambiarEstadoActivo(this)">Inactivo</button>
                                            </div>
                                            <div class="detail-item"><button class="percentage-btn">Porcentaje 72%</button></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <!-- Estudiante 3 -->
                        <div class="student-card">
                            <div class="student-content">
                                <div class="avatar">PP</div>
                                <div class="student-info">
                                    <div class="student-header">
                                        <h3 class="student-name">Pepito Perez</h3>
                                        <div class="badges">
                                            <button class="badge badge-green" onclick="competencias_aprendiz()">Competencias</button>
                                            <button class="badge badge-blue traslado-btn" onclick="cambiarEstadoTraslado(this)">Traslado</button>
                                        </div>
                                    </div>
                                    <div class="student-details">
                                        <div class="detail-item"><label>T. Documento</label><p>C.C</p></div>
                                        <div class="detail-item"><label>Num. Documento</label><p>1234567892</p></div>
                                        <div class="detail-item"><label>Correo Aprendiz</label><p class="email">pepito.perez3@email.com</p></div>
                                        <div class="detail-item"><label>N° Teléfono</label><p>3001234569</p></div>
                                        <div class="detail-item">
                                            <label>Estado</label>
                                            <button class="badge badge-green estado-btn" onclick="cambiarEstadoActivo(this)">Activo</button>
                                        </div>
                                        <div class="detail-item"><button class="percentage-btn">Porcentaje 90%</button></div>
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
    <script src="assets/js/competencias.js"></script>
</html>