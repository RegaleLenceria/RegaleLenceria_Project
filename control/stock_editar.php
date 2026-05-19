<?php
    include_once "conexion.php";
    include_once "funciones.php";


    $id_prenda = $_GET['id_prenda'] ?? "";
    $codigo = $_GET['codigo'] ?? "";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Editar Stock</title>
    <style>
        span{
            padding: 5px;
            border: 1px solid #808080;
            border-radius: 5px;
            color:#808080;
        }
    </style>
</head>
<?php include "includes/nav.php"; ?>
<body>
    
    <header>
        <h1>Editar Stock</h1> 
        <h3>Codigo: <?= $codigo ?></h3>
    </header>

    <section>
        <div class="box-inventario">
            <form action="" method="post">
<?php
    $id_color = $_GET['id'];
    $sql = "SELECT * FROM stock WHERE id = {$id_color}";
    $datos = $mysqli->query($sql);
    while ($row = $datos->fetch_assoc()){
        $dato_color = obteniendoColor($row['codigo_color'], $id_prenda);

        if ($row['estado'] == 'si'){
            $estado = "Activo";
        } else {
            $estado = "Desactivar";
        }
?>              
                <input type="hidden" name="id_prenda" value="<?= $row['id_prenda']; ?>">
                <input type="hidden" name="id" value="<?= $row['id']; ?>">
                
                <label for="color">CODIGO COLOR: </label>    
<?php
        $color = $dato_color->fetch_assoc();
        if ($color['estado'] != "no"){
            if ($color['tipo_color'] != "entero"){
                $imgEstampado = $color["img_estampado"];
                echo '<span title="'.$color['descripcion'].'" class="color-list-estampado" style="background-image:url('.$imgEstampado.');">'.$color['codigo_color'].'</span>';
            }else{
                echo '<span title="'.$color['descripcion'].'" class="color-list" style="background:'.$color['color_hex'].';">'.$color['codigo_color'].'</span>';  
            }
        }
?>                  
                </br></br><label for="talla">Talla:</label>
                <input type="text" name="talla" value="<?= $row['talla']; ?>" required></br>

                <label for="stock">Stock</label>    
                <input type="number" min="0" name="stock" value="<?= $row['stock']; ?>" required></br>
                
                <label for="estado">Estado</label>
                <select name="estado">
                    <option value="<?= $row['estado']; ?>"><?php echo $estado; ?></option>
                    <option value="si">Activo</option>
                    <option value="no">Desactivar</option>
                </select>

                <button type="submit" name="actualizar_stock">ACTUALIZAR</button>

                
<?php } ?>
            </form>

<?php
    if (!empty($msg)){
        echo '<div class="msg-done">';
        echo '<p>Codigo actualizado con exito!!!</p>';
        echo '<div>';
    }

    if (isset($_POST['actualizar_stock'])){
        $id = $_POST['id'];
        $id_prenda = $_POST['id_prenda'];
        $talla = $_POST['talla'];
        $stock = $_POST['stock'];
        $estado = $_POST['estado'];
        
        $sql = "UPDATE stock SET talla = ?,
                                 stock = ?,
                                 estado = ?
                WHERE id = ?";

        $consulta = $mysqli->prepare($sql);
        $consulta->bind_param("ssss", $talla, $stock, $estado, $id);
        if ($consulta->execute()){
            $msg = "Codigo actualizado con exito";
            
            ob_end_clean();
            header("Location: stock.php?id=$id_prenda&codigo=$codigo");
            exit();
        }
    }
?>
        </div> 
    </section>
        <?php  include 'includes/atras.php'; ?>
</body>
</html>