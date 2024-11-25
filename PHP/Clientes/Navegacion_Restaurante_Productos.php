<?php
require '../db_config.php';

$sql = "SELECT id, nombre_restaurante, categoria, descripcion FROM restaurantes";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $restaurantes = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $restaurantes = [];
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurantes y Productos - Kisma Delivery</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        header {
            text-align: center;
            padding: 20px;
            background-color: #343a40;
            color: white;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin: 20px;
        }

        .restaurant-card {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 15px;
            margin: 10px;
            width: 300px;
            background-color: #ffffff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .restaurant-card h3 {
            margin: 0 0 10px;
            color: #333;
        }

        .restaurant-card p {
            margin: 0 0 15px;
            color: #666;
        }

        .view-products {
            display: inline-block;
            padding: 10px 15px;
            color: white;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
        }

        .view-products:hover {
            background-color: #0056b3;
        }

        .no-restaurants {
            text-align: center;
            margin: 50px;
            font-size: 1.2em;
            color: #555;
        }
    </style>
</head>
<body>
    <header>
        <h1>Restaurantes y Productos | Cliente</h1>
    </header>

    <div class="container">
        <?php foreach ($restaurantes as $restaurante): ?>
        <div class="restaurant-card">
            <h3><?php echo $restaurante['nombre_restaurante']; ?></h3>
            <p>Categoría: <?php echo $restaurante['categoria']; ?></p>
            <p><?php echo $restaurante['descripcion']; ?></p>
            <a href="Productos_Restaurante.php?id_restaurante=<?= $restaurante['id'] ?>">Ver productos</a>
        </div>
        <?php endforeach; ?>

        <?php if (empty($restaurantes)): ?>
            <p class="no-restaurants">No hay restaurantes registrados por ahora.</p>
        <?php endif; ?>
    </div>
</body>
</html>
