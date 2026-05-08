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

    <style>
        /* Hace que toda la card sea clickeable y tenga efecto hover */
        .card-noticia {
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            text-decoration: none;
            color: inherit;
        }
        .card-noticia:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15) !important;
        }
    </style>
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
            <!-- Toda la card es un enlace a ver_noticias.php pasando el ID -->
            <a href="view/ver_noticia.php?id_noticia=<?= $fila['id_noticia'] ?>" class="card-noticia">
                <div class="card h-100 shadow-sm">

                    <?php if ($fila["imagen"] != null): ?>
                        <img src="Assets/imagenes/<?= htmlspecialchars($fila["imagen"]) ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Imagen noticia">
                    <?php else: ?>
                        <img src="https://placehold.co/600x200?text=Sin+Imagen" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Sin imagen">
                    <?php endif; ?>

                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($fila["titulo"]) ?></h5>
                        <p class="card-text"><?= htmlspecialchars(mb_substr($fila["descripcion"], 0, 120)) ?>...</p>
                    </div>

                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <small class="text-muted">Por: <?= htmlspecialchars($fila["nombre"]) ?></small>
                        <small class="text-muted"><?= date('d/m/Y', strtotime($fila["fecha_publicacion"])) ?></small>
                    </div>

                </div>
            </a>
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