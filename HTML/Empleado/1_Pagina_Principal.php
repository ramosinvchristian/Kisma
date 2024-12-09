<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['id_empleado'])) {
    die("Error: No se ha iniciado sesión correctamente.");
}

include('../../PHP/db_config.php');

$id_empleado = $_SESSION['id_empleado'];

$sql = "SELECT p.id_pedido, p.estado, p.fecha_pedido, c.nombre_completo AS nombre_cliente, p.direccion_entrega
        FROM pedidos p
        JOIN clientes c ON p.id_cliente = c.id
        WHERE p.id_empleado IS NULL OR p.id_empleado = ?";




$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_empleado);
$stmt->execute();
$pedidos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal - Empleado</title>
    <link rel="stylesheet" href="1_Estilos.css">
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
                            <td><?php echo htmlspecialchars($pedido['direccion_entrega']); ?></td>
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
        fetch(`../../PHP/Empleados/actualizar_estado.php?id_pedido=${id_pedido}&estado=${estado}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error en el servidor: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    if (data.estado === "Entregado") {
    document.querySelector(`tr[data-id="${id_pedido}"]`).remove();
}

                    alert(`Estado del pedido ${id_pedido} actualizado a "${data.estado}".`);
                }
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
                        location.reload();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Ocurrió un error al rechazar el pedido.');
                    });
            }
        }

            document.addEventListener('DOMContentLoaded', () => {
        // Añade el evento a los botones de acciones al cargar la página
        const botonesAccion = document.querySelectorAll('button');

        botonesAccion.forEach(boton => {
            boton.addEventListener('click', (event) => {
                const accion = boton.textContent.trim(); // Obtener texto del botón
                const idPedido = boton.parentElement.parentElement.querySelector('td').textContent.trim(); // ID del pedido

                if (accion.includes('Marcar como En Camino')) {
                    manejarAccionPedido(idPedido, 'En Camino', boton);
                } else if (accion.includes('Marcar como Entregado')) {
                    manejarAccionPedido(idPedido, 'Entregado', boton);
                } else if (accion.includes('Rechazar Pedido')) {
                    rechazarPedido(idPedido, boton);
                }
            });
        });
    });

    /**
     * Manejar el cambio de estado del pedido
     * @param {string} idPedido - ID del pedido
     * @param {string} nuevoEstado - Nuevo estado del pedido
     * @param {HTMLElement} boton - Botón presionado
     */
    function manejarAccionPedido(idPedido, nuevoEstado, boton) {
        if (confirm(`¿Deseas cambiar el estado del pedido ${idPedido} a "${nuevoEstado}"?`)) {
            // Simulación de acción (luego se conectará al backend)
            console.log(`Cambiando estado del pedido ${idPedido} a "${nuevoEstado}"`);
            boton.classList.add('activo');
            alert(`Estado del pedido ${idPedido} actualizado a "${nuevoEstado}".`);
        }
    }

    /**
     * Manejar el rechazo de un pedido
     * @param {string} idPedido - ID del pedido
     * @param {HTMLElement} boton - Botón presionado
     */
    function rechazarPedido(idPedido, boton) {
        if (confirm(`¿Deseas rechazar el pedido ${idPedido}?`)) {
            // Simulación de acción (luego se conectará al backend)
            console.log(`Rechazando pedido ${idPedido}`);
            boton.classList.add('activo');
            alert(`Pedido ${idPedido} rechazado.`);
        }
    }
    </script>
</body>
</html>
