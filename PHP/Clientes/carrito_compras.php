<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);


if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_producto = $_POST['id_producto'] ?? null;
    $nombre_producto = $_POST['nombre_producto'] ?? '';
    $precio = $_POST['precio'] ?? 0;
    $cantidad = $_POST['cantidad'] ?? 1;

    if ($id_producto && $cantidad > 0) {
        
        $encontrado = false;
        foreach ($_SESSION['carrito'] as &$producto) {
            if ($producto['id_producto'] == $id_producto) {
                $producto['cantidad'] += $cantidad;
                $encontrado = true;
                break;
            }
        }

        if (!$encontrado) {
            $_SESSION['carrito'][] = [
                'id_producto' => $id_producto,
                'nombre_producto' => $nombre_producto,
                'precio' => $precio,
                'cantidad' => $cantidad,
            ];
        }
    }
}


if (isset($_GET['eliminar'])) {
    $id_producto = $_GET['eliminar'];
    $_SESSION['carrito'] = array_filter($_SESSION['carrito'], function ($producto) use ($id_producto) {
        return $producto['id_producto'] != $id_producto;
    });
}


if (isset($_GET['vaciar'])) {
    $_SESSION['carrito'] = [];
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="1_Estilos.css">
    <style>
        .producto {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .acciones {
            display: flex;
            gap: 10px;
        }
        button {
            padding: 5px 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Carrito de Compras</h1>
    <div>
        <?php if (!empty($_SESSION['carrito'])): ?>
            <?php foreach ($_SESSION['carrito'] as $producto): ?>
                <div class="producto">
                    <span><?php echo htmlspecialchars($producto['nombre_producto']); ?> (x<?php echo $producto['cantidad']; ?>)</span>
                    <span>$<?php echo number_format($producto['precio'] * $producto['cantidad'], 2); ?></span>
                    <div class="acciones">
                        <a href="carrito_compras.php?eliminar=<?php echo $producto['id_producto']; ?>">Eliminar</a>
                    </div>
                </div>
            <?php endforeach; ?>
            <div>
                <strong>Total:</strong> $
                <?php echo number_format(array_reduce($_SESSION['carrito'], function ($total, $producto) {
                    return $total + ($producto['precio'] * $producto['cantidad']);
                }, 0), 2); ?>
            </div>
            <form action="confirmar_pedido.php" method="POST">
                <label for="direccion_entrega">Dirección de entrega:</label>
                <input type="text" id="direccion_entrega" name="direccion_entrega" required>
                
                <button type="submit">Finalizar compra</button>
            </form>
            <a href="carrito_compras.php?vaciar=true">Vaciar Carrito</a>
        <?php else: ?>
            <p>El carrito está vacío.</p>
        <?php endif; ?>
    </div>
</body>
</html>
