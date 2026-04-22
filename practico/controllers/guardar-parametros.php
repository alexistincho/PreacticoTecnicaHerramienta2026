<?php

session_start();

include(__DIR__ . "/../config/conexion.php");

/*=========================VERIFICAR SESIÓN=========================*/

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../view/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $dias_expiracion = $_POST["dias_expiracion"];
    $tamanio_imagen  = $_POST["tamanio_imagen"];

    /*=========================ACTUALIZAR PARÁMETROS=========================*/

    $sql = "UPDATE parametros SET dias_expiracion = ?, tamanio_imagen = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ii", $dias_expiracion, $tamanio_imagen);
    $stmt->execute();

    $_SESSION["mensaje"] = "Parámetros guardados correctamente";
    header("Location: ../view/parametros.php");
    exit;
}