<?php
session_start();
include('../db_config.php');  
// Verificar si la sesión está iniciada correctamente
if (!isset($_SESSION['id_empleado'])) {
    die("Error: No se ha iniciado sesión correctamente.");
}

include('../db_config.php');

// Obtener el id del empleado desde la sesión
$id_empleado = $_SESSION['id_empleado'];

// Obtener los pedidos asignados a este empleado
$sql = "SELECT p.id_pedido, p.estado, p.fecha_pedido, c.nombre_cliente, c.direccion
        FROM pedidos p
        JOIN clientes c ON p.id_cliente = c.id_cliente
        WHERE p.id_empleado IS NULL OR p.id_empleado = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_empleado);
$stmt->execute();
$pedidos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!-- HTML del archivo -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal - Empleado</title>
    <link rel="stylesheet" href="1_Estilos.css">
    <link rel="stylesheet" href="2_Estilos.css">
</head>
<body>
    <header>
        <div class="logo">
            <h1>Kisma Delivery</h1>
        </div>
        <nav>
            <ul>
                <li><a href="#perfil">Perfil</a></li>
                <li><a href="#pedidos">Pedidos Asignados</a></li>
                <li><a href="#configuracion">Configuración</a></li>
                <li><a href="../PHP/cerrar_sesion.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section id="perfil" class="seccion">
            <h2>Perfil del Empleado</h2>
            <p><strong>Nombre:</strong> [Nombre del empleado]</p>
            <p><strong>Teléfono:</strong> [Teléfono del empleado]</p>
            <p><strong>Tipo de Vehículo:</strong> [Tipo de vehículo]</p>
            <p><strong>Año del Vehículo:</strong> [Año del vehículo]</p>
            <p><strong>Número de Placa:</strong> [Número de placa]</p>
        </section>

        <section id="pedidos" class="seccion">
            <h2>Pedidos Asignados</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Cliente</th>
                        <th>Dirección de Entrega</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($pedido['id_pedido']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['nombre_cliente']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['direccion']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['estado']); ?></td>
                            <td>
                                <?php if ($pedido['estado'] == 'Pendiente'): ?>
                                    <button onclick="actualizarEstado(<?php echo $pedido['id_pedido']; ?>, 'En Camino')">Marcar como En Camino</button>
                                    <button onclick="actualizarEstado(<?php echo $pedido['id_pedido']; ?>, 'Entregado')">Marcar como Entregado</button>
                                <?php endif; ?>
                                <button onclick="rechazarPedido(<?php echo $pedido['id_pedido']; ?>)">Rechazar Pedido</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Kisma Delivery. Todos los derechos reservados.</p>
    </footer>

    <script>
        function actualizarEstado(id_pedido, estado) {
            if (confirm(`¿Deseas cambiar el estado del pedido ${id_pedido} a "${estado}"?`)) {
                fetch(`../PHP/Empleado/actualizar_estado.php?id_pedido=${id_pedido}&estado=${estado}`)
                    .then(response => response.text())
                    .then(data => {
                        alert(data);
                        location.reload(); // Recargar la página para actualizar el estado
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Ocurrió un error al actualizar el estado.');
                    });
            }
        }

        function rechazarPedido(id_pedido) {
            if (confirm(`¿Deseas rechazar el pedido ${id_pedido}?`)) {
                fetch(`../PHP/Empleado/rechazar_pedido.php?id_pedido=${id_pedido}`)
                    .then(response => response.text())
                    .then(data => {
                        alert(data);
                        location.reload(); // Recargar la página para actualizar los pedidos
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Ocurrió un error al rechazar el pedido.');
                    });
            }
        }
    </script>
</body>
</html>
