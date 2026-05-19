<?php
    include_once "control/funciones.php";
    include_once "control/conexion.php";
    
    $search = mysqli_real_escape_string($mysqli, trim($_POST["buscador"]));

    // Funcion encargada de obtener datos 
    // de las prendas en la base de datos
    function obtenerPrenda($id){
        global $mysqli;
        $consulta = $mysqli->query("SELECT inventario.id, 
                                          inventario.nombre_prenda,
                                          inventario.precio_base,
                                          inventario.genero,
                                          inventario.estado,
                                          GROUP_CONCAT(fotos.ruta) AS fotos
                                   FROM inventario
                                   LEFT JOIN fotos ON inventario.id = fotos.id_prenda
                                   WHERE inventario.id = '{$id}'
                                   GROUP BY inventario.id
                                ");
        $fila = $consulta->fetch_assoc();
        return $fila;
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regale Lenceria | Buscador</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <link rel="stylesheet" href="src/style/style.css?v=<?= ASSETS_VERSION ?>">
    <link rel="stylesheet" href="src/style/tienda.css?v=<?= ASSETS_VERSION ?>">

</head>
<body>
    <section>
<?php include("nav.php"); ?>
        <div class="container">
            <div class="box-prendas">
<?php
    if (!isset($_POST["filtro"])){
        $sql = "SELECT * FROM inventario WHERE nombre_prenda LIKE '%$search%'";
        $consulta = $mysqli->query($sql);
        if ($consulta->num_rows != 0){
            while($fila = $consulta->fetch_assoc()){
                if ($fila['estado'] != "no"){
                    $listaFotos = 'control/'.obtenerPrenda($fila['id'])['fotos'];
                    $foto = explode(",", $listaFotos)[0];

                    echo '<div class="prenda">
                            <div class="prenda-img">';
                    echo '<img src="'.$foto.'" alt="Preview">';
                    echo '  </div>';
                    echo '<div class="prenda-info">';
                    echo "<h4>".obtenerPrenda($fila['id'])['nombre_prenda']."</h4>";
                    echo "<p>BS.".obtenerPrenda($fila['id'])['precio_base']."</p>";

                    echo '<a href="vista.php?id='.obtenerPrenda($fila["id"])['id'].'">Ver más</a>';
                    echo "</div>";
                    echo "</div>";
                }
            }
        }else{
            echo "<p>No se encontraron resultados</p>";
        }
    }else{
        $filtro = $_POST["filtro"];

        switch($filtro){
            // Codigo
            case 'codigo':
                $sql = "SELECT * FROM inventario WHERE codigo LIKE '%$search%'";
                $consulta = $mysqli->query($sql);
                if ($consulta->num_rows != 0){
                    while($fila = $consulta->fetch_assoc()){
                        if ($fila["estado"] != "no"){
                            $listaFotos = 'control/'.obtenerPrenda($fila['id'])['fotos'];
                            $foto = explode(",", $listaFotos)[0];
            
                            echo '<div class="prenda">
                                    <div class="prenda-img">';
                            echo '<img src="'.$foto.'" alt="Preview">';
                            echo '  </div>';
                            echo '<div class="prenda-info">';
                            echo "<h4>".obtenerPrenda($fila['id'])['nombre_prenda']."</h4>";
                            echo "<p>BS.".obtenerPrenda($fila['id'])['precio_base']."</p>";
            
                            echo '<a href="vista.php?id='.obtenerPrenda($fila["id"])['id'].'">Ver más</a>';
                            echo "</div>";
                            echo "</div>";
                        }
                    }
                }else{
                    echo "<p>No se encontraron resultados</p>";
                }
                break;
            // Color
            case 'color':
                $sql = "SELECT * FROM colores WHERE codigo_color LIKE '%$search%'";
                $consulta = $mysqli->query($sql);
                if ($consulta->num_rows != 0){
                    while($fila = $consulta->fetch_assoc()){
                        if ($fila["estado"] != "no"){
                            $listaFotos = 'control/'.obtenerPrenda($fila['id_prenda'])['fotos'];
                            $foto = explode(",", $listaFotos)[0];
            
                            echo '<div class="prenda">
                                    <div class="prenda-img">';
                            echo '<img src="'.$foto.'" alt="Preview">';
                            echo '  </div>';
                            echo '<div class="prenda-info">';
                            echo "<h4>".obtenerPrenda($fila['id_prenda'])['nombre_prenda']."</h4>";
                            echo "<p>BS.".obtenerPrenda($fila['id_prenda'])['precio_base']."</p>";
            
                            echo '<a href="vista.php?id='.obtenerPrenda($fila["id_prenda"])['id'].'">Ver más</a>';
                            echo "</div>";
                            echo "</div>";
                        }
                    }
                }else{
                    echo "<p>No se encontraron resultados</p>";
                }
                break;
            // Talla
            case 'talla':
                $sql = "SELECT * FROM tallas WHERE talla LIKE '%$search%'";
                $consulta = $mysqli->query($sql);
                if ($consulta->num_rows != 0){
                    while($fila = $consulta->fetch_assoc()){
                        if ($fila["estado"] != "no"){
                            $listaFotos = 'control/'.obtenerPrenda($fila['id_prenda'])['fotos'];
                            $foto = explode(",", $listaFotos)[0];
            
                            echo '<div class="prenda">
                                    <div class="prenda-img">';
                            echo '<img src="'.$foto.'" alt="Preview">';
                            echo '  </div>';
                            echo '<div class="prenda-info">';
                            echo "<h4>".obtenerPrenda($fila['id_prenda'])['nombre_prenda']."</h4>";
                            echo "<p>BS.".obtenerPrenda($fila['id_prenda'])['precio_base']."</p>";
            
                            echo '<a href="vista.php?id='.obtenerPrenda($fila["id_prenda"])['id'].'">Ver más</a>';
                            echo "</div>";
                            echo "</div>";
                        }
                    }
                }else{
                    echo "<p>No se encontraron resultados</p>";
                }
                break;
        }
    }
?>
            </div>
        </div>
    </section>
</body>
</html>