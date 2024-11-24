<?php
require '../PHP/1_U_R_E_db_config.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = htmlspecialchars(trim($_POST['usuario-empleado']));
    $contrasena = trim($_POST['contrasena-empleado']);

    if (empty($nombre_usuario) || empty($contrasena)) {
        die("Error: Nombre de usuario y contraseña son requeridos.");
    }
    $sql = "SELECT * FROM empleados WHERE usuario_empleado = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $nombre_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($contrasena, $user['contrasena_empleado'])) {
            $_SESSION['empleado_id'] = $user['id'];
            $_SESSION['nombre_usuario'] = $user['usuario_empleado'];
            header("Location: ../HTML/1_E_Pagina_Principal_Empleado.html");
            exit();
        } else {
            echo "Error: Contraseña incorrecta.";
        }
    } else {
        echo "Error: Usuario no encontrado.";
    }
    $stmt->close();
    $conn->close();
} else {
    echo "Método de solicitud no válido.";
}
?>
