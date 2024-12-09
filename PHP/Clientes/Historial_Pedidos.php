<?php
include('../db_config.php');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Simular autenticación (reemplazar con el sistema real)
$id_cliente = 1; // Este ID debería venir de la sesión del cliente logueado

// Obtener historial de pedidos
$sql = "SELECT p.id_pedido, dp.cantidad, p.estado, pr.nombre_producto, p.fecha_pedido
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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .contenedor {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #4CAF50;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .mensaje {
            text-align: center;
            font-size: 18px;
            color: #555;
        }
        footer {
            text-align: center;
            margin-top: 20px;
            color: #777;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <h1>Historial de Pedidos</h1>
        <?php if (!empty($pedidos)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Estado</th>
                        <th>Fecha del Pedido</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($pedido['id_pedido']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['nombre_producto']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['cantidad']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['estado']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['fecha_pedido']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="mensaje">No hay pedidos en tu historial.</p>
        <?php endif; ?>
    </div>
    <footer>
        <strong>Gracias por tu preferencia | Kisma</strong>
    </footer>
</body>
</html>
