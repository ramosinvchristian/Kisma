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
    <style>
        .producto {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 8px;
            max-width: 300px;
            text-align: center;
        }
        .producto img {
            width: 30%;
            height: auto;
            border-radius: 5px;
        }
        .producto h2 {
            margin: 10px 0;
        }
        .producto p {
            margin: 5px 0;
        }
        .producto button {
            padding: 10px 15px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .producto button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Productos</h1>
    <?php foreach ($productos as $producto): ?>
        <div class="producto">
            <h2><?php echo htmlspecialchars($producto['nombre_producto']); ?></h2>
            <img src="/Kisma/IMAGES/Producto.png" alt="Imagen de Producto">
            <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>
            <p>Precio: $<?php echo htmlspecialchars($producto['precio']); ?></p>
            <form action="anadir_carrito.php" method="POST">
                <input type="hidden" name="id_producto" value="<?php echo htmlspecialchars($producto['id_producto']); ?>">
                <input type="hidden" name="id_restaurante" value="<?php echo htmlspecialchars($id_restaurante); ?>">
                <button type="submit">Añadir al carrito</button>
            </form>
        </div>
    <?php endforeach; ?>
</body>
</html>
