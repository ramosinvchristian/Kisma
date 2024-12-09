<?php
include('../db_config.php');
ini_set('display_errors', 1);
error_reporting(E_ALL);


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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }

        .restaurantes-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .restaurante {
            background-color: #ffffff;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 8px;
            max-width: 300px;
            text-align: center;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .restaurante img {
            width: 100%;
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            transition: transform 0.2s;
        }

        .restaurante img:hover {
            transform: scale(1.1);
        }

        .restaurante h2 {
            margin: 10px 0;
            font-size: 1.5em;
        }

        .restaurante a {
            display: inline-block;
            padding: 10px 15px;
            background-color: #007bff;
            color: #ffffff;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.2s;
        }

        .restaurante a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Restaurantes</h1>
    <div class="restaurantes-container">
        <?php if (count($restaurantes) > 0): ?>
            <?php foreach ($restaurantes as $restaurante): ?>
                <div class="restaurante">
                    <h2><?php echo htmlspecialchars($restaurante['nombre_restaurante']); ?></h2>
                    <img src="/Kisma/IMAGES/Restaurante.png" alt="Imagen de Restaurante">
                    <a href="productos.php?id_restaurante=<?php echo $restaurante['id']; ?>">Visitar</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay restaurantes registrados.</p>
        <?php endif; ?>
    </div>
</body>
</html>
