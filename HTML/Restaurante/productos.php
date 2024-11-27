<?php
include('../../PHP/db_config.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$accion = $_POST['accion'] ?? $_GET['accion'] ?? null;

if ($accion === 'crear') {
    $nombre = $_POST['nombre_producto'] ?? null;
    $descripcion = $_POST['descripcion'] ?? null;
    $precio = $_POST['precio'] ?? null;
    $disponible = isset($_POST['disponible']) ? 1 : 0;

    if ($nombre && $descripcion && $precio !== null) {
        $sql = "INSERT INTO productos (id_restaurante, nombre_producto, descripcion, precio, disponible) VALUES (1, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdi", $nombre, $descripcion, $precio, $disponible);
        if ($stmt->execute()) {
            echo "Producto añadido con éxito";
        } else {
            http_response_code(500);
            echo "Error al añadir el producto: " . $conn->error;
        }
    } else {
        http_response_code(400);
        echo "Todos los campos son obligatorios.";
    }
    exit;
    } elseif ($accion === 'listar') {
        try {
            $result = $conn->query("SELECT id_producto, nombre_producto, precio FROM productos WHERE id_restaurante = 1");
            $productos = [];
            while ($row = $result->fetch_assoc()) {
                $productos[] = $row;
            }

            // Verifica si la consulta devolvió resultados
            if (empty($productos)) {
                header('Content-Type: application/json');
                echo json_encode([]);
                exit;
            }

            header('Content-Type: application/json');
            echo json_encode($productos);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => "Error al listar productos", "detalle" => $e->getMessage()]);
        }
        exit;
    }
?>
