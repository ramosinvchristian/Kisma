<?php
require '../db_config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = htmlspecialchars(trim($_POST['nombre-usuario']));
    $contrasena = trim($_POST['contrasena']);

    if (empty($nombre_usuario) || empty($contrasena)) {
        die("Error: Nombre de usuario y contraseña son requeridos.");
    }

    $sql = "SELECT * FROM administradores WHERE usuario_administrador = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt->bind_param('s', $nombre_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        if (password_verify($contrasena, $admin['contrasena_administrador'])) {
            $_SESSION['admin_id'] = $admin['id_administrador'];
            $_SESSION['usuario_administrador'] = $admin['usuario_administrador'];
            echo "Redirigiendo a la página principal de administrador...";
            header("Location: ../../HTML/Administrador/1_Pagina_Principal.html");
            exit();
        } else {
            echo "Error: Contraseña incorrecta.";
        }
    } else {
        echo "Error: Administrador no encontrado.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Método de solicitud no válido.";
}
?>
