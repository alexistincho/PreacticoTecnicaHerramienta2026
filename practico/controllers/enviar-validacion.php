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

/*=========================VERIFICAR QUE LA NOTICIA SEA DEL USUARIO Y ESTÉ EN BORRADOR=========================*/

$sql_check = "SELECT estado FROM noticias WHERE id_noticia = ? AND id_autor = ?";

$stmt_check = $conexion->prepare($sql_check);
$stmt_check->bind_param("ii", $id_noticia, $id_usuario);
$stmt_check->execute();
$resultado = $stmt_check->get_result();
$noticia = $resultado->fetch_assoc();

if (!$noticia || $noticia["estado"] != "Borrador") {
    $_SESSION["error"] = "La noticia no puede enviarse a validación";
    header("Location: ../view/listar_noticias.php");
    exit;
}

/*=========================CAMBIAR ESTADO A LISTA PARA VALIDACIÓN=========================*/

$sql_update = "UPDATE noticias SET estado = 'Lista para Validación' WHERE id_noticia = ?";

$stmt_update = $conexion->prepare($sql_update);
$stmt_update->bind_param("i", $id_noticia);
$stmt_update->execute();

/*=========================REGISTRAR HISTORIAL=========================*/

$sql_historial = "INSERT INTO historial (id_noticia, id_usuario, estado_anterior, estado_nuevo) 
                  VALUES (?, ?, 'Borrador', 'Lista para Validación')";

$stmt_historial = $conexion->prepare($sql_historial);
$stmt_historial->bind_param("ii", $id_noticia, $id_usuario);
$stmt_historial->execute();

/*=========================REDIRECCIÓN=========================*/

$_SESSION["mensaje"] = "Noticia enviada a validación correctamente";
header("Location: ../view/listar_noticias.php");
exit;