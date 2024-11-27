<?php
include('../db_config.php');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Obtener restaurantes
$result = $conn->query("SELECT id, nombre_restaurante FROM restaurantes");
if (!$result) {
    die("Error en la consulta: " . $conn->error);
}

$restaurantes = [];
while ($row = $result->fetch_assoc()) {
    $restaurantes[] = $row;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurantes y Productos</title>
    <link rel="stylesheet" href="1_Estilos.css">
</head>
<body>
    <h1>Restaurantes</h1>
    <?php if (count($restaurantes) > 0): ?>
        <?php foreach ($restaurantes as $restaurante): ?>
            <div>
                <h2><?php echo $restaurante['nombre_restaurante']; ?></h2>
                <a href="productos.php?id_restaurante=<?php echo $restaurante['id']; ?>">Ver Productos</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No hay restaurantes registrados.</p>
    <?php endif; ?>
</body>
</html>
