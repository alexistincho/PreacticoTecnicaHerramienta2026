<?php
session_start();
include(__DIR__ . "/../config/conexion.php");

// Validar que se reciba un ID numérico por GET
if (!isset($_GET['id_noticia']) || !is_numeric($_GET['id_noticia'])) {
    $_SESSION['error'] = "Noticia no válida.";
    header("Location: ../index.php");
    exit;
}

$id_noticia = intval($_GET['id_noticia']);

/*=========================OBTENER NOTICIA POR ID=========================*/
$sql = "SELECT n.*, u.nombre 
        FROM noticias n 
        JOIN usuarios u ON n.id_autor = u.id_usuario 
        WHERE n.id_noticia = ? AND n.estado = 'Publicada'";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_noticia);
$stmt->execute();
$resultado = $stmt->get_result();

// Si no existe o no está publicada, redirigir
if ($resultado->num_rows === 0) {
    $_SESSION['error'] = "La noticia no existe o no está disponible.";
    header("Location: ../index.php");
    exit;
}

$noticia = $resultado->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($noticia['titulo']) ?> | Noti Noticia</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <?php $BASE_URL = "http://localhost/PreacticoTecnicaHerramienta2026/practico/"; ?>
    <link href="<?= $BASE_URL ?>Assets/bootstrap 5.3.8/css/bootstrap.min.css" rel="stylesheet">
    <script src="<?= $BASE_URL ?>Assets/bootstrap 5.3.8/js/bootstrap.bundle.min.js"></script>

    <style>
        .noticia-imagen {
            width: 100%;
            max-height: 450px;
            object-fit: cover;
            border-radius: 0.5rem;
        }
        .noticia-descripcion {
            font-size: 1.1rem;
            line-height: 1.8;
            text-align: justify;
        }
        .noticia-meta {
            font-size: 0.9rem;
            color: #6c757d;
        }
    </style>
</head>
<body>

<?php include_once("../includes/header.php"); ?>

<?php
// Mostrar alerta si hay mensaje de error en sesión
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
?>

<div class="container mt-5 mb-5">

    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">

            <!-- Botón volver -->
            <a href="../index.php" class="btn btn-outline-secondary mb-4">
                ← Volver al inicio
            </a>

            <!-- Imagen de la noticia -->
            <?php if (!empty($noticia['imagen'])): ?>
                <img 
                    src="../Assets/imagenes/<?= htmlspecialchars($noticia['imagen']) ?>" 
                    class="noticia-imagen mb-4" 
                    alt="Imagen de la noticia">
            <?php else: ?>
                <img 
                    src="https://placehold.co/900x400?text=Sin+Imagen" 
                    class="noticia-imagen mb-4" 
                    alt="Sin imagen">
            <?php endif; ?>

            <!-- Título -->
            <h1 class="fw-bold mb-3"><?= htmlspecialchars($noticia['titulo']) ?></h1>

            <!-- Metadatos: autor y fecha -->
            <div class="noticia-meta mb-4 d-flex gap-3">
                <span>✍️ Por: <strong><?= htmlspecialchars($noticia['nombre']) ?></strong></span>
                <span>📅 <?= date('d/m/Y H:i', strtotime($noticia['fecha_publicacion'])) ?></span>
            </div>

            <hr>

            <!-- Descripción completa -->
            <div class="noticia-descripcion mt-4">
                <?= nl2br(htmlspecialchars($noticia['descripcion'])) ?>
            </div>

            <hr class="mt-5">

            <!-- Botón volver al final -->
            <div class="text-center mt-4">
                <a href="../index.php" class="btn btn-primary px-5">← Volver a las noticias</a>
            </div>

        </div>
    </div>

</div>

<?php include_once("../includes/footer.php"); ?>

</body>
</html>