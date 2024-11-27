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

$sql = "SELECT id, nombre_restaurante, categoria, descripcion, direccion, ciudad, codigo_postal, horario, telefono_restaurante, correo_restaurante, nombre_gerente FROM restaurantes";
$result = $conn->query($sql);

if (!$result) {
    die("Error al obtener los restaurantes: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Restaurantes</title>
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
        <h1 class="titulo">Lista de Restaurantes</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Descripción</th>
                    <th>Dirección</th>
                    <th>Ciudad</th>
                    <th>Código Postal</th>
                    <th>Horario</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Gerente</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['nombre_restaurante']) ?></td>
                        <td><?= htmlspecialchars($row['categoria']) ?></td>
                        <td><?= htmlspecialchars($row['descripcion']) ?></td>
                        <td><?= htmlspecialchars($row['direccion']) ?></td>
                        <td><?= htmlspecialchars($row['ciudad']) ?></td>
                        <td><?= htmlspecialchars($row['codigo_postal']) ?></td>
                        <td><?= htmlspecialchars($row['horario']) ?></td>
                        <td><?= htmlspecialchars($row['telefono_restaurante']) ?></td>
                        <td><?= htmlspecialchars($row['correo_restaurante']) ?></td>
                        <td><?= htmlspecialchars($row['nombre_gerente']) ?></td>
                        <td>
                            <form action="eliminar_restaurante.php" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este restaurante?');">
                                <input type="hidden" name="id_restaurante" value="<?= htmlspecialchars($row['id']) ?>">
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
