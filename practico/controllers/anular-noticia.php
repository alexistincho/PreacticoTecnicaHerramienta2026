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

/*=========================VERIFICAR QUE LA NOTICIA SEA DEL USUARIO=========================*/

$sql_check = "SELECT estado FROM noticias WHERE id_noticia = ? AND id_autor = ?";

$stmt_check = $conexion->prepare($sql_check);
$stmt_check->bind_param("ii", $id_noticia, $id_usuario);
$stmt_check->execute();
$resultado = $stmt_check->get_result();
$noticia = $resultado->fetch_assoc();

if (!$noticia || !in_array($noticia["estado"], ["Borrador", "Para Corrección"])) {
    $_SESSION["error"] = "No podés anular esta noticia";
    header("Location: ../view/listar_noticias.php");
    exit;
}

/*=========================ACTUALIZAR ESTADO A ANULADA=========================*/

$estado_anterior = $noticia["estado"];

$sql_update = "UPDATE noticias SET estado = 'Anulada' WHERE id_noticia = ?";

$stmt_update = $conexion->prepare($sql_update);
$stmt_update->bind_param("i", $id_noticia);
$stmt_update->execute();

/*=========================REGISTRAR HISTORIAL=========================*/

$sql_historial = "INSERT INTO historial (id_noticia, id_usuario, estado_anterior, estado_nuevo) 
                  VALUES (?, ?, ?, 'Anulada')";

$stmt_historial = $conexion->prepare($sql_historial);
$stmt_historial->bind_param("iis", $id_noticia, $id_usuario, $estado_anterior);
$stmt_historial->execute();

/*=========================REDIRECCIÓN=========================*/

$_SESSION["mensaje"] = "Noticia anulada correctamente";
header("Location: ../view/listar_noticias.php");
exit;