<?php
    include_once "conexion.php";
    include_once "funciones.php";

    if (isset($_GET['id'])){
        $id = $_GET['id'];
        
        // Inventario borrar
        $query_inventario = $mysqli->prepare("DELETE FROM inventario WHERE inventario.id = ?");
        $query_inventario->bind_param("i", $id);
        $query_inventario->execute();
        $query_inventario->close();

        // Colores borrar
        $query_colores = $mysqli->prepare("DELETE FROM colores WHERE colores.id_prenda = ?");
        $query_colores->bind_param("i", $id);
        $query_colores->execute();
        $query_colores->close();

        // Fotos borrar
        $query_fotos = $mysqli->prepare("DELETE FROM fotos WHERE fotos.id_prenda = ?");
        $query_fotos->bind_param("i", $id);
        $query_fotos->execute();
        $query_fotos->close();

        // Borrar fotografias subidas
        $query_file_fotos = $mysqli->query("SELECT * from fotos WHERE fotos.id_prenda = {$id}");
        while ($raw = $query_file_fotos->fetch_assoc()){
            if (file_exists($raw['ruta'])){
                if (unlink($raw['ruta'])){
                }else{
                    echo "Error al borrar los ficheros";
                }
            }
        }
        

        // Stock borrar
        $query_stok = $mysqli->prepare("DELETE FROM stock WHERE stock.id_prenda = ?");
        $query_stok->bind_param("i", $id);
        $query_stok->execute();
        $query_stok->close();

        // Tallas borrar
        $query_talls = $mysqli->prepare("DELETE FROM tallas WHERE tallas.id_prenda = ?");
        $query_talls->bind_param("i", $id);
        $query_talls->execute();
        $query_talls->close();
        $mysqli->close();

        ob_end_clean();
        header('location: inventario.php');
        exit();
    }
?>