<?php
    include_once "conexion.php";
    include_once 'funciones.php';

    ob_start();

    if (isset($_GET['id']) && !empty($_GET['id'])){
        if (is_numeric($_GET['id'])){
            $id = intval($_GET['id']);
        }else{
            ob_end_clean();
            header("Location: inventario.php");
            exit();
        }

        $codigo = isset($_GET['codigo']) ? htmlspecialchars($_GET['codigo']) : '';
    }

    elseif(isset($_GET['borrar'])){
        $query = $mysqli->prepare("DELETE FROM stock WHERE id = ?");
            
        if ($query){
            $query->bind_param("i", $_GET['borrar']);
            $query->execute();
            $query->close();

            $registro = ["user" => $_SESSION['username'],
            "actividad" => "Stock borrado del codigo (".$codigo.").",
            "nivel" => 3];

            registroActividad($registro);

            ob_end_clean();
            header("Location: stock.php?id=".$_GET['prenda-id']."&codigo=".$_GET['codigo']);
            exit();

        }else{
                echo "<script>alert('Error al borrar el item')</script>";
        }
    }

    elseif (isset($_POST['actualizar_stock'])){
        $stock_id = intval($_POST['stock_id']);
        $codigo_color = htmlspecialchars($_POST['codigo_color']);
        $talla = htmlspecialchars($_POST['talla']);
        $stock = intval($_POST['stock']);
        $estado = htmlspecialchars($_POST['estado']);

        $datos = $mysqli->prepare("UPDATE stock 
                                   SET codigo_color = ?, talla = ?, stock = ?, estado = ?
                                   WHERE id = ?");
        $datos->bind_param("ssisi", $codigo_color, $talla, $stock, $estado, $stock_id);
        
        if ($datos->execute()){
            $datos->close();
            ob_end_clean();
            header("Location: stock.php?id=".$id."&codigo=".$codigo);
            exit();
        } else {
            echo "<script>alert('Error al actualizar el stock')</script>";
            $datos->close();
        }
    }

    else{
        ob_end_clean();
        header('location: inventario.php');
        exit(); 
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de control | Stock</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="style/style.css">
    <!-- Jquery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <style>
        .color-list, .color-list-estampado{  
            padding: 5px;
            border-radius: 5px;
            border: solid 1px #818181;
            color:#818181;
        }

        .color-list-estampado{
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: white;
            text-shadow: 1px 1px 2px black;
        }
    </style>
</head>
<body>
    <?php include "includes/menu.php"; ?>
    
    <header>
        <h1>Stock</h1>
        <h4>Codigo: <?php echo $codigo; ?></h4>
    </header>

    <div class="modal">
        <div class="box-modal">
            <form action="" method="post">
                <!-- Boton para salir -->
                <div class="salir">
                    <a href="#"><i class="fa-solid fa-circle-xmark"></i></a>
                </div>
                
                <label for="codigo_color">Codigo Color</label>
                <select name="codigo_color">
<?php
    $datos_colores = $mysqli->query("SELECT * FROM colores WHERE id_prenda = $id");
    while($row = $datos_colores->fetch_assoc()){
        echo "<option value='{$row['codigo_color']}'>{$row['codigo_color']} - {$row['descripcion']}</option>";
    }
    $datos_colores->close();
?>
                </select>

                <label for="talla">Talla</label>
                <select name="talla">
<?php
    $datos_tallas = $mysqli->query("SELECT * FROM tallas WHERE id_prenda = $id");
    while($row = $datos_tallas->fetch_assoc()){
        echo "<option value='{$row['talla']}'>{$row['talla']}</option>";
    }
    $datos_tallas->close();
?>
                </select>
                
                <label for="stock">Stock</label>
                <input type="number" name="stock" required>

                <label for="estado">Estado</label>
                <select name="estado">
                    <option value="si">Activa</option>
                    <option value="no">Desactivado</option>
                </select>

                <button>GUARDAR</button>
            </form>
        </div>
<?php
    if ($_SERVER['REQUEST_METHOD'] == "POST"){
        $codigo_color = $_POST['codigo_color'];
        $talla = $_POST['talla'];
        $stock = $_POST['stock'];
        $estado = $_POST['estado'];

        $datos = $mysqli->prepare("INSERT INTO stock (id_prenda, codigo_color, talla, stock, estado) VALUES (?, ?, ?, ?, ?)");
        $datos->bind_param("issis", $id, $codigo_color, $talla, $stock, $estado);
        $datos->execute();
        $datos->close();

        ob_end_clean();
        header("location: stock.php?id=$id&codigo=$codigo");
        exit();
    }
?>
    </div>

    <!-- MODAL ACTUALIZAR -->
    <div class="modal" id="update">
        <div class="box-modal">
            <form action="" method="post">
                <div class="salir" onclick="cerrarModal()">
                    <a href="#"><i class="fa-solid fa-circle-xmark"></i></a>
                </div>
                
                <input type="hidden" name="stock_id" id="stock_id">
                
                <label for="codigo_color">Codigo Color</label>
                <select name="codigo_color" id="codigo_color_edit">
<?php
    $datos_colores = $mysqli->query("SELECT * FROM colores WHERE id_prenda = $id");
    while($row = $datos_colores->fetch_assoc()){
        echo "<option value='{$row['codigo_color']}'>{$row['codigo_color']} - {$row['descripcion']}</option>";
    }
    $datos_colores->close();
?>
                </select>

                <label for="talla">Talla</label>
                <select name="talla" id="talla_edit">
<?php
    $datos_tallas = $mysqli->query("SELECT * FROM tallas WHERE id_prenda = $id");
    while($row = $datos_tallas->fetch_assoc()){
        echo "<option value='{$row['talla']}'>{$row['talla']}</option>";
    }
    $datos_tallas->close();
?>
                </select>
                
                <label for="stock">Stock</label>
                <input type="number" min="0" name="stock" id="stock_edit" required>

                <label for="estado">Estado</label>
                <select name="estado" id="estado_edit">
                    <option value="si">Activa</option>
                    <option value="no">Desactivado</option>
                </select>

                <button type="submit" name="actualizar_stock">GUARDAR</button>       
            </form>
        </div>
    </div>

    <div class="container">
        <div class="menu">
            <div class="btn-add">
                <button onclick="viewModal()"><i class="fa-solid fa-plus"></i> NUEVO STOCK</button>
            </div>
        </div>

        <div class="lista-inventario">
            <table>
                <tr>
                    <th>COLOR</th>
                    <th>TALLA</th>
                    <th>STOCK DISPONIBLE</th>
                    <th>ESTADO</th>
                    <th>OPCIONES</th>
                </tr>
<?php
    $datos = $mysqli->query("SELECT * FROM stock
                             WHERE id_prenda = $id
                             ORDER BY codigo_color, CAST(talla AS UNSIGNED), talla");

    while($row = $datos->fetch_assoc()){
?>
    <tr>
        <td><?php
            //Obteniendo datos del color 
            $dato_color = obteniendoColor($row['codigo_color'], $id);
            $color = $dato_color->fetch_assoc();
            if ($color['estado'] != "no"){
                if ($color['tipo_color'] != "entero"){
                    $imgEstampado = $color["img_estampado"];
                    echo '<span title="'.$color['descripcion'].'" class="color-list-estampado" style="background-image:url('.$imgEstampado.');">'.$color['codigo_color'].'</span>';
                }else{
                    echo '<span title="'.$color['descripcion'].'" class="color-list" style="background:'.$color['color_hex'].';">'.$color['codigo_color'].'</span>';  
                }
            }
            $dato_color->close();   
        ?></td>
        <td><?= strtoupper($row['talla']); ?></td>
        <td>
<?php
    if ($row['stock'] < 2){
        echo '<span style="color:white;background:#e75b5b;padding:5px;border-radius:5px;">'.$row['stock'].'</span>'; 
    }
    elseif ($row['stock'] <= 5){
        echo '<span style="color:white;background:orange;padding:5px;border-radius:5px;">'.$row['stock'].'</span>'; 
    }
    else{
        echo '<span style="color:white;background:#3ac062;padding:5px;border-radius:5px;">'.$row['stock'].'</span>'; 
    }
?>
        </td>
        <td>
<?php
    if($row['estado'] != "si"){
        echo '<i title="Desactivado" style="color:#818181;" class="fa-solid fa-eye-slash"></i> Desactivado';
    }else{
        echo '<i title="Activado" class="fa-solid fa-eye"></i> Activado';
    }
?>
        </td>
        
        <td>
            <ul>
                <li><a class="btn-editar" title="Editar" href="#" data-id="<?= $row['id'] ?>" data-color="<?= $row['codigo_color'] ?>" data-talla="<?= $row['talla'] ?>" data-stock="<?= $row['stock'] ?>" data-estado="<?= $row['estado'] ?>"><i class="fa-solid fa-pen-to-square"></i></a></li>
                <li><a id="btn-borrar" data-id="<?php echo $row['id']; ?>" data-prenda="<?php echo $id; ?>" data-codigo="<?php echo $codigo; ?>" title="Borrar" href="#"><i style="color:red;" class="fa-solid fa-trash-can"></i></a></li>
            </ul>
        </td>
    </tr>
<?php
    } 
?>

<script>
    function cerrarModal(){
        const modal = document.querySelector('#update');
        modal.style.display = "none";
    }

    function abrirModalEditar(btn){
        btn.preventDefault();
        
        const id = btn.currentTarget.getAttribute('data-id');
        const color = btn.currentTarget.getAttribute('data-color');
        const talla = btn.currentTarget.getAttribute('data-talla');
        const stock = btn.currentTarget.getAttribute('data-stock');
        const estado = btn.currentTarget.getAttribute('data-estado');
        
        document.getElementById('stock_id').value = id;
        document.getElementById('codigo_color_edit').value = color;
        document.getElementById('talla_edit').value = talla;
        document.getElementById('stock_edit').value = stock;
        document.getElementById('estado_edit').value = estado;
        
        const modal = document.querySelector('#update');
        modal.style.display = "flex";
    }

    window.addEventListener('DOMContentLoaded', function(){
        // Botones editar
        const btn_editar = document.querySelectorAll('.btn-editar');
        Array.from(btn_editar).forEach( btn => {
            btn.addEventListener('click', abrirModalEditar);
        });

        // Botones eliminar stock
        const btn_eliminar_stock = document.querySelectorAll('#btn-borrar');
        Array.from(btn_eliminar_stock).forEach( btn => {
            btn.addEventListener('click', function(e){
                e.preventDefault();
                const id = btn.getAttribute('data-id');
                const prenda_id = btn.getAttribute('data-prenda');
                if (confirm('¿Estas seguro de borrar los datos?')){
                    window.location.href = `stock.php?borrar=${id}&prenda-id=${prenda_id}&codigo=<?php echo $codigo; ?>`;
                }
            });
        });
    });
</script>

<script>
    function actualizar(){
        const modal_actualizar = document.querySelector("#update");
        modal_actualizar.style.display = "flex";
    }

    function viewModal(){
        const modal = document.querySelector('.modal:not(#update)');
        modal.style.display = "flex";
    }

    const modal = document.querySelector('.modal:not(#update)');
    if(modal) {
        const modal_salir = modal.querySelector('.salir');
        if(modal_salir) {
            modal_salir.addEventListener('click', function(){
                modal.style.display = "none";
            });
        }
    }
</script>
            </table>
        </div>
    </div>

    <?php  include 'includes/atras.php'; ?>
</body>
</html>