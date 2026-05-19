<?php
    include_once "conexion.php";
    include_once "funciones.php";

    $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
    $limite = 12;

    $filtros = [
        "g" => $_GET["g"] ?? null,
        "c" => $_GET["c"] ?? null,
        "m" => $_GET["m"] ?? null,
        "sub" => $_GET["sub"] ?? null,
        "limite" => $limite,
        "offset" => $offset
    ];

    $resultados = obtenerProductos($filtros);
    $html = "";

    while($row = $resultados->fetch_assoc()){
        if ($row['estado'] != "no"){
            // Obtener tallas del producto
            $tallas_producto = obtenerTallasProducto($row['id']);
            $tallas_arr = [];
            while($t = $tallas_producto->fetch_assoc()){
                $tallas_arr[] = $t['talla'];
            }
            
            // Obtener colores del producto
            $colores_producto = obtenerColoresProducto($row['id']);
            $colores_arr = [];
            while($col = $colores_producto->fetch_assoc()){
                $colores_arr[] = $col['codigo_color'];
            }
            
            if(!empty($row['fotos'])){
                $fotos_arr = explode(",", $row['fotos']);
                $front = "control/".trim($fotos_arr[0]);
                if (isset($fotos_arr[1]) && trim($fotos_arr[1]) !== ''){
                    $back = "control/".trim($fotos_arr[1]);
                } else {
                    $back = $front;
                }
            } else {
                $front = 'src/imgs/placeholder.png';
                $back = $front;
            }

            $html .= '<div class="prenda" data-talla="'.implode(',', $tallas_arr).'" data-color="'.implode(',', $colores_arr).'" data-precio="'.$row['precio_base'].'">';
            $html .= '<div class="prenda-img">';
            $html .= '<a title="'.$row['codigo'].'" href="vista.php?id='.$row['id'].'">';
            $html .= '<img class="img-front" src="'.$front.'" alt="Frontal">';
            $html .= '<img class="img-back" src="'.$back.'" alt="Trasera">';
            $html .= '</a>';
            $html .= '</div>';
            
            $html .= '<div class="prenda-info">';
            $html .= '<div class="prenda-info-title"><h4>'.$row['nombre_prenda'].'</h4></div>';
            $html .= '<div class="prenda-info-precio"><p>'.$row['precio_base'].'BS</p></div>';
            $html .= '<div class="prenda-info-colores">';
            
            $colores = vistaColors($row['id']);
            while ($color = $colores->fetch_assoc()){
                if ($color['estado'] != "no"){
                    if ($color['tipo_color'] != "estampado"){
                        $html .= '<div class="color-prenda" style="background:'.$color['color_hex'].';" title="'.$color['codigo_color'].'"></div>';
                    }else{
                        $imgEstampado = "control/".$color['img_estampado'];
                        $html .= '<div class="color-prenda" style="background:url('.$imgEstampado.');" title="'.$color['codigo_color'].'"></div>';
                    }
                }
            }
            
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }
    }

    $mysqli->close();
    echo $html;
?>
