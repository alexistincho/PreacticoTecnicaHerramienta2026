<?php

include(__DIR__ . "/../config/conexion.php");

/*=========================OBTENER DÍAS DE EXPIRACIÓN DESDE PARÁMETROS=========================*/

$sql_param = "SELECT dias_expiracion FROM parametros LIMIT 1";
$resultado_param = $conexion->query($sql_param);
$parametro = $resultado_param->fetch_assoc();
$dias = $parametro["dias_expiracion"];

/*=========================BUSCAR NOTICIAS PUBLICADAS QUE SUPERARON LOS DÍAS=========================*/

$sql = "SELECT id_noticia FROM noticias 
        WHERE estado = 'Publicada' 
        AND DATEDIFF(NOW(), fecha_publicacion) >= ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $dias);
$stmt->execute();
$resultado = $stmt->get_result();

/*=========================EXPIRAR CADA NOTICIA Y REGISTRAR HISTORIAL=========================*/

while ($fila = $resultado->fetch_assoc()) {

    $id_noticia = $fila["id_noticia"];

    /* Cambiar estado a Expirada */
    $sql_update = "UPDATE noticias SET estado = 'Expirada' WHERE id_noticia = ?";
    $stmt_update = $conexion->prepare($sql_update);
    $stmt_update->bind_param("i", $id_noticia);
    $stmt_update->execute();

    /* Registrar en historial con id_usuario NULL porque es automático */
    $sql_historial = "INSERT INTO historial (id_noticia, id_usuario, estado_anterior, estado_nuevo) 
                      VALUES (?, NULL, 'Publicada', 'Expirada')";
    $stmt_historial = $conexion->prepare($sql_historial);
    $stmt_historial->bind_param("i", $id_noticia);
    $stmt_historial->execute();
}

