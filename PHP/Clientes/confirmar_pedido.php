<?php
session_start();
include('../db_config.php');
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (empty($_SESSION['carrito'])) {
    die("El carrito está vacío. No se puede confirmar el pedido.");
}

$id_cliente = 1;
$id_restaurante = 1;
$direccion_entrega = $_POST['direccion_entrega'] ?? null;

if (!$direccion_entrega) {
    die("Faltan datos obligatorios para procesar el pedido. Verifica que hayas ingresado la dirección de entrega.");
}



$conn->begin_transaction();
try {
    $sql_pedido = "INSERT INTO pedidos (id_cliente, id_restaurante, estado, total, fecha_pedido, direccion_entrega) 
                   VALUES (?, ?, 'Pendiente', 0, NOW(), ?)";
    $stmt_pedido = $conn->prepare($sql_pedido);
    $stmt_pedido->bind_param("iis", $id_cliente, $id_restaurante, $direccion_entrega);
    $stmt_pedido->execute();
    $id_pedido = $stmt_pedido->insert_id;
    $sql_detalle = "INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, precio_unitario) 
                    VALUES (?, ?, ?, ?)";
    $stmt_detalle = $conn->prepare($sql_detalle);

    $total = 0;
    foreach ($_SESSION['carrito'] as $producto) {
        $id_producto = $producto['id_producto'];
        $cantidad = $producto['cantidad'];
        $precio_unitario = $producto['precio'];

        $total += $cantidad * $precio_unitario;

        $stmt_detalle->bind_param("iiid", $id_pedido, $id_producto, $cantidad, $precio_unitario);
        $stmt_detalle->execute();
    }

    $sql_update_total = "UPDATE pedidos SET total = ? WHERE id_pedido = ?";
    $stmt_update = $conn->prepare($sql_update_total);
    $stmt_update->bind_param("di", $total, $id_pedido);
    $stmt_update->execute();

    $conn->commit();
    unset($_SESSION['carrito']);

    echo "Pedido confirmado con éxito. ID del pedido: $id_pedido. Total: $$total.";
    echo "<br><a href='productos.php?id_restaurante=$id_restaurante'>Volver a productos</a>";
} catch (Exception $e) {
    $conn->rollback();
    echo "Error al confirmar el pedido: " . $e->getMessage();
}
?>
