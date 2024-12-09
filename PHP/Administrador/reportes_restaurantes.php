<?php
require '../db_config.php';
require '../../vendor/autoload.php';

use Dompdf\Dompdf;

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../HTML/Administrador/1_Iniciar_Sesion.html");
    exit();
}

$sql = "SELECT id, nombre_restaurante, codigo_postal, telefono_restaurante, correo_restaurante, nombre_gerente FROM restaurantes";
$result = $conn->query($sql);

if (!$result) {
    die("Error al obtener los restaurantes: " . $conn->error);
}

$html = '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Restaurantes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            text-align: center;
            color: #4CAF50;
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
            color: #333;
        }
    </style>
</head>
<body>
    <h1>Reporte de Restaurantes</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Código Postal</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Gerente</th>
            </tr>
        </thead>
        <tbody>';

while ($row = $result->fetch_assoc()) {
    $html .= '
            <tr>
                <td>' . htmlspecialchars($row['id']) . '</td>
                <td>' . htmlspecialchars($row['nombre_restaurante']) . '</td>
                <td>' . htmlspecialchars($row['codigo_postal']) . '</td>
                <td>' . htmlspecialchars($row['telefono_restaurante']) . '</td>
                <td>' . htmlspecialchars($row['correo_restaurante']) . '</td>
                <td>' . htmlspecialchars($row['nombre_gerente']) . '</td>
            </tr>';
}

$html .= '
        </tbody>
    </table>
</body>
</html>';

$dompdf = new Dompdf();
$dompdf->load_html($html);
$dompdf->set_paper('A4', 'landscape');
$dompdf->render();

$dompdf->stream("reporte_restaurantes.pdf", ["Attachment" => true]);
exit();
?>
