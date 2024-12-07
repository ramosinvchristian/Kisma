<?php
session_start();
include('../db_config.php');

if (isset($_POST['id_producto'], $_POST['cantidad'], $_POST['id_restaurante'])) {
    $id_producto = $_POST['id_producto'];
    $cantidad = (int) $_POST['cantidad'];
    $id_restaurante = $_POST['id_restaurante'];

    // Obtener detalles del producto desde la base de datos
    $stmt = $conn->prepare("SELECT nombre_producto, precio FROM productos WHERE id_producto = ?");
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();
    $result = $stmt->get_result();
    $producto = $result->fetch_assoc();

    if ($producto) {
        $carrito = $_SESSION['carrito'] ?? [];
        $carrito[] = [
            'id_producto' => $id_producto,
            'nombre_producto' => $producto['nombre_producto'],
            'precio' => $producto['precio'],
            'cantidad' => $cantidad,
            'id_restaurante' => $id_restaurante
        ];
        $_SESSION['carrito'] = $carrito;
        echo "Producto añadido al carrito.";
    } else {
        echo "Producto no encontrado.";
    }
} else {
    echo "Faltan datos del producto.";
}

?>
