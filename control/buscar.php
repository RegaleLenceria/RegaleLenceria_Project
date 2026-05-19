<?php
    include_once "conexion.php";
    include_once "funciones.php";
    
    $termino = isset($_GET['q']) ? $mysqli->real_escape_string($_GET['q']) : '';

    $query = "SELECT * FROM inventario 
              WHERE              
              codigo LIKE '%$termino%'
              OR
              nombre_prenda LIKE '%$termino%' 
              OR 
              descripcion_corta LIKE '%$termino%' 
              OR
              descripcion_larga LIKE '%$termino%'
              OR
              marca_prenda LIKE '%$termino%'";
        
              

    $resultado = $mysqli->query($query);

    $datos = [];
    while($row = $resultado->fetch_assoc()){
        $datos[] = $row;
    }

    echo json_encode($datos);
?>