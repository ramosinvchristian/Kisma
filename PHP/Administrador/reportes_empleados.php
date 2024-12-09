<?php
require '../db_config.php';
require '../../vendor/autoload.php';

use Dompdf\Dompdf;

// Iniciar sesión para verificar si el administrador está autenticado
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../HTML/Administrador/1_Iniciar_Sesion.html");
    exit();
}

// Crear instancia de Dompdf
$dompdf = new Dompdf();

// Consulta para obtener los datos de los empleados
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

// Construcción del HTML para el PDF
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

// Agregar los datos de los empleados al HTML
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

// Cerrar conexión a la base de datos
$conn->close();

// Cargar HTML en Dompdf
$dompdf->loadHtml($html);

// Configurar el tamaño de papel y la orientación
$dompdf->setPaper('A4', 'landscape');

// Renderizar el PDF
$dompdf->render();

// Enviar el PDF al navegador para su descarga
$dompdf->stream("Reporte_Empleados.pdf", ["Attachment" => true]);
?>
