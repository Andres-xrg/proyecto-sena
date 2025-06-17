<?php
require_once __DIR__ . '/../../db/conexion.php';

if (!isset($_GET['id'])) {
    die("ID de instructor no especificado.");
}

$id = intval($_GET['id']);
$query = "SELECT * FROM instructores WHERE Id_instructor = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Instructor no encontrado.");
}

$instructor = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Instructor</title>
</head>
<body>
    <h1>Editar Instructor</h1>
    <form action="actualizar_instructor.php" method="POST">
        <input type="hidden" name="id" value="<?= $instructor['Id_instructor'] ?>">
        
        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($instructor['nombre']) ?>" required><br>

        <label>Apellido:</label>
        <input type="text" name="apellido" value="<?= htmlspecialchars($instructor['apellido']) ?>" required><br>

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($instructor['Email']) ?>" required><br>

        <label>Tipo Documento:</label>
        <input type="text" name="tipo_documento" value="<?= htmlspecialchars($instructor['T_documento']) ?>" required><br>

        <label>Número Documento:</label>
        <input type="text" name="numero_documento" value="<?= htmlspecialchars($instructor['N_Documento']) ?>" required><br>

        <label>Teléfono:</label>
        <input type="text" name="telefono" value="<?= htmlspecialchars($instructor['N_Telefono']) ?>" required><br>

        <label>Ficha:</label>
        <input type="text" name="ficha" value="<?= htmlspecialchars($instructor['Ficha']) ?>"><br>

        <button type="submit">Actualizar</button>
    </form>
</body>
</html>
