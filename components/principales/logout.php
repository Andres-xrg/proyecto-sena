<?php
// ¡NO pongas ningún espacio ni línea antes de esta apertura PHP!
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

session_destroy();

// Asegura que no haya salida previa antes de redirigir
header("Location: /proyecto-sena/index.php?page=components/principales/login&logout=1");
exit;
