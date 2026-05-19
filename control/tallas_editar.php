<?php
    include_once "conexion.php";
    require_once "funciones.php";
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
    if (isset($_GET['id']) && isset($_GET['id_prenda']) && isset($_GET['codigo'])){
        $id = $_GET['id'];
        $prenda_id = $_GET['id_prenda'];
        $codigo = htmlspecialchars($_GET['codigo']);
?>
    <header>
        <h1>Editar Talla</h1>
        <h4>Codigo: <?php echo $codigo; ?></h4>
    </header>
    
    <div class="container">
  <div class="container-editar">
        <div class="box-editar">
            <form action="" method="post">    
            <div class="form-data">
            <label for="talla">Talla</label>
<?php
        $datos = $mysqli->query("SELECT * FROM tallas WHERE id = {$id}");
        while ($row = $datos->fetch_assoc()){
            echo '<input type="text" name="talla" maxlength="6" value="'.$row['talla'].'" required>';
        }

?>
                </div>            

                <div class="form-data">
                    <label for="estado">Estado</label>
                    <select name="estado">
                        <option value="si">Activo</option>
                        <option value="no">Inactivo</option>
                    </select>
                </div>
<?php
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        if (!empty($_POST['talla']) && !empty($_POST['estado'])){
            $talla =$_POST['talla'];
            $estado = $_POST['estado'];

            $stmt = $mysqli->prepare("UPDATE tallas 
                                       SET   
                                            talla = ?,
                                            estado = ?
                                      WHERE 
                                            id = {$id}");
            $stmt->bind_param('ss', $talla, $estado);
            $stmt->execute();
            $stmt->close();

            $registro = ["user" => $_SESSION['username'],
                         "actividad" => "Se edito la talla del codigo (".$codigo.").",
                         "nivel" => 2];

            registroActividad($registro);

            header("location: tallas.php?id={$prenda_id}&codigo={$codigo}");
            exit();
        }
    }
?>
                <button>GUARDAR</button>
            </form>
        </div>
     </div>
    </div>
<?php
    }
    else{
        header("location: inventario.php");
        exit();
    }
?>


<?php include 'includes/atras.php'; ?>
</body>
</html>