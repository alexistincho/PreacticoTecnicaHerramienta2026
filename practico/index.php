<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <?php $BASE_URL = "http://localhost/PreacticoTecnicaHerramienta2026/practico/";?>
     <link href="<?= $BASE_URL ?>Assets/bootstrap 5.3.8/css/bootstrap.min.css" rel="stylesheet">
    <script src="<?= $BASE_URL ?>Assets/bootstrap 5.3.8/js/bootstrap.bundle.min.js"></script>


    <title>Noti Noticia</title>
</head>

<body>

<?php include_once("includes/header.php"); ?>

<div class="container mt-5">

    <h1 class="text-center mb-4">Noticias Publicadas</h1>

    <?php

    /*=========================OBTENER NOTICIAS PUBLICADAS=========================*/

    $sql = "SELECT n.*, u.nombre FROM noticias n 
            JOIN usuarios u ON n.id_autor = u.id_usuario 
            WHERE n.estado = 'Publicada' 
            ORDER BY n.fecha_publicacion DESC";

    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $resultado = $stmt->get_result();

    ?>

    <?php if ($resultado->num_rows > 0): ?>

    <div class="row row-cols-1 row-cols-md-3 g-4">

        <?php while ($fila = $resultado->fetch_assoc()): ?>

        <div class="col">
            <div class="card h-100 shadow-sm">

                <?php if ($fila["imagen"] != null): ?>
                    <img src="Assets/imagenes/<?= $fila["imagen"] ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Imagen noticia">
                <?php else: ?>
                    <img src="https://placehold.co/600x200?text=Sin+Imagen" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Sin imagen">
                <?php endif; ?>

                <div class="card-body">
                    <h5 class="card-title"><?= $fila["titulo"] ?></h5>
                    <p class="card-text"><?= mb_substr($fila["descripcion"], 0, 120) ?>...</p>
                </div>

                <div class="card-footer d-flex justify-content-between align-items-center">
                    <small class="text-muted">Por: <?= $fila["nombre"] ?></small>
                    <small class="text-muted"><?= $fila["fecha_publicacion"] ?></small>
                </div>

            </div>
        </div>

        <?php endwhile; ?>

    </div>

    <?php else: ?>

        <div class="alert alert-info text-center">No hay noticias publicadas aún.</div>

    <?php endif; ?>

</div>

<?php include_once("includes/footer.php"); ?>

</body>
</html>