<?php
// config/conexion.php
// Archivo de conexión a la base de datos usando MySQLi

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'concesionario_db');

// Crear la conexión
$conexion = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verificar si hubo error al conectar
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>