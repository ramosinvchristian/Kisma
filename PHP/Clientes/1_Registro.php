<?php
require '../db_config.php';
if ($conn->ping()) {
    echo "Conexión exitosa a la base de datos.";
} else {
    echo "Error de conexión: " . $conn->error;
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_completo = htmlspecialchars(trim($_POST['nombre-completo']));
    $nombre_usuario = htmlspecialchars(trim($_POST['nombre-usuario']));
    $correo = filter_var(trim($_POST['correo']), FILTER_SANITIZE_EMAIL);
    $contrasena = trim($_POST['contrasena']);
    $confirmar_contrasena = trim($_POST['confirmar-contrasena']);
    $telefono = trim($_POST['telefono']);
    $captcha = $_POST['captcha'] ?? '';
    $robot = isset($_POST['robot']);
    $terminos = isset($_POST['terminos']);
    if (empty($nombre_completo) || empty($nombre_usuario) || empty($correo) || empty($contrasena) || empty($confirmar_contrasena) || empty($telefono)) {
        die("Error: Todos los campos marcados con * son obligatorios.");
    }
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        die("Error: El correo no es válido.");
    }    
    if ($contrasena !== $confirmar_contrasena) {
        die("Error: Las contraseñas no coinciden.");
    }
    if ($captcha !== 'img1') {
        die("Error: Selecciona la imagen correcta del CAPTCHA.");
    }
    if (!$robot) {
        die("Error: Debes confirmar que no eres un robot.");
    }
    if (!$terminos) {
        die("Error: Debes aceptar los términos y condiciones.");
    }
    $hash_contrasena = password_hash($contrasena, PASSWORD_DEFAULT);
    $sql = "INSERT INTO clientes (nombre_completo, nombre_usuario, correo, contrasena, telefono) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt->bind_param('sssss', $nombre_completo, $nombre_usuario, $correo, $hash_contrasena, $telefono);
    if ($stmt->execute()) {
        echo "Registro exitoso. Ahora puedes <a href='../../HTML/Cliente/1_Iniciar_Sesion.html'>Iniciar Sesión</a>.";
    } else {
        if ($stmt->errno === 1062) {
            echo "Error: El correo o nombre de usuario ya está registrado.";
        } else {
            echo "Error al registrar el usuario: " . $stmt->error;
        }
    }
    $stmt->close();
    $conn->close();
} else {
    echo "Método de solicitud no válido.";
}
?>
