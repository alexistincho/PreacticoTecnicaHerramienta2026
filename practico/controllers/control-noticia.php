<?php

session_start();

include(__DIR__ . "/../config/conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id_usuario = $_POST["id_usuario"];
    $titulo = $_POST["titulo"];
    $descripcion = $_POST["descripcion"];

    /*=========================SUBIR IMAGEN (SI EXISTE)=========================*/

    $imagen_nombre = null;

if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] === 0) {

    /* Validar tamaño */
    if ($_FILES["imagen"]["size"] > 2097152) {
        $_SESSION["error"] = "La imagen supera los 2MB";
        header("Location: ../index.php");
        exit;
    }

    /* Validar tipo */

    $tipo = mime_content_type($_FILES["imagen"]["tmp_name"]);

    if ($tipo != "image/jpeg" && $tipo != "image/png") {
        $_SESSION["error"] = "Solo se permiten imágenes JPG o PNG";
        header("Location: ../index.php");
        exit;
    }

    $carpetaDestino = __DIR__ . "/../Assets/imagenes/";

    if (!is_dir($carpetaDestino)) {mkdir($carpetaDestino, 0777, true);}

    $nombreArchivo =time() . "_" .basename($_FILES["imagen"]["name"]);

    $rutaArchivo =$carpetaDestino . $nombreArchivo;

    if (!move_uploaded_file($_FILES["imagen"]["tmp_name"],$rutaArchivo)) {
        $_SESSION["error"] ="Error al subir la imagen";
        header("Location: ../index.php");
        exit;
    }

    $imagen_nombre = $nombreArchivo;
    
}

    /*=========================INSERTAR NOTICIA=========================*/

    $sql = "INSERT INTO noticias (titulo,descripcion,imagen,id_autor) VALUES (?,?,?,?)";

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


/*=========================VERIFICAR TÍTULO DUPLICADO=========================*/

$sql_check = "SELECT id_noticia FROM noticias 
              WHERE titulo = ? 
              AND estado NOT IN ('Expirada', 'Anulada')";

$stmt_check = $conexion->prepare($sql_check);
$stmt_check->bind_param("s", $titulo);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    $_SESSION["error"] = "Ya existe una noticia con ese título";
    header("Location: ../view/crear_noticia.php");
    exit;
}

$stmt_check->close();
?>