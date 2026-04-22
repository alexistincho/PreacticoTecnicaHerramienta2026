<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Parámetros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php
include("../includes/header.php");

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

/*=========================OBTENER PARÁMETROS ACTUALES=========================*/

$sql = "SELECT * FROM parametros LIMIT 1";
$resultado = $conexion->query($sql);
$parametro = $resultado->fetch_assoc();
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6">

            <?php if (!empty($_SESSION["mensaje"])): ?>
                <div class="alert alert-success text-center"><?= $_SESSION["mensaje"] ?></div>
                <?php unset($_SESSION["mensaje"]); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION["error"])): ?>
                <div class="alert alert-danger text-center"><?= $_SESSION["error"] ?></div>
                <?php unset($_SESSION["error"]); ?>
            <?php endif; ?>

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-dark text-white text-center rounded-top-4">
                    <h4 class="mb-0 fw-bold">Parámetros del Sistema</h4>
                </div>
                <div class="card-body p-4">
                    <form action="../controllers/guardar-parametros.php" method="POST">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Días hasta expiración de noticias</label>
                            <input type="number" name="dias_expiracion" class="form-control"
                                   value="<?= $parametro["dias_expiracion"] ?>" min="1" required>
                            <small class="text-muted">Una noticia publicada expirará luego de esta cantidad de días</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tamaño máximo de imagen (MB)</label>
                            <input type="number" name="tamanio_imagen" class="form-control"
                                   value="<?= $parametro["tamanio_imagen"] ?>" min="1" max="10" required>
                            <small class="text-muted">Tamaño máximo permitido para subir imágenes</small>
                        </div>

                        <button type="submit" class="btn btn-success w-100 fw-bold">Guardar</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
</body>
</html>