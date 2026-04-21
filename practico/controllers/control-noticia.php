<?php

session_start();

include(__DIR__ . "../config/conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id_usuario = $_POST["id_usuario"];
    $titulo = $_POST["titulo"];
    $descripcion = $_POST["descripcion"];

    /*=========================SUBIR IMAGEN (SI EXISTE)=========================*/

    $imagen_nombre = null;

    if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] === 0) {

        $carpetaDestino =
        __DIR__ . "../Assets/";

        /* Crear carpeta si no existe */

        if (!is_dir($carpetaDestino)) { mkdir($carpetaDestino, 0777, true);}

        $nombreArchivo =time() . "_" .basename($_FILES["imagen"]["name"]);

        $rutaArchivo =$carpetaDestino . $nombreArchivo;

        move_uploaded_file($_FILES["imagen"]["tmp_name"],$rutaArchivo);

        $imagen_nombre = $nombreArchivo;
    }

    /*=========================INSERTAR NOTICIA=========================*/

    $sql = "

    INSERT INTO noticias (titulo,descripcion,imagen,id_autor) VALUES (?,?,?,?)";

    $stmt = $conexion->prepare($sql);

    $stmt->bind_param("sssi",$titulo,$descripcion,$imagen_nombre,$id_usuario);

    $stmt->execute();

    /*=========================OBTENER ID NOTICIA=========================*/

    $id_noticia = $conexion->insert_id;

    /*=========================REGISTRAR HISTORIAL=========================*/

    $sql_historial = "INSERT INTO historial (id_noticia,id_usuario,estado_anterior,estado_nuevo)VALUES (?,?,NULL,'Borrador')";

    $stmt2 = $conexion->prepare($sql_historial);

    $stmt2->bind_param("ii",$id_noticia,$id_usuario);

    $stmt2->execute();

    /*=========================REDIRECCIÓN=========================*/

    $_SESSION["mensaje"] = "Noticia creada correctamente";
    header("Location: ../index.php");
    exit;

}
?>