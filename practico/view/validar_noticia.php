<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Validar Noticias</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<?php

include("../includes/header.php");

/* Verificar sesión */

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

/* Verificar rol validador/editor/admin */

if (!$es_validador) {
    header("Location: ../index.php");
    exit;
}

/* Obtener noticias en borrador */

$sql = "SELECT n.*, u.nombre FROM noticias n JOIN usuarios u ON n.id_autor = u.id_usuario 
WHERE n.estado = 'Borrador'
ORDER BY n.fecha_creacion DESC ";

$stmt = $conexion->prepare($sql);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<div class="container mt-5"> <h2 class="mb-4 text-center"> Noticias para su públicación</h2>

<?php
if (!empty($_SESSION["mensaje"])) {
    echo '
    <div class="alert alert-success text-center">
    ' . $_SESSION["mensaje"] . '
    </div>';
    unset($_SESSION["mensaje"]);
}
?>
<table class="table table-bordered table-striped text-center">
<thead class="table-dark">
<tr>
    <th>Título</th>
    <th>Autor</th>
    <th>Imagen</th>
    <th>Fecha</th>
    <th>Acciones</th>
</tr>
</thead>

<tbody>

<?php

if ($resultado->num_rows > 0) {

    while ($fila = $resultado->fetch_assoc()) {

        echo '
        <tr>
        <td>' . $fila["titulo"] . '</td>
        <td>' . $fila["nombre"] . '</td>
        <td>';

        if ($fila["imagen"] != null) {
            echo '<img src="../imagenes/' . $fila["imagen"] . '" width="80" class="img-thumbnail">';
        }

        echo '
        </td>
        <td>' . $fila["fecha_creacion"] . '</td>
        <td>
        <a href="../database/validar_noticia.php?id=' . $fila["id_noticia"] . '" 
        class="btn btn-success btn-sm">
        Validar
        </a>
        <a  <a href="validar_noticia.php?id_historial=' . $fila["id_noticia"] . '" 
        class="btn btn-secondary btn-sm">
        Historial
        </a>
        </td>
        </tr>';
    }

}
else {

    echo '

    <tr>
        <td colspan="5">
        No hay noticias en borrador
        </td>
    </tr>';

}

?>

</tbody>

</table>

</div>

    
<?php include("historial.php"); ?>

<?php include("../includes/footer.php"); ?>

</body>
</html>