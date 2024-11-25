<?php
require '../db_config.php';

if ($conn->ping()) {
    // Conexión activa
} else {
    die("Error de conexión: " . $conn->error);
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar si se especificó el ID del restaurante
if (!isset($_GET['id_restaurante']) || empty($_GET['id_restaurante'])) {
    die("Error: No se especificó un restaurante.");
}

$id_restaurante = intval($_GET['id_restaurante']);

// Consulta para obtener la información del restaurante
$query_restaurante = "SELECT id, nombre_restaurante, descripcion, categoria, ciudad FROM restaurantes WHERE id = ?";
$stmt = $conn->prepare($query_restaurante);
$stmt->bind_param("i", $id_restaurante);
$stmt->execute();
$result_restaurante = $stmt->get_result();

if ($result_restaurante->num_rows === 0) {
    die("Error: Restaurante no encontrado.");
}

$restaurante = $result_restaurante->fetch_assoc();

// Consulta para obtener los productos del restaurante
$query_productos = "SELECT id_producto, nombre_producto, descripcion, precio FROM productos WHERE id_restaurante = ?";
$stmt = $conn->prepare($query_productos);
$stmt->bind_param("i", $id_restaurante);
$stmt->execute();
$result_productos = $stmt->get_result();

$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - <?= htmlspecialchars($restaurante['nombre_restaurante']) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
        }
        main {
            padding: 20px;
        }
        .restaurante-info {
            margin-bottom: 20px;
            text-align: center;
        }
        .productos {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }
        .producto {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .producto h3 {
            font-size: 1.2em;
            margin: 10px 0;
        }
        .producto p {
            margin: 5px 0;
        }
        .producto .precio {
            color: #28a745;
            font-weight: bold;
            margin: 10px 0;
        }
        .producto form {
            margin-top: 10px;
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
    <header>
        <h1>Productos en <?= htmlspecialchars($restaurante['nombre_restaurante']) ?></h1>
        <p><?= htmlspecialchars($restaurante['descripcion']) ?></p>
        <p><strong>Ubicación:</strong> <?= htmlspecialchars($restaurante['ciudad']) ?></p>
    </header>
    <main>
        <div class="productos">
            <?php while ($producto = $result_productos->fetch_assoc()): ?>
                <div class="producto">
                    <h3><?= htmlspecialchars($producto['nombre_producto']) ?></h3>
                    <p><?= htmlspecialchars($producto['descripcion']) ?></p>
                    <p class="precio">$<?= number_format($producto['precio'], 2) ?></p>
                    <form method="POST" action="Carrito.php">
                        <input type="hidden" name="id_producto" value="<?= $producto['id_producto'] ?>">
                        <input type="hidden" name="nombre_producto" value="<?= htmlspecialchars($producto['nombre_producto']) ?>">
                        <input type="hidden" name="precio" value="<?= $producto['precio'] ?>">
                        <label for="cantidad-<?= $producto['id_producto'] ?>">Cantidad:</label>
                        <input type="number" name="cantidad" id="cantidad-<?= $producto['id_producto'] ?>" value="1" min="1" max="10">
                        <button type="submit">Agregar al carrito</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    </main>
</body>
</html>
