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

$sql = "SELECT id AS id_empleado, 
               nombre_completo, 
               usuario_empleado AS nombre_usuario, 
               correo_empleado AS correo, 
               telefono_empleado AS telefono, 
               tipo_vehiculo AS puesto, 
               fecha_registro AS fecha_contratacion 
        FROM empleados";
$result = $conn->query($sql);

if (!$result) {
    die("Error al obtener los empleados: " . $conn->error);
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Empleados</title>
    <link rel="stylesheet" href="../../HTML/Administrador/1_Estilos.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .contenedor {
            padding: 20px;
            max-width: 1200px;
            margin: auto;
        }
        .titulo {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <h1 class="titulo">Lista de Empleados</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre Completo</th>
                    <th>Nombre de Usuario</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Vehículo</th>
                    <th>Fecha de Contratación</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id_empleado']) ?></td>
                        <td><?= htmlspecialchars($row['nombre_completo']) ?></td>
                        <td><?= htmlspecialchars($row['nombre_usuario']) ?></td>
                        <td><?= htmlspecialchars($row['correo']) ?></td>
                        <td><?= htmlspecialchars($row['telefono']) ?></td>
                        <td><?= htmlspecialchars($row['puesto']) ?></td>
                        <td><?= htmlspecialchars($row['fecha_contratacion']) ?></td>
                        <td>
                            <form action="eliminar_empleado.php" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este empleado?');">
                                <input type="hidden" name="id_empleado" value="<?= htmlspecialchars($row['id_empleado']) ?>">
                                <button type="submit">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php
$conn->close();
?>
