<?php
require '../PHP/1_U_R_E_db_config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_completo = htmlspecialchars(trim($_POST['nombre-empleado']));
    $usuario = htmlspecialchars(trim($_POST['usuario-empleado']));
    $fecha_nacimiento = htmlspecialchars(trim($_POST['fecha-nacimiento']));
    $telefono = htmlspecialchars(trim($_POST['telefono-empleado']));
    $correo = filter_var(trim($_POST['correo-empleado']), FILTER_SANITIZE_EMAIL);
    $contrasena = trim($_POST['contrasena-empleado']);
    $confirmar_contrasena = trim($_POST['confirmar-contrasena-empleado']);
    $tipo_vehiculo = htmlspecialchars(trim($_POST['tipo-vehiculo']));
$anio_vehiculo = intval($_POST['anio-vehiculo']);
$numero_placa = htmlspecialchars(trim($_POST['numero-placa']));
$numero_cuenta = htmlspecialchars(trim($_POST['numero-cuenta']));
    $captcha = $_POST['captcha'] ?? '';
    $robot = isset($_POST['robot']);
    $terminos = isset($_POST['terminos']);
    if (empty($nombre_completo) || empty($usuario) || empty($fecha_nacimiento) || empty($telefono) ||
        empty($correo) || empty($contrasena) || empty($confirmar_contrasena)) {
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
    $sql = "INSERT INTO empleados 
    (nombre_completo, usuario_empleado, fecha_nacimiento, telefono_empleado, correo_empleado, contrasena_empleado, tipo_vehiculo, anio_vehiculo, numero_placa, numero_cuenta) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt->bind_param('ssssssssss', $nombre_completo, $usuario, $fecha_nacimiento, $telefono, $correo, $hash_contrasena, $tipo_vehiculo, $anio_vehiculo, $numero_placa, $numero_cuenta);
    if ($stmt->execute()) {
        echo "Registro exitoso. Ahora puedes <a href='../HTML/1_E_Iniciar_Sesion_Empleado.html'>Iniciar Sesión</a>.";
    } else {
        if ($stmt->errno === 1062) {
            echo "Error: El correo o nombre de usuario ya está registrado.";
        } else {
            echo "Error al registrar al empleado: " . $stmt->error;
        }
    }
    $stmt->close();
    $conn->close();
} else {
    echo "Método de solicitud no válido.";
}
?>
