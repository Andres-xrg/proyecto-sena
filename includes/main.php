<?php
$page = $_GET['page'] ?? 'components/principales/welcome';
$page = str_replace('..', '', $page); // protección mínima

$file = "$page.php";

if (file_exists($file)) {
    include $file;
} else {
    echo "<p style='color:red'>La página solicitada no existe.</p>";
}
?>