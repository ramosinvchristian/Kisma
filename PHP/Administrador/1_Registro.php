<?php
require '../db_config.php';

if ($conn->ping()) {
    echo "Conexión exitosa a la base de datos.";
} else {
    die("Error de conexión: " . $conn->error);
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_administrador = htmlspecialchars(trim($_POST['nombre-administrador']));
    $usuario_administrador = htmlspecialchars(trim($_POST['usuario-administrador']));
    $correo_administrador = filter_var(trim($_POST['correo-administrador']), FILTER_SANITIZE_EMAIL);
    $contrasena_administrador = trim($_POST['contrasena-administrador']);
    $confirmar_contrasena_administrador = trim($_POST['confirmar-contrasena-administrador']);
    $token_administrador = trim($_POST['token-administrador']);
    $captcha = $_POST['captcha'] ?? '';
    $robot = isset($_POST['robot']);
    $terminos = isset($_POST['terminos']);

    $token_correcto = 'Kisma123';
    if (
        empty($nombre_administrador) || empty($usuario_administrador) ||
        empty($correo_administrador) || empty($contrasena_administrador) ||
        empty($confirmar_contrasena_administrador) || empty($token_administrador)
    ) {
        die("Error: Todos los campos marcados con * son obligatorios.");
    }

    if ($token_administrador !== $token_correcto) {
        die("Error: El token ingresado es incorrecto.");
    }
    if (!filter_var($correo_administrador, FILTER_VALIDATE_EMAIL)) {
        die("Error: El correo no es válido.");
    }
    if ($contrasena_administrador !== $confirmar_contrasena_administrador) {
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
    if ($token_administrador !== $token_correcto) {
        die("Error: Token incorrecto. Contacta con el soporte.");
    }
    $hash_contrasena = password_hash($contrasena_administrador, PASSWORD_DEFAULT);
    $sql = "INSERT INTO administradores (nombre_administrador, usuario_administrador, correo_administrador, contrasena_administrador) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    $stmt->bind_param('ssss', $nombre_administrador, $usuario_administrador, $correo_administrador, $hash_contrasena);

    if ($stmt->execute()) {
        echo "Registro exitoso. Ahora puedes <a href='../../HTML/Administrador/1_Iniciar_Sesion.html'>Iniciar Sesión</a>.";
    } else {
        if ($stmt->errno === 1062) {
            echo "Error: El correo o nombre de usuario ya está registrado del administrador.";
        } else {
            echo "Error al registrar el administrador: " . $stmt->error;
        }
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Método de solicitud no válido.";
}
?>
