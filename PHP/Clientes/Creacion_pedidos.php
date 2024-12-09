<?php
include('../db_config.php');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Validar datos recibidos
$direccion_entrega = $_POST['direccion_entrega'] ?? 'Dirección no especificada'; // Valor por defecto si no se ingresa
$id_cliente = $_POST['id_cliente'] ?? null;
$id_restaurante = $_POST['id_restaurante'] ?? null;
$productos = $_POST['productos'] ?? []; // Array con id_producto, cantidad, precio_unitario

if ($id_cliente && $id_restaurante && !empty($productos)) {
    $conn->begin_transaction();

    try {
        // Verificar si el cliente existe
        $sql_verificar_cliente = "SELECT COUNT(*) as existe FROM clientes WHERE id = ?";
        $stmt_verificar = $conn->prepare($sql_verificar_cliente);
        $stmt_verificar->bind_param("i", $id_cliente);
        $stmt_verificar->execute();
        $resultado = $stmt_verificar->get_result();
        $cliente_existe = $resultado->fetch_assoc()['existe'];

        if (!$cliente_existe) {
            throw new Exception("El cliente con ID $id_cliente no existe.");
        }

        // Crear el pedido con la dirección de entrega
        $sql_pedido = "INSERT INTO pedidos (id_cliente, id_restaurante, estado, total, fecha_pedido, direccion_entrega) 
                       VALUES (?, ?, 'Pendiente', 0, NOW(), ?)";
        $stmt_pedido = $conn->prepare($sql_pedido);
        $stmt_pedido->bind_param("iis", $id_cliente, $id_restaurante, $direccion_entrega);
        $stmt_pedido->execute();

        $id_pedido = $stmt_pedido->insert_id; // Obtener el ID del pedido recién creado
        $total = 0;

        // Insertar los productos en detalle_pedido
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
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #ece9e6, #ffffff);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 100vh;
        }

        h1 {
            text-align: center;
            font-size: 2rem;
            color: #4CAF50;
            margin-bottom: 20px;
        }

        form {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid #4CAF50;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 600px;
        }

        label {
            font-size: 1rem;
            color: #333;
            margin-top: 10px;
            display: block;
        }

        input[type="number"], input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            width: 100%;
        }

        button:hover {
            background-color: #45a049;
        }

        .producto {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin-top: 10px;
            background-color: #f9f9f9;
        }

        #productos {
            margin-bottom: 20px;
        }

        .mensaje {
            margin-top: 20px;
            padding: 10px;
            border: 2px solid #4CAF50;
            border-radius: 5px;
            background-color: rgba(76, 175, 80, 0.1);
            color: #4CAF50;
            font-size: 1rem;
            text-align: center;
            width: 100%;
            max-width: 600px;
            display: none; /* Oculto por defecto */
        }

        .mensaje.error {
            border-color: #f44336;
            color: #f44336;
            background-color: rgba(244, 67, 54, 0.1);
        }

        @media (max-width: 768px) {
            form {
                padding: 15px;
                font-size: 0.9rem;
            }

            button {
                font-size: 0.9rem;
            }

            input[type="number"], input[type="text"] {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <h1>Crear Pedidos extra</h1>
    <form method="POST" action="">
        <label for="id_cliente">ID Cliente:</label>
        <input type="number" id="id_cliente" name="id_cliente" required>

        <label for="id_restaurante">ID Restaurante:</label>
        <input type="number" id="id_restaurante" name="id_restaurante" required>

        <label for="direccion_entrega">Dirección de Entrega:</label>
        <input type="text" id="direccion_entrega" name="direccion_entrega" required>

        <label>Productos:</label>
        <div id="productos">
            <div class="producto">
                <label for="id_producto">ID Producto:</label>
                <input type="number" name="productos[0][id_producto]" required>
                <label for="cantidad">Cantidad:</label>
                <input type="number" name="productos[0][cantidad]" required>
                <label for="precio_unitario">Precio Unitario:</label>
                <input type="number" step="0.01" name="productos[0][precio_unitario]" required>
            </div>
        </div>
        <button type="button" onclick="agregarProducto()">Agregar otro producto</button>
        <button type="submit">Crear Pedido</button>
    </form>

    <div class="mensaje" id="mensaje"></div>

    <script>
        let contador = 1;

        function agregarProducto() {
            const productosDiv = document.getElementById('productos');
            const nuevoProducto = `
                <div class="producto">
                    <label for="id_producto">ID Producto:</label>
                    <input type="number" name="productos[${contador}][id_producto]" required>
                    <label for="cantidad">Cantidad:</label>
                    <input type="number" name="productos[${contador}][cantidad]" required>
                    <label for="precio_unitario">Precio Unitario:</label>
                    <input type="number" step="0.01" name="productos[${contador}][precio_unitario]" required>
                </div>`;
            productosDiv.insertAdjacentHTML('beforeend', nuevoProducto);
            contador++;
        }

        // Mostrar mensaje de éxito o error (simulación)
        function mostrarMensaje(tipo, texto) {
            const mensajeDiv = document.getElementById('mensaje');
            mensajeDiv.className = tipo === 'error' ? 'mensaje error' : 'mensaje';
            mensajeDiv.innerText = texto;
            mensajeDiv.style.display = 'block';
        }

        // Simulación (puedes reemplazar esto por la lógica real después de procesar el formulario)
        document.querySelector('form').addEventListener('submit', (e) => {
            e.preventDefault();
            mostrarMensaje('success', 'Pedido creado con éxito. ID del pedido: 21');
        });
    </script>
</body>
</html>
