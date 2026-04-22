<?php

session_start();

include(__DIR__ . "/../config/conexion.php");

/*=========================VERIFICAR SESIÓN=========================*/

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../view/login.php");
    exit;
}

$id_noticia = $_GET["id"];
$id_usuario = $_SESSION["id_usuario"];

/*=========================OBTENER ROL VALIDADOR=========================*/

$es_validador = false;

$sql_rol = "SELECT r.nombre FROM roles r 
            INNER JOIN usuario_roles ur ON r.id_rol = ur.id_rol 
            WHERE ur.id_usuario = ? AND r.nombre = 'Validador'";

$stmt_rol = $conexion->prepare($sql_rol);
$stmt_rol->bind_param("i", $id_usuario);
$stmt_rol->execute();
$stmt_rol->store_result();

if ($stmt_rol->num_rows > 0) {
    $es_validador = true;
}

if (!$es_validador) {
    $_SESSION["error"] = "No tenés permiso para publicar noticias";
    header("Location: ../view/validar_noticia.php");
    exit;
}

/*=========================VERIFICAR QUE ESTÉ EN LISTA PARA VALIDACIÓN=========================*/

$sql_check = "SELECT estado FROM noticias WHERE id_noticia = ?";

$stmt_check = $conexion->prepare($sql_check);
$stmt_check->bind_param("i", $id_noticia);
$stmt_check->execute();
$resultado = $stmt_check->get_result();
$noticia = $resultado->fetch_assoc();

if (!$noticia || $noticia["estado"] != "Lista para Validación") {
    $_SESSION["error"] = "La noticia no está lista para validar";
    header("Location: ../view/validar_noticia.php");
    exit;
}

/*=========================PUBLICAR NOTICIA=========================*/

$sql_update = "UPDATE noticias SET estado = 'Publicada', fecha_publicacion = NOW() WHERE id_noticia = ?";

$stmt_update = $conexion->prepare($sql_update);
$stmt_update->bind_param("i", $id_noticia);
$stmt_update->execute();

/*=========================REGISTRAR HISTORIAL=========================*/

$sql_historial = "INSERT INTO historial (id_noticia, id_usuario, estado_anterior, estado_nuevo) 
                  VALUES (?, ?, 'Lista para Validación', 'Publicada')";

$stmt_historial = $conexion->prepare($sql_historial);
$stmt_historial->bind_param("ii", $id_noticia, $id_usuario);
$stmt_historial->execute();

/*=========================REDIRECCIÓN=========================*/

$_SESSION["mensaje"] = "Noticia publicada correctamente";
header("Location: ../view/validar_noticia.php");
exit;