<?php
session_start();
include('../db_config.php');
ini_set('display_errors', 1);
error_reporting(E_ALL);


$id_cliente = $_SESSION['usuario_id'];
$carrito = $_SESSION['carrito'] ?? [];


if (empty($carrito)) {
    die("El carrito está vacío.");
}


$id_restaurante = $carrito[0]['id_restaurante'] ?? null;
if (!$id_restaurante) {
    die("Error: No se pudo determinar el restaurante para este pedido.");
}
foreach ($carrito as $item) {
    if ($item['id_restaurante'] !== $id_restaurante) {
        die("Todos los productos deben ser del mismo restaurante.");
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['finalizar_compra'])) {
    $direccion_entrega = $_POST['direccion_entrega'];
    $total = array_sum(array_map(fn($item) => $item['precio'] * $item['cantidad'], $carrito));

    
    $stmt_pedido = $conn->prepare("
        INSERT INTO pedidos (id_cliente, id_restaurante, fecha_pedido, estado, direccion_entrega, total) 
        VALUES (?, ?, NOW(), 'Pendiente', ?, ?)
    ");
    $stmt_pedido->bind_param("iisd", $id_cliente, $id_restaurante, $direccion_entrega, $total);
    $stmt_pedido->execute();
    $id_pedido = $stmt_pedido->insert_id;


    $stmt_detalle = $conn->prepare("
        INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, precio_unitario, subtotal) 
        VALUES (?, ?, ?, ?, ?)
    ");
    foreach ($carrito as $item) {
        $subtotal = $item['precio'] * $item['cantidad'];
        $stmt_detalle->bind_param("iiidd", $id_pedido, $item['id_producto'], $item['cantidad'], $item['precio'], $subtotal);
        $stmt_detalle->execute();
    }


    $_SESSION['carrito'] = [];
    echo "<p>Compra realizada con éxito.</p><a href='productos.php'>Volver a productos</a>";
    exit();
}

echo '<pre>';
print_r($_SESSION);
echo '</pre>';


?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="1_Estilos.css">
</head>
<body>
    <h1>Carrito de Compras</h1>
    <?php if (count($carrito) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($carrito as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['nombre_producto']); ?></td>
                        <td>$<?php echo number_format($item['precio'], 2); ?></td>
                        <td><?php echo htmlspecialchars($item['cantidad']); ?></td>
                        <td>$<?php echo number_format($item['precio'] * $item['cantidad'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <form action="carrito.php" method="POST">
            <label for="direccion_entrega">Dirección de entrega:</label>
            <input type="text" id="direccion_entrega" name="direccion_entrega" required>
            <button type="submit" name="finalizar_compra">Finalizar Compra</button>
        </form>
    <?php else: ?>
        <p>El carrito está vacío.</p>
        <a href="productos.php">Volver a productos</a>
    <?php endif; ?>
</body>
</html>
