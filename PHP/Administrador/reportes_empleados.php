<?php
require '../db_config.php';
require '../../vendor/autoload.php';

use Dompdf\Dompdf;
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../HTML/Administrador/1_Iniciar_Sesion.html");
    exit();
}

$dompdf = new Dompdf();
$sql = "SELECT id AS id_empleado, 
               nombre_completo, 
               usuario_empleado AS nombre_usuario, 
               correo_empleado AS correo, 
               telefono_empleado AS telefono, 
               fecha_registro AS fecha_contratacion 
        FROM empleados";

$result = $conn->query($sql);

if (!$result) {
    die("Error al obtener los empleados: " . $conn->error);
}

$html = '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Empleados</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        h1 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Reporte de Empleados</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre Completo</th>
                <th>Nombre de Usuario</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Fecha de Contratación</th>
            </tr>
        </thead>
        <tbody>';

while ($row = $result->fetch_assoc()) {
    $html .= '<tr>
                <td>' . htmlspecialchars($row['id_empleado']) . '</td>
                <td>' . htmlspecialchars($row['nombre_completo']) . '</td>
                <td>' . htmlspecialchars($row['nombre_usuario']) . '</td>
                <td>' . htmlspecialchars($row['correo']) . '</td>
                <td>' . htmlspecialchars($row['telefono']) . '</td>
                <td>' . htmlspecialchars($row['fecha_contratacion']) . '</td>
              </tr>';
}

$html .= '    </tbody>
    </table>
</body>
</html>';

$conn->close();

$dompdf->loadHtml($html);

$dompdf->setPaper('A4', 'landscape');

$dompdf->render();

$dompdf->stream("Reporte_Empleados.pdf", ["Attachment" => true]);
?>
