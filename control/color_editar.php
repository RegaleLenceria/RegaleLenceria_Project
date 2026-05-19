<?php
    include_once "conexion.php";
    include_once "funciones.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Talla - </title>
<?php include_once "includes/librerias.php"; ?>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
<?php
    if (isset($_GET['id']) && isset($_GET['codigo'])){
        $id = $_GET['id'];
        $codigo = htmlspecialchars($_GET['codigo']);
?>
    <header>
        <h1>Editar Color</h1>
        <h4>Codigo: <?php echo $codigo; ?></h4>
    </header>
    
    <div class="container">
  <div class="container-editar">
        <div class="box-editar">
            <form action="" method="post">    
            <div class="form-data">
<?php
        $datos = $mysqli->query("SELECT * FROM colores WHERE id = {$id}");
        while ($row = $datos->fetch_assoc()):
?>
        <input type="hidden" name="prenda_id" value="<?= $row['id_prenda'] ?>">
        <label for="codigo_color">Codigo Color</label>
        <input type="text" name="codigo_color" maxlength="6" value="<?= $row['codigo_color'] ?>" required>
        
        </br><label for="descripcion">Descripcion</label>
        <input type="text" name="descripcion" value="<?= $row['descripcion'] ?>" required>

        <div class="form-data">
            <label for="estado">Estado</label>
            <select name="estado">
                
                <option value="activo">Activo</option>
                <option value="inactivo">Inactivo</option>
            </select>
        </div>
<?php
    endwhile;
?>
                </div>            
<?php
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        if (!empty($_POST['codigo_color']) && !empty($_POST['descripcion'])){
            $codigo_color = $_POST['codigo_color'];
            $descripcion = $_POST['descripcion'];
            $estado = $_POST['estado'];
            $prenda_id = $_POST['prenda_id'];

            $stmt = $mysqli->prepare("UPDATE colores 
                                       SET   
                                            codigo_color = ?,
                                            descripcion = ?,
                                            estado = ?
                                      WHERE 
                                            id = {$id}");
            $stmt->bind_param('sss', $codigo_color, $descripcion, $estado);
            $stmt->execute();
            $stmt->close();
            
            ob_end_clean();
            header("location: colores.php?id={$prenda_id}&codigo={$codigo}");
            exit();
        }
    }
?>
                <button>ACTUALIZAR</button>
            </form>
        </div>
     </div>
    </div>
<?php
    }
    else{
        ob_end_clean();
        header("location: inventario.php");
        exit();
    }
?>


<?php include 'includes/atras.php'; ?>
</body>
</html>