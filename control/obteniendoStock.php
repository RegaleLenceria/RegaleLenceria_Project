<?php
    include_once "conexion.php";

    $datos = [];
    $obteniendoPrendas = $mysqli->query("SELECT * FROM inventario LIMIT 10");
    
    while ($row = $obteniendoPrendas->fetch_assoc()){
        $datos = $row;
    }
        
    echo json_encode($datos);
?>