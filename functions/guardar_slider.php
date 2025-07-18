<?php
session_start();
require_once(__DIR__ . "/../db/conexion.php");

if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Recoger datos del formulario
$id_slider       = $_POST['id_slider'] ?? null;
$titulo_es       = trim($_POST['titulo_es'] ?? '');
$titulo_en       = trim($_POST['titulo_en'] ?? '');
$descripcion_es  = trim($_POST['descripcion_es'] ?? '');
$descripcion_en  = trim($_POST['descripcion_en'] ?? '');
$imagen = null;

$imagenAnterior = null;

if (!empty($id_slider)) {
    // Obtener la imagen actual desde la base de datos
    $stmt_get = $conn->prepare("SELECT imagen FROM slider WHERE id_slider = ?");
    $stmt_get->bind_param("i", $id_slider);
    $stmt_get->execute();
    $stmt_get->bind_result($imagenAnterior);
    $stmt_get->fetch();
    $stmt_get->close();
}

// Procesar nueva imagen
if (!empty($_FILES['imagen']['name']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $nombreOriginal = basename($_FILES['imagen']['name']);
    $nombreSeguro = time() . "_" . preg_replace("/[^a-zA-Z0-9._-]/", "", $nombreOriginal);
    $rutaDestino = __DIR__ . "/../assets/slider/" . $nombreSeguro;

    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
        $imagen = $nombreSeguro;

        // Eliminar imagen anterior si existe
        if (!empty($imagenAnterior)) {
            $rutaAnterior = __DIR__ . "/../assets/slider/" . $imagenAnterior;
            if (file_exists($rutaAnterior)) {
                unlink($rutaAnterior);
            }
        }
    }
}

// Asegurar paso
if (!isset($_SESSION['slide_step'])) {
    $_SESSION['slide_step'] = 1;
}

try {
    if (!empty($id_slider)) {
        // Verificar existencia
        $stmt_check = $conn->prepare("SELECT COUNT(*) FROM slider WHERE id_slider = ?");
        $stmt_check->bind_param("i", $id_slider);
        $stmt_check->execute();
        $stmt_check->bind_result($count);
        $stmt_check->fetch();
        $stmt_check->close();

        if ($count > 0) {
            // Actualizar
            if ($imagen) {
                $stmt = $conn->prepare("UPDATE slider SET titulo_es=?, titulo_en=?, descripcion_es=?, descripcion_en=?, imagen=? WHERE id_slider=?");
                $stmt->bind_param("sssssi", $titulo_es, $titulo_en, $descripcion_es, $descripcion_en, $imagen, $id_slider);
            } else {
                $stmt = $conn->prepare("UPDATE slider SET titulo_es=?, titulo_en=?, descripcion_es=?, descripcion_en=? WHERE id_slider=?");
                $stmt->bind_param("ssssi", $titulo_es, $titulo_en, $descripcion_es, $descripcion_en, $id_slider);
            }
        } else {
            // Insertar si no existe
            $stmt = $conn->prepare("INSERT INTO slider (titulo_es, titulo_en, descripcion_es, descripcion_en, imagen) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $titulo_es, $titulo_en, $descripcion_es, $descripcion_en, $imagen);
        }
    } else {
        // Insertar nuevo
        $stmt = $conn->prepare("INSERT INTO slider (titulo_es, titulo_en, descripcion_es, descripcion_en, imagen) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $titulo_es, $titulo_en, $descripcion_es, $descripcion_en, $imagen);
    }

    $stmt->execute();
    $stmt->close();

    $_SESSION['mensaje_slider'] = "¡Slide guardado correctamente!";
} catch (Exception $e) {
    $_SESSION['mensaje_slider'] = "Error al guardar el slide: " . $e->getMessage();
}

// Redirigir
if (isset($_POST['continuar'])) {
    $_SESSION['slide_step'] = min(3, ($_SESSION['slide_step'] ?? 1) + 1);
    header("Location: /proyecto-sena/index.php?page=components/principales/welcome&edit=1");
} else {
    unset($_SESSION['slide_step']);
    header("Location: /proyecto-sena/index.php?page=components/principales/welcome");
}
exit;
