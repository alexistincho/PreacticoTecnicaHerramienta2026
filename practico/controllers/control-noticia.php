<?php

session_start();

include(__DIR__ . "/../config/conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id_usuario  = $_POST["id_usuario"];
    $titulo      = $_POST["titulo"];
    $descripcion = $_POST["descripcion"];

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

    /*=========================OBTENER TAMAÑO MÁXIMO DESDE PARÁMETROS=========================*/

    $sql_param = "SELECT tamanio_imagen FROM parametros LIMIT 1";
    $resultado_param = $conexion->query($sql_param);
    $parametro = $resultado_param->fetch_assoc();

    // VALIDACIÓN: Si existe el parámetro en la BD lo usamos, si no, asignamos 2 MB por defecto
    if ($parametro && isset($parametro["tamanio_imagen"])) {
        $megas = $parametro["tamanio_imagen"];
    } else {
        $megas = 2; // Valor de respaldo por seguridad
    }

    $tamanio_maximo = $megas * 1048576; // MB a bytes

    /*=========================SUBIR IMAGEN (SI EXISTE)=========================*/

    $imagen_nombre = null;

    if (isset($_FILES["imagen"]) && $_FILES["imagen"]["size"] > 0) {

        /* Verificar errores de subida de PHP */
        if ($_FILES["imagen"]["error"] === UPLOAD_ERR_INI_SIZE ||
            $_FILES["imagen"]["error"] === UPLOAD_ERR_FORM_SIZE) {
            $_SESSION["error"] = "La imagen supera el tamaño máximo permitido";
            header("Location: ../view/crear_noticia.php");
            exit;
        }

        if ($_FILES["imagen"]["error"] !== UPLOAD_ERR_OK) {
            $_SESSION["error"] = "Error al subir la imagen";
            header("Location: ../view/crear_noticia.php");
            exit;
        }

        /* Validar tamaño con el valor de la BD */
        if ($_FILES["imagen"]["size"] > $tamanio_maximo) {
            $_SESSION["error"] = "La imagen supera los " . $parametro["tamanio_imagen"] . "MB permitidos";
            header("Location: ../view/crear_noticia.php");
            exit;
        }

        /* Validar tipo */
        $tipo = mime_content_type($_FILES["imagen"]["tmp_name"]);

        if ($tipo != "image/jpeg" && $tipo != "image/png") {
            $_SESSION["error"] = "Solo se permiten imágenes JPG o PNG";
            header("Location: ../view/crear_noticia.php");
            exit;
        }

        $carpetaDestino = __DIR__ . "/../Assets/imagenes/";

        if (!is_dir($carpetaDestino)) {
            mkdir($carpetaDestino, 0777, true);
        }

        $nombreArchivo = time() . "_" . basename($_FILES["imagen"]["name"]);
        $rutaArchivo   = $carpetaDestino . $nombreArchivo;

        if (!move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaArchivo)) {
            $_SESSION["error"] = "Error al subir la imagen";
            header("Location: ../view/crear_noticia.php");
            exit;
        }

        $imagen_nombre = $nombreArchivo;
    }

    /*=========================INSERTAR NOTICIA=========================*/

    $sql = "INSERT INTO noticias (titulo, descripcion, imagen, id_autor) VALUES (?, ?, ?, ?)";

    $stmt = $conexion->prepare($sql);
    
    // Si la imagen viene vacía o nula, nos aseguramos de pasar un NULL limpio o el string correspondiente
    if ($imagen_nombre === null) {
        $param_imagen = null;
    } else {
        $param_imagen = $imagen_nombre;
    }

    // Pasamos $param_imagen en lugar de la variable original para evitar conflictos de referencia en PHP
    $stmt->bind_param("ssss", $titulo, $descripcion, $param_imagen, $id_usuario); 
    
    if (!$stmt->execute()) {
        
        $_SESSION["error"] = "Error en la base de datos: " . $stmt->error;
        header("Location: ../view/crear_noticia.php");
        exit;
    }

    /*=========================OBTENER ID NOTICIA=========================*/

    $id_noticia = $conexion->insert_id;

    /*=========================REGISTRAR HISTORIAL=========================*/

    $sql_historial = "INSERT INTO historial (id_noticia, id_usuario, estado_anterior, estado_nuevo) 
                      VALUES (?, ?, NULL, 'Borrador')";

    $stmt2 = $conexion->prepare($sql_historial);
    $stmt2->bind_param("ii", $id_noticia, $id_usuario);
    $stmt2->execute();

    /*=========================REDIRECCIÓN=========================*/

    $_SESSION["mensaje"] = "Noticia creada correctamente";
    header("Location: ../view/listar_noticias.php");
    exit;
}
