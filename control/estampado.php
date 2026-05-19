<?php
    include_once "conexion.php";
    include_once "funciones.php";

    $id = $_POST['id'];
    $tipo_color = $_POST['tipo_color'];
    $codigo_color = $_POST['codigoColor'];
    $img_estampado = $_FILES['imagen'];
    $descripcion = $_POST['descripcion'];

    // Si el color es estampado (imagen) guardar ruta de imagen 
    if ($img_estampado != 0){ 
        $ruta_guardar = "upload/estampados/";
        $nombre_imagen = $img_estampado['name'];
        $tmp_imagen = $img_estampado['tmp_name'];
            
        $ruta_completa = $ruta_guardar.$nombre_imagen;

        if (move_uploaded_file($tmp_imagen, $ruta_completa)){
            $img_estampado = $ruta_completa;
        }
    }
        
    $estado = $_POST['estado'];

    $stmt = $mysqli->prepare("INSERT INTO 
                                colores (id_prenda, tipo_color, codigo_color, descripcion, img_estampado, estado)
                              VALUES (?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("isssss", $id, $tipo_color, $codigo_color, $descripcion,
                                    $img_estampado, $estado);

    $stmt->execute();
    $stmt->close();

    ob_end_clean();
    header("location: colores.php?id={$id}");
    exit();

?>