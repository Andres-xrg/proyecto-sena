<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    exit;
}

try {
    $slideNumber = $_POST['slideNumber'] ?? null;
    $titleEs = $_POST['titleEs'] ?? '';
    $titleEn = $_POST['titleEn'] ?? '';
    $descEs = $_POST['descEs'] ?? '';
    $descEn = $_POST['descEn'] ?? '';
    
    if (!$slideNumber || !$titleEs || !$descEs) {
        throw new Exception('Datos incompletos');
    }
    
    // Procesar imagen si existe
    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'assets/img/';
        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageName = 'software_' . $slideNumber . '.' . $extension;
        $imagePath = $uploadDir . $imageName;
        
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            throw new Exception('Error al subir imagen');
        }
    }
    
    // Guardar en archivo JSON
    $dataFile = 'carousel_data.json';
    $data = [];
    
    if (file_exists($dataFile)) {
        $data = json_decode(file_get_contents($dataFile), true) ?: [];
    }
    
    $data[$slideNumber] = [
        'titleEs' => $titleEs,
        'titleEn' => $titleEn,
        'descEs' => $descEs,
        'descEn' => $descEn,
        'image' => $imagePath
    ];
    
    file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT));
    
    echo json_encode([
        'success' => true,
        'message' => 'Slide actualizado correctamente'
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>