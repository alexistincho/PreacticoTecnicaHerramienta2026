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
    <title>Noti Noticia: Registro de usuario</title>
</head>
<body>
    <?php include("../header.php");?>
    <?php
            if (!empty($_SESSION["error"])){
                $error = $_SESSION["error"] ?? " ";
                unset($_SESSION["error"]);
            } 
            else {
                $error = "";
            }
    ?>
        <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh; padding: 1rem;">
            <div class="card p-4 rounded shadow" style="max-width: 500px; width: 100%;">
                <h3 class="text-center mb-4">Registro de Usuario</h3>
                <?php if(!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <form action="../database/validar-registro.php" method="post" enctype="multipart/form-data" novalidate>
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="nombre" required >
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contraseña <span class="text-danger">*</span></label>
                        <input type="password" class="form-control"  name="password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Correo electrónico <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label>Selecciona sus roles<span class="text-danger">*</span></label><br>
                            Editor <input type="checkbox" name="roles[]" value="1"> 

                            Validador <input type="checkbox" name="roles[]" value="2"> 
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Registrarse</button>
                </form>
            </div>
        </div>
    <?php include("../footer.php");?>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    

</body>
</html>