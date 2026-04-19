
<?php
session_start();
include(__DIR__ . "/../database/conexion.php");
if (!isset($_GET['id_album']) || !is_numeric($_GET['id_album'])) {
    $_SESSION['error'] = "Álbum no válido.";
    header("Location: ../index.php");
    exit;
}

$id_album = intval($_GET['id_album']);

 /* Mensaje si se repite el like*/
            if (isset($_SESSION['error'])) {
                $mensaje = $_SESSION['error'];
                unset($_SESSION['error']);
                echo "<script>
                Swal.fire({
                    icon: 'warning',
                    title: '$mensaje',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
                </script>";
            }



/* Datos del album */
$Album = "SELECT a.titulo AS album_titulo, a.url_imagen_album, u.nombre, u.apellido FROM Album a INNER JOIN Usuario u ON a.id_usuario = u.id_usuario WHERE a.id_album = ?";
$Album = $conexion->prepare($Album);
$Album->bind_param("i", $id_album);
$Album->execute();
$Album->store_result();
$Album->bind_result($album_titulo, $url_imagen_album, $nombre, $apellido);
$Album->fetch();
$Album->close();

/* Imágenes asociadas al álbum */
$sql_imagenes = "SELECT id_imagen, url_imagen, titulo, etiqueta, visibilidad 
                 FROM Imagen 
                 WHERE id_album = ?";
$stmt_imagenes = $conexion->prepare($sql_imagenes);
$stmt_imagenes->bind_param("i", $id_album);
$stmt_imagenes->execute();
$resultado_imagenes = $stmt_imagenes->get_result();
$imagenes = $resultado_imagenes->fetch_all(MYSQLI_ASSOC);
$stmt_imagenes->close();

/* cantidad de likes totales */
$consulta_likes = "SELECT COUNT(*) AS total_likes FROM Likes WHERE id_imagen = ?";
$likes = $conexion->prepare($consulta_likes);
$likes->bind_param("i", $id_imagen);
$likes->execute();
$resultado_likes = $likes->get_result();
$total_likes = $resultado_likes->fetch_assoc()['total_likes'];
$likes->close();

/* cantidad de denuncias totales */
$sql_denuncias = "SELECT COUNT(*) AS total_denuncias FROM Denuncia WHERE id_imagen = ?";
$stmt_denuncias = $conexion->prepare($sql_denuncias);
$stmt_denuncias->bind_param("i", $id_imagen);
$stmt_denuncias->execute();
$result_denuncias = $stmt_denuncias->get_result();
$total_denuncias = $result_denuncias->fetch_assoc()['total_denuncias'];
$stmt_denuncias->close();


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Álbum: <?=$album_titulo?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body class="bg-light">
<?php include("../header.php"); ?>

<div class="container my-5">
    <div class="w-100">
        <!-- Encabezado del álbum -->
        <div class="card shadow-lg rounded-4 mb-5 border-0">
            <div class="card-img-top position-relative" style="height: 250px; overflow: hidden;">
                <img src="../<?= $url_imagen_album ?>" class="w-100 h-100 object-fit-cover" alt="Portada del álbum">
                <div class="position-absolute bottom-0 start-0 w-100 bg-dark bg-opacity-50 text-white p-3">
                    <h3 class="mb-1"><?= $album_titulo?></h3>
                    <small>Publicado por: <b><?= "$nombre $apellido" ?></b></small>
                </div>
            </div>
        </div>

        <!-- Imágenes -->
        <?php if (!empty($imagenes)): ?>
            <div class="row g-4">
                <?php foreach ($imagenes as $imagen): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow-sm border-0 rounded-4 overflow-hidden h-100">
                            <div class="overflow-hidden" style="max-height: 250px;">
                                <img src="../<?= $imagen['url_imagen']?>" class="card-img-top img-fluid" alt="<?= htmlspecialchars($imagen['titulo']) ?>" style="transition: transform 0.3s;">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title text-primary"><?=$imagen['titulo'] ?></h5>
                                <p>
                                    <span class="badge bg-secondary"><?= $imagen['etiqueta'] ?></span>
                                    <span class="badge bg-info text-dark"><?= $imagen['visibilidad']?></span>
                                </p>

                              <div class="d-flex gap-2 mb-3">
                            
                                    <i class="bi bi-hand-thumbs-up"></i> Me gusta (<?= $total_likes ?>)
                                     

                                  
                                    <i class="bi bi-flag"></i> Denuncias (<?= $total_denuncias ?>)
                                   
                                </div>
                                <!-- Comentarios -->
                                <div>
                                    <h6 class="fw-bold">Comentarios</h6>
                                    <ul class="list-group list-group-flush mb-2 small" style="max-height: 120px; overflow-y: auto;">
                                        <?php
                                        $id_imagen = $imagen['id_imagen'];
                                        $sqlComentarios = "SELECT c.texto, u.nombre, u.apellido FROM Comentario c INNER JOIN Usuario u ON c.id_usuario = u.id_usuario WHERE c.id_imagen = ?";
                                        $stmtComentarios = $conexion->prepare($sqlComentarios);

                                        if ($stmtComentarios) {
                                            $stmtComentarios->bind_param("i", $id_imagen);
                                            $stmtComentarios->execute();
                                            $resultadoComentarios = $stmtComentarios->get_result();

                                            while ($c = $resultadoComentarios->fetch_assoc()): ?>
                                                <li class="list-group-item">
                                                    <b><?= htmlspecialchars($c['nombre'] . ' ' . $c['apellido']) ?>:</b>
                                                    <?= htmlspecialchars($c['texto']) ?>
                                                </li>
                                            <?php endwhile;

                                            $stmtComentarios->close();
                                        } else {
                                            echo "<li class='list-group-item text-danger'>Error al cargar comentarios.</li>";
                                        }
                                        ?>
                                    </ul>

                                  
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-muted fst-italic text-center">Este álbum aún no tiene imágenes.</p>
        <?php endif; ?>

        <!-- Botones de navegación -->
        <div class="d-flex justify-content-center gap-3 mt-5">
            <a href="../pages/crearImagen.php?id_album=<?= $id_album ?>" class="btn btn-outline-primary btn-lg">
                <i class="bi bi-plus-circle"></i> Cargar más imágenes
            </a>
            <a href="../index.php" class="btn btn-outline-secondary btn-lg">
                <i class="bi bi-house"></i> Ir al inicio
            </a>
        </div>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
