<?php
include('../db_config.php');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Simular autenticación (reemplazar con el sistema real)
$id_cliente = 1; // Este ID debería venir de la sesión del cliente logueado

// Obtener historial de pedidos
$sql = "SELECT p.id_pedido, dp.cantidad, p.estado, pr.nombre_producto 
        FROM pedidos p
        JOIN detalle_pedido dp ON p.id_pedido = dp.id_pedido
        JOIN productos pr ON dp.id_producto = pr.id_producto
        WHERE p.id_cliente = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_cliente);
$stmt->execute();
$pedidos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Pedidos</title>
    <link rel="stylesheet" href="1_Estilos.css">
</head>
<body>
    <h1>Historial de Pedidos</h1>
    <?php if (!empty($pedidos)): ?>
        <?php foreach ($pedidos as $pedido): ?>
            <div>
                <p><strong>ID Pedido:</strong> <?php echo htmlspecialchars($pedido['id_pedido']); ?></p>
                <p><strong>Producto:</strong> <?php echo htmlspecialchars($pedido['nombre_producto']); ?></p>
                <p><strong>Cantidad:</strong> <?php echo htmlspecialchars($pedido['cantidad']); ?></p>
                <p><strong>Estado:</strong> <?php echo htmlspecialchars($pedido['estado']); ?></p>
            </div>
            <hr>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No hay pedidos en tu historial.</p>
    <?php endif; ?>
</body>
<br><strong>Gracias por tu preferencia | Kisma</strong></br>
</html>
