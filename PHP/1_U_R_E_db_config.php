<?php
$host = 'localhost';
$dbname = 'Kisma';
$username = 'root';
$password = '';
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error al conectar a la base de datos: " . $conn->connect_error);
}
?>
