<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link   href="../style.css" rel="stylesheet" >
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="/artesanos/Assets/imagenes/loza-de-barro.png" type="image/x-icon">
    <title>Artesanos.com Inicio de Sesion</title>
</head>
<body>
<?php

    include("../header.php");
    if (session_status() == PHP_SESSION_NONE) {
          session_start();
    }
    
    if (!empty($_SESSION["error"])){
        $error = $_SESSION["error"] ?? " ";
        unset($_SESSION["error"]);
    } 
    else {
        $error = "";
    }

?>
        <div class="container d-flex justify-content-center align-items-center" >
        <div class="card shadow p-4 rounded" style="max-width: 400px; width: 100%;">
            <h3 class="text-center mb-4">Iniciar sesión</h3>
            
            <?php if(!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <form action="../database/validar-login.php" method="post">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" placeholder="Ingrese email" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" class="form-control" name="contraseña" placeholder="Ingrese su contraseña" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Iniciar</button>
            </form>

            <div class="mt-3 text-center">
            <a href="./registro.php">¿No tienes cuenta? Regístrate</a>
            </div>
        </div>
        </div>
    <?php include("../footer.php"); ?>
</body>
</html>