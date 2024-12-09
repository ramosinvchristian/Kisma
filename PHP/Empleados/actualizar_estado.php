<?php


header('Content-Type: application/json; charset=utf-8');
ob_start(); // Captura cualquier salida inesperada

session_start();

include('../db_config.php'); // Ajusta la ruta a tu archivo de configuración de la base de datos

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



if (!isset($_SESSION['id_empleado'])) {
    echo json_encode(["error" => "Error: No se ha iniciado sesión correctamente."]);
    exit;
}

// Verificar los parámetros
if (isset($_GET['id_pedido']) && isset($_GET['estado'])) {
    $id_pedido = intval($_GET['id_pedido']);
    $estado = $_GET['estado'];
$estados_permitidos = ['Pendiente', 'Preparando', 'En Camino', 'Entregado'];

if (!in_array($estado, $estados_permitidos)) {
    echo json_encode(["error" => "Estado no permitido."]);
    exit;
}


    try {
        // Verificar si el pedido existe
        $sql_check = "SELECT id_pedido FROM pedidos WHERE id_pedido = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("i", $id_pedido);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            // El pedido existe, procedemos a actualizarlo
            $sql_update = "UPDATE pedidos SET estado = ? WHERE id_pedido = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("si", $estado, $id_pedido);

            if ($stmt_update->execute()) {
                echo json_encode(["id_pedido" => $id_pedido, "estado" => $estado]);
            } else {
                echo json_encode(["error" => "Error al actualizar el estado."]);
            }
            $stmt_update->close();


            if ($stmt_update->execute()) {
                if ($estado === 'Entregado') {
                    // Actualiza el estado a "Archivado"
                    $sql_archive = "UPDATE pedidos SET estado = 'Archivado' WHERE id_pedido = ?";
                    $stmt_archive = $conn->prepare($sql_archive);
                    $stmt_archive->bind_param("i", $id_pedido);
                
                    if ($stmt_archive->execute()) {
                        echo json_encode(["id_pedido" => $id_pedido, "estado" => 'Archivado']);
                    } else {
                        echo json_encode(["error" => "Error al archivar el pedido."]);
                    }
                    $stmt_archive->close();



                } else {
                    echo json_encode(["id_pedido" => $id_pedido, "estado" => $estado]);
                }
            } else {
                echo json_encode(["error" => "Error al actualizar el estado del pedido."]);
            }

            $stmt_update->close();
        } else {
            echo json_encode(["error" => "El pedido no existe."]);
        }

        $stmt_check->close();
    } catch (Exception $e) {
        echo json_encode(["error" => "Excepción: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Datos inválidos."]);
}

$conn->close();

error_log("Estado recibido: " . $estado);

$output = ob_get_clean(); // Obtén el contenido capturado
if (!empty($output)) {
    error_log("Salida inesperada: " . $output);
}
