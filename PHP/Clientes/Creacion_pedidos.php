<?php
include('../db_config.php');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Validar datos recibidos
$id_cliente = $_POST['id_cliente'] ?? null;
$id_restaurante = $_POST['id_restaurante'] ?? null;
$productos = $_POST['productos'] ?? []; // Array con id_producto, cantidad, precio_unitario

if ($id_cliente && $id_restaurante && !empty($productos)) {
    $conn->begin_transaction();

    try {
        // Crear el pedido
        $sql_pedido = "INSERT INTO pedidos (id_cliente, id_restaurante, estado, total, fecha_pedido) 
                       VALUES (?, ?, 'Pendiente', 0, NOW())";
        $stmt_pedido = $conn->prepare($sql_pedido);
        $stmt_pedido->bind_param("ii", $id_cliente, $id_restaurante);
        $stmt_pedido->execute();

        $id_pedido = $stmt_pedido->insert_id; // Obtener el ID del pedido recién creado
        $total = 0;

        // Insertar los productos en detalle_pedidos
        $sql_detalle = "INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, precio_unitario) 
                        VALUES (?, ?, ?, ?)";
        $stmt_detalle = $conn->prepare($sql_detalle);

        foreach ($productos as $producto) {
            $id_producto = $producto['id_producto'];
            $cantidad = $producto['cantidad'];
            $precio_unitario = $producto['precio_unitario'];

            $total += $cantidad * $precio_unitario;

            $stmt_detalle->bind_param("iiid", $id_pedido, $id_producto, $cantidad, $precio_unitario);
            $stmt_detalle->execute();
        }

        // Actualizar el total del pedido
        $sql_update_total = "UPDATE pedidos SET total = ? WHERE id_pedido = ?";
        $stmt_update = $conn->prepare($sql_update_total);
        $stmt_update->bind_param("di", $total, $id_pedido);
        $stmt_update->execute();

        $conn->commit();
        echo "Pedido creado con éxito. ID del pedido: $id_pedido";
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error al crear el pedido: " . $e->getMessage();
    }
} else {
    echo "Todos los campos son obligatorios.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Pedido</title>
</head>
<body>
    <h1>Crear Pedido</h1>
    <form method="POST" action="">
        <label for="id_cliente">ID Cliente:</label>
        <input type="number" id="id_cliente" name="id_cliente" required><br><br>

        <label for="id_restaurante">ID Restaurante:</label>
        <input type="number" id="id_restaurante" name="id_restaurante" required><br><br>

        <label>Productos:</label><br>
        <div id="productos">
            <div>
                <label for="id_producto">ID Producto:</label>
                <input type="number" name="productos[0][id_producto]" required>
                <label for="cantidad">Cantidad:</label>
                <input type="number" name="productos[0][cantidad]" required>
                <label for="precio_unitario">Precio Unitario:</label>
                <input type="number" step="0.01" name="productos[0][precio_unitario]" required>
            </div>
        </div>
        <button type="button" onclick="agregarProducto()">Agregar otro producto</button><br><br>

        <button type="submit">Crear Pedido</button>
    </form>

    <script>
        let contador = 1;
        function agregarProducto() {
            const productosDiv = document.getElementById('productos');
            const nuevoProducto = `
                <div>
                    <label for="id_producto">ID Producto:</label>
                    <input type="number" name="productos[${contador}][id_producto]" required>
                    <label for="cantidad">Cantidad:</label>
                    <input type="number" name="productos[${contador}][cantidad]" required>
                    <label for="precio_unitario">Precio Unitario:</label>
                    <input type="number" step="0.01" name="productos[${contador}][precio_unitario]" required>
                </div>`;
            productosDiv.innerHTML += nuevoProducto;
            contador++;
        }
    </script>
</body>
</html>
