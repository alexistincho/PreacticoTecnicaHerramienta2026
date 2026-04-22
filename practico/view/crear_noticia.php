<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="../style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    <title>Crear Noticia</title>

    <style>
        .card-custom {
            max-width: 700px;   /* más ancho */
            margin: 20px auto; /* centrado con margen */
        }
    </style>
</head>
<body class="bg-light">

  <?php include("../includes/header.php"); ?>

  <?php 
    if (!empty($_SESSION["error"])) {
        $error = $_SESSION["error"] ?? " ";
        unset($_SESSION["error"]);
    } else {
        $error = "";
    }
    $id_usuario = $_SESSION["id_usuario"];
  ?> 

  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-12 col-md-10 col-lg-9">
        
        <?php if (!empty($error)): ?>
          <div class="alert alert-danger text-center fw-semibold">
              <?php echo $error; ?>
          </div>
        <?php endif; ?>

        <div class="card card-custom shadow-lg border-0 rounded-4">
          <!-- Header -->
          <div class="card-header bg-primary text-white text-center rounded-top-4">
            <h4 class="mb-0 fw-bold">Crear Noticia</h4>
          </div>

          <!-- Body -->
          <div class="card-body p-4">
            <form action="../controllers/control-noticia.php" method="POST" enctype="multipart/form-data">
              <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">

              <div class="mb-3">
                <label class="form-label fw-semibold">Título </label>
                <input type="text" name="titulo" class="form-control border-primary" minlength="10" maxlength="100" required>
              </div>
              <div class="mb-3">
                    <label class="form-label">imagen</label>
                    <input type="file" name="imagen" class="form-control" accept="image/*">
              </div>
              <div class="mb-3">
                    <label class="form-label">descripción</label>
                    <textarea name="descripcion" class="form-control border-primary" minlength="50" rows="4" required></textarea>
                </div>
              <button type="submit" class="btn btn-success w-100 py-2 fw-bold">
                <i class="bi bi-plus-circle me-2"></i> CREAR
              </button>
            </form>
          </div>

          <!-- Footer opcional -->
          <div class="card-footer text-center text-muted small">
            Recuerda: un titulo puede contener entre <b>10 a 100 caracteres</b>. <br>
            Recuerda: la descripción debe de tener <b>almenos 50 caracteres</b>.
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include("../includes/footer.php"); ?>

</body>
</html>
