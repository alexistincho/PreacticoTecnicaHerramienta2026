<?php

session_start();

include("../config/conexion.php");

/* Verificar sesión */

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION["id_usuario"];

/* Verificar ID */

if (!isset($_GET["id"])) {

    $_SESSION["error"] = "ID de noticia inválido";

    header("Location: listar_noticias.php");
    exit;
}

$id_noticia = $_GET["id"];

/* Obtener noticia */

$sql = "SELECT * FROM noticias WHERE id_noticia = ? AND id_autor = ?";

$stmt = $conexion->prepare($sql);

$stmt->bind_param("ii", $id_noticia, $id_usuario);

$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {

    $_SESSION["error"] = "Noticia no encontrada";

    header("Location: listar_noticias.php");
    exit;
}

$noticia = $resultado->fetch_assoc();

/* Validar estados no editables */

if (
    $noticia["estado"] === "Publicada" ||
    $noticia["estado"] === "Expirada"
) {

    $_SESSION["error"] =
    "No se puede editar una noticia publicada o expirada";

    header("Location: listar_noticias.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">
<title>Editar Noticia</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<?php include("../includes/header.php"); ?>

<div class="container mt-5">

    <h2 class="text-center mb-4">
    Editar Noticia
    </h2>

<form method="POST" action="../controllers/control-editar-noticia.php" enctype="multipart/form-data">

<input type="hidden" name="id_noticia" value="<?php echo $noticia["id_noticia"]; ?>">

<div class="mb-3">

<label class="form-label">
Título
</label>

<input
type="text"
name="titulo"
class="form-control"
required
value="<?php echo $noticia["titulo"]; ?>"
>

</div>

<div class="mb-3">

<label class="form-label">
Descripción
</label>

<textarea
name="descripcion"
class="form-control"
rows="5"
required
><?php echo $noticia["descripcion"]; ?></textarea>

</div>

<div class="mb-3">

<label class="form-label">
Imagen actual
</label>

<br>

<?php
if ($noticia["imagen"] != null) {

    echo '<img src="../Assets/' .
    $noticia["imagen"] .
    '" width="120" class="img-thumbnail">';
}
?>

</div>

<div class="mb-3">

<label class="form-label">
Cambiar imagen (opcional)
</label>

<input
type="file"
name="imagen"
class="form-control"
>

</div>

<div class="text-center">

<button
type="submit"
class="btn btn-warning"
>
Actualizar Noticia
</button>

<a
href="listar_noticias.php"
class="btn btn-secondary"
>
Cancelar
</a>

</div>

</form>

</div>

<?php include("../includes/footer.php"); ?>

</body>
</html>