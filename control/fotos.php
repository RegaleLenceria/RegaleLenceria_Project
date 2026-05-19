<?php 
    error_reporting(0); /// Ssshhhhh!!!
    /*
    Warning: Cannot modify header information -
    headers already sent by (output started at 
    C:\xampp\htdocs\regalelenceria\control\fotos.php:139) 
    in C:\xampp\htdocs\regalelenceria\control\fotos.php on line 185

    Recuerda revisar esto Alejandro del futuro :) Please!!!
    */    
    include_once "conexion.php"; 
    require_once "funciones.php";

    $codigo = htmlspecialchars(trim($_GET['codigo'])) ?? '';

    if (isset($_GET['id']) && !empty($_GET['id'])){
        if (!is_numeric($_GET['id'])){
            ob_end_clean();
            header("Location: index.php");
            exit();
        }

        $id = $_GET['id'];
    }
    
    elseif(isset($_GET['borrar'])){
        $stmt = $mysqli->prepare("DELETE FROM fotos WHERE fotos.id = ?");
        $stmt->bind_param("i", $_GET['borrar']);
        $stmt->execute();
        $stmt->close();

        // Borrar fotos del disco
        if (borrarFoto($_GET['borrar'])){
            $registro = ["user" => $_SESSION['username'],
                         "actividad" => "Foto borrada de codigo (".$codigo.").",
                         "nivel" => 3];

            registroActividad($registro);

            echo '<script>alert("Borrado con exito!!!");</script>';
        }

        ob_end_clean();
        header("Location: fotos.php?id=".$_GET['prenda-id']."&codigo=".$codigo);
        exit();
    }
    else{
        ob_end_clean();
        header('location: index.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control Panel | Fotos</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="style/style.css">
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const id_prenda = document.querySelectorAll('.btn-eliminar-fotos');

            Array.from(id_prenda).forEach(btn => {
                btn.addEventListener('click', function(){
                    const id = this.getAttribute('data-id').split("-")[0];
                    const id_prenda = this.getAttribute('prenda-id');
                    const codigo_prenda = this.getAttribute('data-id').split("-")[1];

                    if (confirm("¿Deseas borrar la foto?")){
                        window.location.href = `fotos.php?borrar=${id}&prenda-id=${id_prenda}&codigo=${codigo_prenda}`;
                    }
                });
            });
        });
    </script>
    <!-- Modal preview -->
    <style>
           .modal {
            display: none;
            position: fixed;
            z-index: 1;
            padding-top: 60px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.9);
        }

        .modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
            border-radius: 5px;
        }

        .modal-content, #caption {
            animation-name: zoom;
            animation-duration: 0.6s;
        }

        @keyframes zoom {
            from {transform: scale(0)}
            to {transform: scale(1)}
        }

        .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
        }

        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <?php include "includes/menu.php"; ?>
    <header>
        <h1>Editar fotos</h1>
    </header>

    <!-- El Modal -->
    <div id="myModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="img01">
        <div id="caption"></div>
    </div>

    <div class="container">
            <div class="box-imagenes">
                <h2>Codigo: <?= $codigo ?></h2>
                <!-- Preview imagenes -->
                <div class="preview"></div>

                <div class="container-input">
                    <form action="" method="post" enctype="multipart/form-data">
                        <!-- <input type="hidden" name="id"> -->
                        <input type="file" name="images[]" multiple accept="image/*" class="file-input">
                        
                        </br><label for="colores">Codigo color:</label>
                        <select name="colores">
<?php
    $stmt = $mysqli->query("SELECT * FROM colores WHERE id_prenda = {$id}");
    while($row = $stmt->fetch_assoc()){
        echo "<option value='{$row['codigo_color']}'>{$row['codigo_color']} - {$row['descripcion']}</option>";
    }
    $stmt->close();
?>
                        </select>
                        
                        <label for="estado">Estado</label>
                        <select name="estado">
                            <option value="activo">Activado</option>
                            <option value="desactivado">Desactivado</option>
                        </select>

                        </br><button>SUBIR</button>
                    </form>
<?php 
    if ($_SERVER['REQUEST_METHOD'] == "POST"){
        $color = $_POST['colores'];
        $estado = $_POST['estado'];

        $uploadDir = "upload/".$codigo."/";
        if (!is_dir($uploadDir)){
            mkdir($uploadDir, 0777, true);
        }

        foreach ($_FILES['images']['tmp_name'] as $key=>$tmpName){
            $filename = basename($_FILES['images']['name'][$key]);
            $targetFilePath = $uploadDir.$filename;
            
            if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK){
                if (move_uploaded_file($tmpName, $targetFilePath)){
                    // echo "<p> Imagenes <b>".$filename."</b> subidas con exito</p>";
                    if (isset($id) && isset($color) && isset($estado)){
                        $stmt = $mysqli->prepare("INSERT INTO 
                                                  fotos (id_prenda, color, ruta, estado)
                                                  VALUES
                                                  (?, ?, ? ,?)
                                                ");
                        $stmt->bind_param("isss", $id, $color, $targetFilePath, $estado);
                        $stmt->execute();
                        $stmt->close();

                        //Registro de foto de edicion
                        $registro = ["user" => $_SESSION['username'],
                                     "actividad" => "Subida de foto - codigo (".$codigo.") exitoso.",
                                     "nivel" => 2];

                        registroActividad($registro);

                    }
                }
                else{
                    echo "<b>Error al subir las imagenes</b>";
                }
            }
        }

        ob_end_clean();
        header("location: fotos.php?id={$id}&codigo={$codigo}");
        exit();
    }
?>
                </div>

                <div class="table-imagenes">
                    <table>
                        <tr>
                            <th>IMAGEN</th>
                            <th>COLOR</th>
                            <th>ESTADO</th>
                            <th>OPCIONES</th>
                        </tr>

<?php
    $stmt = $mysqli->query("SELECT * FROM fotos WHERE id_prenda = {$id}");
    while($row = $stmt->fetch_assoc()){
?>
    <tr>
        <td>
            <div class="view-img">
                <img onclick="openModal(this)" src="<?php echo $row['ruta']?>" alt="Preview">
            </div>
        </td>

        <td><?php echo $row['color']; ?></td>
        <td>
        <?php
            if ($row['estado'] !== "activo"){
                echo '<i class="fa-regular fa-eye-slash"></i> Desactivado';
            }else{
                echo '<i class="fa-solid fa-eye"></i> Activado';
            }
        ?>
        </td>
        <td>
            <ul>
                <li><a href="foto_editar.php?id=<?= $row['id']?>&codigo=<?= $codigo ?>&id_prenda=<?= $id ?>"><i class="fa-solid fa-pen-to-square"></i></a></li>
                <?php
                    if ($_SESSION['privilegio'] != 1){
                ?>
                <button class="btn-eliminar-fotos" prenda-id="<?= $id ?>" data-id="<?= $row['id'] ."-".$codigo ?>"><i style="color:red;" class="fa-solid fa-trash-can"></i></button>
                <?php
                    }
                ?>
            </ul>
        </td>
    </tr>
<?php
    }
    $mysqli->close();
?>
                    </table>
                </div>
                <!-- Preview de las imagenes -->
                <script>
                    function openModal(element) {
                        var modal = document.getElementById("myModal");
                        var modalImg = document.getElementById("img01");
                        var captionText = document.getElementById("caption");
                        modal.style.display = "block";
                        modalImg.src = element.src;
                        captionText.innerHTML = element.alt;
                    }

                    function closeModal() {
                        var modal = document.getElementById("myModal");
                        modal.style.display = "none";
                    }
                </script>
                <!-- Para el manejo del preview de las imagenes subidas -->
                <script src="js/main.js"></script>
        </div>
    </div>
    <?php  include 'includes/atras.php'; ?>
</body>
</html>
