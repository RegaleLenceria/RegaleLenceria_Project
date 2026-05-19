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
    if (isset($_GET['id']) && isset($_GET['codigo']) && isset($_GET['id_prenda'])){
        $id = $_GET['id'];
        $id_prenda = $_GET['id_prenda'];
        $codigo = htmlspecialchars($_GET['codigo']);
?>
    <header>
        <h1>Editar fotos</h1>
        <h4>Codigo: <?php echo $codigo; ?></h4>
    </header> 
    
    <div class="container">
  <div class="container-editar">
        <div class="box-editar">
            <form action="" method="post">   
            <div class="form-data">

                <label for="color">Selecciona el color:</label>
                <select name="colores">
<?php 
    $colores = $mysqli->query("SELECT * FROM colores WHERE id_prenda = {$id_prenda}");
    while ($row = $colores->fetch_assoc()):
?>
                    <option value="<?= $row['codigo_color'] ?>"><?= $row['codigo_color']?> - <?= $row['descripcion'] ?></option>
<?php endwhile; ?>
                </select>

<?php
        $datos = $mysqli->query("SELECT * FROM fotos WHERE id = {$id}");
        while ($row = $datos->fetch_assoc()):
?>

        <div class="form-data">
            <label for="estado">Estado</label>
            <select name="estado">
                <option value="activo">Activo</option>
                <option value="inactivo">Inactivo</option>
            </select>
        </div>

        <div class="form-data"> 
            <label for="posicion">Posicion de la imagen</label>
            <select name="posicion">
                <option value=""></option>
                <option value="principal">Principal</option>
            </select>

            <label for="vista">Vista de imagen en tienda</label>
            <select name="vista">
                <option value="frontal">Imagen Frontal</option>
                <option value="trasero">Imagen Trasera</option>
            </select>
        </div>

<?php
    endwhile;
?>
                </div>            
                <button>ACTUALIZAR</button>
            </form>
        </div>
<?php
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $color = $_POST['colores'];
        $estado = $_POST['estado'];
        $posicion = $_POST['posicion'];
        $vista = $_POST['vista'];

        $stmt = $mysqli->prepare("UPDATE fotos 
                                       SET   
                                            color = ?,
                                            estado = ?,
                                            posicion = ?,
                                            vista = ?
                                      WHERE 
                                            id = {$id}");
        $stmt->bind_param('ssss', $color, $estado, $posicion, $vista);
        $stmt->execute();
        $stmt->close();
        
        //Registro de foto de edicion
        $registro = ["user" => $_SESSION['username'],
                     "actividad" => "Foto editada - codigo (".$codigo.")",
                     "nivel" => 2];

        ob_end_clean();
        header("location: fotos.php?id={$_GET['id_prenda']}&codigo={$codigo}");
        exit();
    }
?>
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