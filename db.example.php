<?php
$host = 'localhost';
$dbname = 'nombre_base_datos';
$user = 'usuario';
$pass = 'contraseña';

// Usado en db.php
$conexion = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
?>