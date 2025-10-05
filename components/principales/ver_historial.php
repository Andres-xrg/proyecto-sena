<?php
session_start();

require_once __DIR__ . '/../../functions/autenticacion_login.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: /proyecto-sena/components/principales/login.php");
    exit();
}
require_once __DIR__ . '/../../functions/lang.php'; // Asegúrate de incluir esto antes de cualquier uso de $translations
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../functions/historial.php';
date_default_timezone_set('America/Bogota');
$sql = "SELECT h.*, u.nombre, u.apellido 
        FROM historial_usuarios h 
        LEFT JOIN usuarios u ON h.usuario_id = u.Id_usuario 
        ORDER BY h.fecha DESC";

$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $translations['activity_history'] ?? 'Historial de Actividades' ?></title>
    <link rel="stylesheet" href="../../assets/css/Historial.css">
    <link rel="icon" href="/proyecto-sena/assets/img/logo-sena.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="form-header">
        <img src="../../assets/img/back-arrow.png" alt="Regresar" class="back-arrow" onclick="goBack()">
    </div>
    <div class="container-fluid">
        <div class="main-container fade-in">
            <div class="header-section">
                <div class="header-content">
                    <h1 class="page-title">
                        <i class="fas fa-history"></i>
                        <?= $translations['activity_history'] ?? 'Historial de Actividades' ?>
                    </h1>
                    <p class="page-subtitle">
                        <?= $translations['system_activity_log'] ?? 'Registro completo de todas las actividades del sistema' ?>
                    </p>
                </div>
            </div>

            <div class="controls-section">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="search-box">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" class="search-input" placeholder="<?= $translations['search_placeholder'] ?? 'Buscar por usuario, acción o descripción...' ?>" id="searchInput">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="filter-buttons">
                            <button class="filter-btn active" data-filter="all"><?= $translations['all'] ?? 'Todas' ?></button>
                            <button class="filter-btn" data-filter="login">Login</button>
                            <button class="filter-btn" data-filter="logout">Logout</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="controls-section">
                <div class="stats-cards">
                    <div class="stat-card">
                        <div class="stat-number"><?= $resultado->num_rows ?></div>
                        <div class="stat-label"><?= $translations['total_activities'] ?? 'Total Actividades' ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">
                            <?php 
                            $today_sql = "SELECT COUNT(*) as count FROM historial_usuarios WHERE DATE(fecha) = CURDATE()";
                            $today_result = $conn->query($today_sql);
                            echo $today_result->fetch_assoc()['count'];
                            ?>
                        </div>
                        <div class="stat-label"><?= $translations['today'] ?? 'Hoy' ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">
                            <?php 
                            $users_sql = "SELECT COUNT(DISTINCT usuario_id) as count FROM historial_usuarios";
                            $users_result = $conn->query($users_sql);
                            echo $users_result->fetch_assoc()['count'];
                            ?>
                        </div>
                        <div class="stat-label"><?= $translations['active_users'] ?? 'Usuarios Activos' ?></div>
                    </div>
                </div>
            </div>

            <div class="table-container">
                <?php if($resultado->num_rows > 0): ?>
                <table class="activity-table" id="activityTable">
                    <thead>
                        <tr>
                            <th><i class="fas fa-user"></i> <?= $translations['user'] ?? 'Usuario' ?></th>
                            <th><i class="fas fa-bolt"></i> <?= $translations['action'] ?? 'Acción' ?></th>
                            <th><i class="fas fa-info-circle"></i> <?= $translations['description'] ?? 'Descripción' ?></th>
                            <th><i class="fas fa-calendar"></i> <?= $translations['date'] ?? 'Fecha' ?></th>
                        </tr>
                    </thead>
                    <tbody>
                <?php  
                $resultado->data_seek(0);
                while($row = $resultado->fetch_assoc()): 
                    $nombre = $row['nombre'] ?? '';
                    $apellido = $row['apellido'] ?? '';
                    $accion = $row['accion'] ?? 'Desconocida';
                    $descripcion = $row['descripcion'] ?? 'Sin descripción';
                    $inicial_nombre = $nombre !== '' ? substr($nombre, 0, 1) : '?';
                    $inicial_apellido = $apellido !== '' ? substr($apellido, 0, 1) : '?';
                    $initials = strtoupper($inicial_nombre . $inicial_apellido);
                    $action_class = 'action-' . strtolower($accion);
                    $action_icon = match (strtolower($accion)) {
                        'login'  => 'fas fa-sign-in-alt',
                        'logout' => 'fas fa-sign-out-alt',
                        'create' => 'fas fa-plus',
                        'update' => 'fas fa-edit',
                        'delete' => 'fas fa-trash',
                        default  => 'fas fa-cog',
                    };
                ?>
                <tr data-action="<?= strtolower($accion) ?>">
                    <td>
                        <div class="user-info">
                            <div class="user-avatar"><?= $initials ?></div>
                            <div class="user-details">
                                <?php 
                                $nombreCompleto = trim($nombre . ' ' . $apellido);
                                echo '<h6>' . ($nombreCompleto !== '' ? htmlspecialchars($nombreCompleto) : 'Anónimo') . '</h6>';
                                ?>
                                <small>ID: <?= htmlspecialchars((string)($row['usuario_id'] ?? '0')) ?></small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="action-badge <?= $action_class ?>">
                            <i class="<?= $action_icon ?>"></i>
                            <?= htmlspecialchars($accion) ?>
                        </span>
                    </td>
                    <td>
                        <div class="description-text" title="<?= htmlspecialchars($descripcion) ?>">
                            <?= htmlspecialchars($descripcion) ?>
                        </div>
                    </td>
                    <td>
                        <div class="date-info">
                            <?= isset($row['fecha']) ? date('d/m/Y H:i', strtotime($row['fecha'])) : 'Sin fecha' ?>
                        </div>
                        <div class="date-relative">
                            <?php
                            if (isset($row['fecha'])) {
                                $time_diff = time() - strtotime($row['fecha']);
                                if ($time_diff < 60) {
                                    echo "Hace $time_diff segundos";
                                } elseif ($time_diff < 3600) {
                                    echo "Hace " . floor($time_diff/60) . " minutos";
                                } elseif ($time_diff < 86400) {
                                    echo "Hace " . floor($time_diff/3600) . " horas";
                                } else {
                                    echo "Hace " . floor($time_diff/86400) . " días";
                                }
                            } else {
                                echo "Fecha no disponible";
                            }
                            ?>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
                </table>
                <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3><?= $translations['no_activities'] ?? 'No hay actividades registradas' ?></h3>
                    <p><?= $translations['activities_hint'] ?? 'Cuando se registren actividades en el sistema, aparecerán aquí.' ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/historial.js"></script>
    <script src="../../assets/js/goBack.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const body = document.body;
        const themeToggleBtn = document.getElementById("modoOscuroBtn");
        const themeIcon = themeToggleBtn ? themeToggleBtn.querySelector("i") : null;

        const applyTheme = (theme) => {
        if (theme === "dark") {
            body.classList.add("dark");
            if (themeIcon) {
            themeIcon.classList.remove("fa-moon");
            themeIcon.classList.add("fa-sun");
            }
        } else {
            body.classList.remove("dark");
            if (themeIcon) {
            themeIcon.classList.remove("fa-sun");
            themeIcon.classList.add("fa-moon");
            }
        }
        };

        const savedTheme = localStorage.getItem("theme");
        if (savedTheme) {
        applyTheme(savedTheme);
        } else {
        if (window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches) {
            applyTheme("dark");
        } else {
            applyTheme("light");
        }
        }

        if (themeToggleBtn) {
        themeToggleBtn.addEventListener("click", () => {
            const isDark = body.classList.contains("dark");
            const newTheme = isDark ? "light" : "dark";
            applyTheme(newTheme);
            localStorage.setItem("theme", newTheme);
        });
        }

        window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change", (e) => {
        if (!localStorage.getItem("theme")) {
            applyTheme(e.matches ? "dark" : "light");
        }
        });
    });
    </script>

</body>
</html>
