<?php
    include_once "conexion.php";
    require_once "funciones.php";

    if (isset($_GET['id']) && !empty($_GET['id'])){
        $id = $_GET['id'];
        $codigo = htmlspecialchars($_GET['codigo']);
        /*
        * Recuerda usar htmlspecialchars()
        * Para prevenir XSS Attacks ;)
        */
    }
    
    elseif(isset($_GET['borrar']) 
           && isset($_GET['id_prenda'])
           && isset($_GET['codigo'])){
        
        $id_prenda = $_GET['id_prenda'];
        $codigo = htmlspecialchars($_GET['codigo']);

        if (borrarTallas($_GET['borrar'])){
            $registro = ["user" => $_SESSION['username'],
                         "actividad" => "Talla borrada de codigo (".$codigo.").",
                         "nivel" => 3];

            registroActividad($registro);
            
            header ("location: tallas.php?id={$id_prenda}&codigo={$codigo}");
        }
        else{
            echo "<script>alert('Error al borrar los datos')</script>";
        }
    }

    else{
        header('location: inventario.php');
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control | Ingresar talla</title>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const btn_borrar_talla = document.querySelectorAll('.btn-borrar-tallas');
            Array.from(btn_borrar_talla).forEach(btn => {
                btn.addEventListener('click', function(){
                    const id_prenda = this.getAttribute('data-prenda');
                    const id_talla = this.getAttribute('data-id');
                    const codigo_prenda = this.getAttribute('data-codigo');

                    if (confirm("¿Deseas borrar la talla?")){
                        window.location.href = `tallas.php?borrar=${id_talla}&id_prenda=${id_prenda}&codigo=${codigo_prenda}`;   
                    }
                });
            });
        });
    </script>
<?php include_once "includes/librerias.php"; ?>
    <link rel="stylesheet" href="style/style.css">
</head>

<body>
    <?php include "includes/menu.php"; ?>
    
    <header>
        <h1>Editar talla</h1>
        <h4>Codigo: <?php echo $_GET['codigo']; ?></h4>
    </header>

    <!-- Ventana Modal Registro de talla -->
    <div class="modal">
        <div class="box-modal">
            <form action="" method="post">
                <!-- Boton para salir -->
                <div class="salir">
                    <a href="#" onclick="closeModal()"><i class="fa-solid fa-circle-xmark"></i></a>
                </div>
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <label for="talla">Talla</label>
                <input type="text" name="talla" maxlength="6" required>

                <label for="estado">Estado</label>
                <select name="estado">
                    <option value="si">Activo</option>
                    <option value="no">Inactivo</option>
                </select>

                <button>GUARDAR</button>
            </form>
        </div>
<?php
    if ($_SERVER['REQUEST_METHOD'] == "POST"){
        $id = $_POST['id'];
        $talla = $_POST['talla'];
        $estado = $_POST['estado'];

        $datos = $mysqli->prepare("INSERT INTO 
                                   tallas (id_prenda, talla, estado) 
                                   VALUES (?, ?, ?)");

        $datos->bind_param("iss", $id, $talla, $estado);
        $datos->execute();
        $datos->close();

        $registro = ["user" => $_SESSION['username'],
                     "actividad" => "Foto borrada de codigo (".$codigo.").",
                     "nivel" => 3];

        registroActividad($registro);
        
        ob_end_clean();
        header("location: tallas.php?id={$id}&codigo={$codigo}");
        exit();
    }
?>
    </div>

    <div class="container">
        <div class="menu">
            <div class="btn-add">
                <button onclick="viewModal()"><i class="fa-solid fa-plus"></i> AGREGAR TALLA</button>
                <script>
                    function viewModal(){
                        const modal = document.querySelector('.modal');
                        modal.style.display = "flex";
                    }

                    function closeModal(){
                        const modal = document.querySelector('.modal');
                        modal.style.display = "none";
                    }
                </script>
            </div>
        </div>

        <div class="lista-inventario">
            <table>
                <tr>
                    <th>TALLA</th>
                    <th>ESTADO</th>
                    <th>OPCIONES</th>
                </tr>
<?php
    $datos = $mysqli->query("SELECT * FROM tallas WHERE id_prenda = $id");
    while($row = $datos->fetch_assoc()){
?>
    <tr>
        <td><?php echo $row['talla']; ?></td>
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
                <li><a class="btn-editar" title="Editar" href="tallas_editar.php?id=<?= $row['id'] ?>&id_prenda=<?= $row['id_prenda'] ?>&codigo=<?= $codigo ?>"><i class="fa-solid fa-pen-to-square"></i></a></li>
                <!-- <li><a title="Borrar" href="tallas.php?borrar=<?php echo $row['id']; ?>"><i style="color:red;" class="fa-solid fa-trash-can"></i></a></li> -->
                <button class="btn-borrar-tallas" data-prenda="<?= $id ?>" data-codigo="<?= $codigo ?>" data-id="<?= $row['id'] ?>"><i style="color:red;" class="fa-solid fa-trash-can"></i></button>
            </ul>
        </td>
    </tr>
<?php
    } 
?>
        </table>
        </div>
    </div>

    <script>
        const btnEditar = document.querySelectorAll('.btn-editar');
        Array.from(btnEditar).forEach(btn => {
            btn.addEventListener('click', function(){
                const id = this.getAttribute('prenda-id');
                window.location.href = `tallas.php?editar=${id}`;
            });
        });
    </script>

    <?php  include 'includes/atras.php'; ?>
</body>
</html>