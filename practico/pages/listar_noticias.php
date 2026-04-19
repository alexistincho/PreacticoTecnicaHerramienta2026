<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Noticias</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="../css/styleHistorial.css" rel="stylesheet">
</head>

<body class="bg-light">

<?php
include("../header.php");

/* Verificar sesión */

if (!isset($_SESSION["id_usuario"])) {header("Location: login.php");exit;}

$id_usuario = $_SESSION["id_usuario"];

/* Obtener noticias del usuario */

$sql = "SELECT * FROM noticias WHERE id_autor = ? ORDER BY fecha_creacion DESC";

$stmt = $conexion->prepare($sql);

$stmt->bind_param("i", $id_usuario);

$stmt->execute();

$resultado = $stmt->get_result();
?>

<div class="container mt-5">

<h2 class="mb-4 text-center">
Mis Noticias
</h2>

<?php
if (!empty($_SESSION["mensaje"])) {
    echo '
    <div class="alert alert-success text-center">' . $_SESSION["mensaje"] . '</div>';
    unset($_SESSION["mensaje"]);
}
?>
<table class="table table-bordered table-striped text-center">
<thead class="table-dark">
    <tr> <th>Título</th> <th>Imagen</th> <th>Estado</th> <th>Fecha</th> <th>Acciones</th></tr>
</thead>
<tbody>
<?php
    if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        echo '
        <tr>
        
        <td>' . $fila["titulo"] . '</td>
        <td>';
        if ($fila["imagen"] != null) {

            echo '<img src="../Assets/' . $fila["imagen"] . '" width="80" class=" img-thumbnail">';
        }
        echo '  </td> <td> <span class="badge bg-primary"> ' . $fila["estado"] . ' </span> </td>

        <td> ' . $fila["fecha_creacion"] . ' </td> <td>

        <a href="editar_noticia.php?id=' . $fila["id_noticia"] . '" class="btn btn-warning btn-sm"> Editar </a>

        <a href="../database/eliminar_noticia.php?id=' . $fila["id_noticia"] . '"class="btn btn-danger btn-sm"onclick="return confirm(\'¿Eliminar noticia?\')">
        Eliminar</a>';

        /*BOTÓN ENVIAR A VALIDACIÓNSOLO SI ESTÁ EN BORRADOR*/

        
        echo' <a href="listar_noticias.php?id_historial=' . $fila["id_noticia"] . '" class="btn btn-secondary btn-sm btn-historial"> 
            Historial</a>
        </td></tr>';
    }

    }else {
        echo ' <tr> <td colspan="6"> No tienes noticias creadas </td> </tr>';
    }



?>

    <?php include("historial.php");?>


</tbody></table></div>
<?php include("../footer.php"); ?>
</body>
</html>