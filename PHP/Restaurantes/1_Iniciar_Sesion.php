<?php
require '../db_config.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = htmlspecialchars(trim($_POST['usuario-gerente']));
    $contrasena = trim($_POST['contrasena']);
    if (empty($nombre_usuario) || empty($contrasena)) {
        die("Error: Nombre de usuario y contraseña son requeridos.");
    }
    $sql = "SELECT * FROM restaurantes WHERE usuario_gerente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $nombre_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($contrasena, $user['contrasena_gerente'])) {
            $_SESSION['id_restaurante'] = $user['id'];

            $_SESSION['nombre_usuario'] = $user['usuario_gerente'];
            header("Location: ../../HTML/Restaurante/1_Pagina_Principal.html");
            exit();
        } else {
            echo "Error: Contraseña incorrecta.";
        }
    } else {
        echo "Error: Restaurante no encontrado.";
    }
    $stmt->close();
    $conn->close();
} else {
    echo "Método de solicitud no válido.";
}
?>
