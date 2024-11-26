<?php
include('../db_config.php');
ini_set('display_errors', 1);
error_reporting(E_ALL);

$id_restaurante = $_GET['id_restaurante'] ?? null;

if ($id_restaurante) {
    $stmt = $conn->prepare("SELECT id_producto, nombre_producto, precio, descripcion FROM productos WHERE id_restaurante = ?");
    $stmt->bind_param("i", $id_restaurante);
    $stmt->execute();
    $result = $stmt->get_result();
    $productos = $result->fetch_all(MYSQLI_ASSOC);
} else {
    die("ID de restaurante no válido");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="stylesheet" href="1_Estilos.css">
</head>
<body>
    <h1>Productos</h1>
    <?php foreach ($productos as $producto): ?>
        <div>
            <h2><?php echo $producto['nombre_producto']; ?></h2>
            <p><?php echo $producto['descripcion']; ?></p>
            <p>Precio: $<?php echo $producto['precio']; ?></p>
        </div>
    <?php endforeach; ?>
</body>
</html>
