<style>
  li:hover {
    background: rgba(56, 56, 148, 1);
    transition: background 300ms;
    border-radius: 5px;
  }
</style>

<?php
$BASE_URL = "http://localhost/PreacticoTecnicaHerramienta2026/practico/";

/* Iniciar sesión si no está iniciada */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/* Incluir conexión a la base de datos */
include(__DIR__ . "/../config/conexion.php");

/* Verificar si el usuario está logueado */
if (isset($_SESSION["id_usuario"])) {
    $id_usuario = $_SESSION["id_usuario"];
} else {
    $id_usuario = 0;
}

/* ========================= OBTENER ROLES DEL USUARIO ========================= */

$es_editor = false;
$es_validador = false;

if ($id_usuario != 0) {

    $sql_roles = "
        SELECT r.nombre
        FROM roles r
        INNER JOIN usuario_roles ur
        ON r.id_rol = ur.id_rol
        WHERE ur.id_usuario = ?
    ";

    $stmt = $conexion->prepare($sql_roles);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();

    $resultado = $stmt->get_result();

    while ($fila = $resultado->fetch_assoc()) {

        if ($fila["nombre"] == "Editor") {
            $es_editor = true;
        }

        if ($fila["nombre"] == "Validador") {
            $es_validador = true;
        }

    }

}
?>

<nav class="navbar navbar-expand-lg bg-dark">
  <div class="container-fluid">

    <a class="navbar-brand text-white" href="<?= $BASE_URL ?>index.php">
      Noticias
    </a>

    <button class="navbar-toggler" type="button"
      data-bs-toggle="collapse"
      data-bs-target="#navbarSupportedContent">

      <span class="navbar-toggler-icon"></span>

    </button>

    <div class="collapse navbar-collapse"
      id="navbarSupportedContent">

      <ul class="navbar-nav me-auto">

<?php

if ($id_usuario != 0) {

    // Usuario logueado

    echo '';

    /* ========================= MENÚ PARA EDITOR ========================= */

    if ($es_editor) {

        echo '

        <li class="nav-item">
            <a class="nav-link text-white"
               href="'. $BASE_URL . 'view/crear_noticia.php">
               Crear Noticia
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-white"
               href="'. $BASE_URL . 'view/listar_noticias.php">
               Mis Noticias
            </a>
        </li>

        ';

    }

    /* ========================= MENÚ PARA VALIDADOR ========================= */

    if ($es_validador) {

        echo '

        <li class="nav-item">
            <a class="nav-link text-white"
               href="'. $BASE_URL . 'view/validar_noticia.php">
               Validar Noticias
            </a>
        </li>

        ';

    }

    /* =========================CERRAR SESIÓN========================= */

    echo '

    <li class="nav-item">
        <a class="nav-link text-white"
           href="'. $BASE_URL . 'controllers/logout.php">
           Cerrar Sesión
        </a>
    </li>

    ';

}
else {

    // Usuario no logueado

    echo ' 

<li class="nav-item">
    <a class="nav-link text-white"
       href="' . $BASE_URL . 'view/login.php">
       Iniciar Sesión
    </a>
</li>

<li class="nav-item">
    <a class="nav-link text-white"
       href="' . $BASE_URL . 'view/registro.php">
       Registrarse
    </a>
</li>

';

}

?>

      </ul>

    </div>

  </div>
</nav>