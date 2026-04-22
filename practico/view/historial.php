<?php
if (isset($_GET["id_historial"])) {

    $id_noticia = $_GET["id_historial"];

    echo '<div class="container mt-5">
            <h3 class="text-center">Historial de la noticia</h3>';

    /*=========================LEFT JOIN PARA CONTEMPLAR EXPIRACIÓN AUTOMÁTICA=========================*/

    $sql = "SELECT h.*, u.nombre FROM historial h 
            LEFT JOIN usuarios u ON h.id_usuario = u.id_usuario 
            WHERE h.id_noticia = ? 
            ORDER BY h.fecha DESC";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_noticia);
    $stmt->execute();
    $resultado = $stmt->get_result();

    echo '<table class="table table-bordered table-striped text-center">
            <thead class="table-dark">
                <tr>
                    <th>Usuario</th>
                    <th>Estado anterior</th>
                    <th>Estado nuevo</th>
                    <th>Fecha</th>
                </tr>
            </thead>
          <tbody>';

    if ($resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {

            /*=========================SI ES NULL FUE AUTOMÁTICO=========================*/
            $nombre = $fila["nombre"] ?? "Sistema (automático)";

            echo '<tr>
                    <td>' . $nombre . '</td>
                    <td>' . ($fila["estado_anterior"] ?? "-") . '</td>
                    <td>' . $fila["estado_nuevo"] . '</td>
                    <td>' . $fila["fecha"] . '</td>
                  </tr>';
        }
    } else {
        echo '<tr><td colspan="4">No hay historial para esta noticia</td></tr>';
    }

    echo '</tbody></table></div>';
}
?>