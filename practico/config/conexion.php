
<?php
// Datos de conexión
$host = "localhost";
$usuario="root";
$password = "";
$base_de_datos = "noticias2026";

// Crear conexión
$conexion = new mysqli($host, $usuario, $password, $base_de_datos);

// Verificar conexión
/* if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

echo "Conexión exitosa";
?> */