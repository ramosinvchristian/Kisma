<?php
require '../PHP/1_U_R_E_db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_restaurante = htmlspecialchars(trim($_POST['nombre-restaurante']));
    $categoria = htmlspecialchars(trim($_POST['categoria']));
    $otra_categoria = htmlspecialchars(trim($_POST['otra-categoria']));
    $descripcion = htmlspecialchars(trim($_POST['descripcion']));
    $direccion = htmlspecialchars(trim($_POST['direccion']));
    $ciudad = htmlspecialchars(trim($_POST['ciudad']));
    $codigo_postal = htmlspecialchars(trim($_POST['codigo-postal']));
    $horario = htmlspecialchars(trim($_POST['horario']));
    $telefono_restaurante = htmlspecialchars(trim($_POST['telefono-restaurante']));
    $correo_restaurante = filter_var(trim($_POST['correo-restaurante']), FILTER_SANITIZE_EMAIL);
    $nombre_gerente = htmlspecialchars(trim($_POST['nombre-gerente']));
    $usuario_gerente = htmlspecialchars(trim($_POST['usuario-gerente']));
    $correo_gerente = filter_var(trim($_POST['correo-gerente']), FILTER_SANITIZE_EMAIL);
    $contrasena_gerente = trim($_POST['contrasena-gerente']);
    $confirmar_contrasena_gerente = trim($_POST['confirmar-contrasena-gerente']);
    $captcha = $_POST['captcha'] ?? '';
    $robot = isset($_POST['robot']);
    $terminos = isset($_POST['terminos']);
    if (empty($nombre_restaurante) || empty($categoria) || empty($descripcion) || empty($direccion) || 
        empty($ciudad) || empty($codigo_postal) || empty($horario) || empty($telefono_restaurante) || 
        empty($correo_restaurante) || empty($nombre_gerente) || empty($usuario_gerente) || 
        empty($correo_gerente) || empty($contrasena_gerente) || empty($confirmar_contrasena_gerente)) {
        die("Error: Todos los campos marcados con * son obligatorios.");
    }
    if (!filter_var($correo_restaurante, FILTER_VALIDATE_EMAIL) || !filter_var($correo_gerente, FILTER_VALIDATE_EMAIL)) {
        die("Error: El correo no es válido.");
    }
    if ($contrasena_gerente !== $confirmar_contrasena_gerente) {
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
    $hash_contrasena = password_hash($contrasena_gerente, PASSWORD_DEFAULT);
    $categoria_final = $categoria === 'Otros' ? $otra_categoria : $categoria;
    $sql = "INSERT INTO restaurantes (nombre_restaurante, categoria, descripcion, direccion, ciudad, codigo_postal, horario, 
             telefono_restaurante, correo_restaurante, nombre_gerente, usuario_gerente, correo_gerente, contrasena_gerente) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt->bind_param('sssssssssssss', $nombre_restaurante, $categoria_final, $descripcion, $direccion, $ciudad, 
                                      $codigo_postal, $horario, $telefono_restaurante, $correo_restaurante, 
                                      $nombre_gerente, $usuario_gerente, $correo_gerente, $hash_contrasena);
    if ($stmt->execute()) {
        echo "Registro exitoso. Ahora puedes <a href='../HTML/1_R_Iniciar_Sesion_Restaurante.html'>Iniciar Sesión</a>.";
    } else {
    if ($stmt->errno === 1062) {
        echo "Error: El correo o nombre de usuario ya está registrado.";
    } else {
        echo "Error al registrar el restaurante: " . $stmt->error;
            }
    }
    $stmt->close();
    $conn->close();
} else {
    echo "Método de solicitud no válido.";
}
?>
