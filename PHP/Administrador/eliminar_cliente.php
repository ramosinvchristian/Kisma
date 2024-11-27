<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../db_config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../HTML/Administrador/1_Iniciar_Sesion.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_cliente'])) {
    $id_cliente = intval($_POST['id_cliente']);
    
    $sql = "DELETE FROM clientes WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('i', $id_cliente);
        if ($stmt->execute()) {
            echo "Cliente eliminado correctamente.";
            header("Location: listar_clientes.php");
            exit();
        } else {
            echo "Error al eliminar el cliente: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
    }
} else {
    echo "Solicitud no válida.";
}

$conn->close();
?>
