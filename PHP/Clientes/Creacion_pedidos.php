<?php
require '../db_config.php';

// Verificar si la conexión es exitosa
if ($conn->ping()) {
    // Conexión activa
} else {
    die("Error de conexión: " . $conn->error);
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar sesión y verificar que el cliente esté logueado
session_start();

// Depurar la sesión
var_dump($_SESSION);  // Esto te ayudará a ver si el id_cliente está presente en la sesión

// Verificar que el id_cliente esté disponible en la sesión
if (!isset($_SESSION['id_cliente']) || empty($_SESSION['id_cliente'])) {
    die("Error: No se ha encontrado el ID del cliente.");
}

$id_cliente = $_SESSION['id_cliente']; // Usar el id_cliente que ya está en la sesión

// Verificar que el id_restaurante esté presente en la URL
if (!isset($_GET['id_restaurante']) || empty($_GET['id_restaurante'])) {
    die("Error: No se ha especificado el restaurante.");
}

$id_restaurante = intval($_GET['id_restaurante']);

// Procesar el formulario cuando se envíen productos
if (isset($_POST['productos']) && !empty($_POST['productos'])) {
    $productos = $_POST['productos']; // Array de productos: id_producto, cantidad, precio
    $total = 0;

    // Calcular el total del pedido
    foreach ($productos as $producto) {
        $total += $producto['cantidad'] * $producto['precio'];
    }

    // Insertar el pedido en la tabla `pedidos`
    $query_pedido = "INSERT INTO pedidos (id_cliente, id_restaurante, total) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query_pedido);
    $stmt->bind_param("iid", $id_cliente, $id_restaurante, $total);
    $stmt->execute();
    $id_pedido = $stmt->insert_id;  // Obtener el ID del pedido recién creado

    // Insertar los detalles del pedido en la tabla `detalle_pedido`
    $query_detalle = "INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, precio_unitario) VALUES (?, ?, ?, ?)";
    $stmt_detalle = $conn->prepare($query_detalle);

    foreach ($productos as $producto) {
        $stmt_detalle->bind_param("iiid", $id_pedido, $producto['id_producto'], $producto['cantidad'], $producto['precio']);
        $stmt_detalle->execute();
    }

    $stmt->close();
    $stmt_detalle->close();

    // Redirigir a la página de detalles del pedido o mostrar mensaje de éxito
    header("Location: detalles_pedido.php?id_pedido=$id_pedido");
    exit();
}

// Consultar los productos disponibles para el restaurante
$query_productos = "SELECT id_producto, nombre_producto, precio FROM productos WHERE id_restaurante = ?";
$stmt_productos = $conn->prepare($query_productos);
$stmt_productos->bind_param("i", $id_restaurante);
$stmt_productos->execute();
$result_productos = $stmt_productos->get_result();

// Obtener la información del restaurante para mostrarla
$query_restaurante = "SELECT nombre_restaurante, descripcion FROM restaurantes WHERE id = ?";
$stmt_restaurante = $conn->prepare($query_restaurante);
$stmt_restaurante->bind_param("i", $id_restaurante);
$stmt_restaurante->execute();
$result_restaurante = $stmt_restaurante->get_result();
$restaurante = $result_restaurante->fetch_assoc();

$stmt_productos->close();
$stmt_restaurante->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Pedido en <?= htmlspecialchars($restaurante['nombre_restaurante']) ?></title>
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
        <h1>Crear Pedido en <?= htmlspecialchars($restaurante['nombre_restaurante']) ?></h1>
        <p><?= htmlspecialchars($restaurante['descripcion']) ?></p>
    </header>
    <main>
        <form method="POST" action="../../PHP/Clientes/Creacion_pedidos.php">
        <input type="hidden" name="id_cliente" value="<?= $id_cliente ?>">
        <input type="hidden" name="id_restaurante" value="<?= $id_restaurante ?>">
    
            <div class="productos">
                <?php while ($producto = $result_productos->fetch_assoc()): ?>
                    <div class="producto">
                        <h3><?= htmlspecialchars($producto['nombre_producto']) ?></h3>
                        <p>Precio: $<?= number_format($producto['precio'], 2) ?></p>
                        <label for="cantidad-<?= $producto['id_producto'] ?>">Cantidad:</label>
                        <input type="number" name="productos[<?= $producto['id_producto'] ?>][cantidad]" id="cantidad-<?= $producto['id_producto'] ?>" value="1" min="1">
                        <input type="hidden" name="productos[<?= $producto['id_producto'] ?>][id_producto]" value="<?= $producto['id_producto'] ?>">
                        <input type="hidden" name="productos[<?= $producto['id_producto'] ?>][precio]" value="<?= $producto['precio'] ?>">
                    </div>
                <?php endwhile; ?>
            </div>

            <button type="submit">Confirmar Pedido</button>
        </form>
    </main>
</body>
</html>
