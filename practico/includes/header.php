<style>
  li:hover {
    background: rgba(56, 56, 148, 1);
    transition: background 300ms;
    border-radius: 5px;
  }
</style>

<?php
$BASE_URL = "http://localhost/PreacticoTecnicaHerramienta2026/practico/";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include(__DIR__ . "/../config/conexion.php");

if (isset($_SESSION["id_usuario"])) {
    $id_usuario = $_SESSION["id_usuario"];
} else {
    $id_usuario = 0;
}

$es_editor = false;
$es_validador = false;

if ($id_usuario != 0) {

    $sql_roles = "SELECT r.nombre FROM roles r INNER JOIN usuario_roles ur ON r.id_rol = ur.id_rol WHERE ur.id_usuario = ?";
    $stmt = $conexion->prepare($sql_roles);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    while ($fila = $resultado->fetch_assoc()) {
        if ($fila["nombre"] == "Editor") $es_editor = true;
        if ($fila["nombre"] == "Validador") $es_validador = true;
    }
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">

    <a class="navbar-brand" href="<?= $BASE_URL ?>index.php">Noticias</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">

        <?php if ($id_usuario != 0): ?>

            <?php if ($es_editor): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $BASE_URL ?>view/crear_noticia.php">Crear Noticia</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $BASE_URL ?>view/listar_noticias.php">Mis Noticias</a>
                </li>
            <?php endif; ?>

            <?php if ($es_validador): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $BASE_URL ?>view/validar_noticia.php">Validar Noticias</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $BASE_URL ?>view/parametros.php">Parámetros</a>
                </li>
            <?php endif; ?>

            <li class="nav-item">
                <a class="nav-link" href="<?= $BASE_URL ?>controllers/logout.php">Cerrar Sesión</a>
            </li>

        <?php else: ?>

            <li class="nav-item">
                <a class="nav-link" href="<?= $BASE_URL ?>view/login.php">Iniciar Sesión</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= $BASE_URL ?>view/registro.php">Registrarse</a>
            </li>

        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>

<?php include(__DIR__ . "/../controllers/expirar-noticias.php"); ?>