<?php 
    include_once "conexion.php"; 
    include_once "funciones.php";
    
    $img_estampado = 0;

    $codigo = htmlspecialchars($_GET['codigo']) ?? null;

    if (isset($_GET['id'])){
        $id = $_GET['id'];
    }

    elseif(isset($_GET['borrar'])){
        $stmt = $mysqli->prepare("DELETE FROM colores WHERE colores.id=?");
        $stmt->bind_param("i", $_GET['borrar']);
        $stmt->execute();
        $stmt->close(); 

        $registro = ["user" => $_SESSION['username'],
                    "actividad" => "Se borro color del codigo (".$codigo.").",
                    "nivel" => 3];

        registroActividad($registro);

        ob_end_clean();
        header('location: colores.php?id='.$_GET['prenda-id'].'&codigo='.$codigo);
        exit();
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
    <title>Editar Colores</title>
    <link rel="stylesheet" href="style/style.css">
     <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const btnEliminarColor = document.querySelectorAll(".btn-eliminar-color");
            
            Array.from(btnEliminarColor).forEach(btn => {
                btn.addEventListener('click', function(){
                    const id = this.getAttribute("data-id");
                    const prenda_id = this.getAttribute("prenda-id");

                    if (confirm('¿Deseas borrar el color?')){
                        window.location.href = `colores.php?borrar=${id}&prenda-id=${prenda_id}`;
                    }
                })
            });
        });
    </script>
    <style>
        .tab{
            overflow: hidden;
            border:1px solid #ccc;
            background:rgb(150, 81, 84);
            margin:10px;
        }

        .tab button{
            background-color:inherit;
            color:white;
            float:left;
            border:none;
            outline:none;
            cursor:pointer;
            padding:14px 16px;
            transition:0.3s;
            font-size:17px;
        }

        .tabcontent{
            display:none;
            padding:6px 12px;
            border:1px solid #ccc;
            border-top:none;
            align-items:center;
            align-content:center;
            justify-content:center;
            margin:10px;
            width: 100%;
        }

        .tabcontent div{
            width:90%;
        }

        .tabcontent div input[type=text], input[type=file]{
            width:100%;
            padding:10px;
            margin:5px;
            display:inline-block;
        }

        .tabcontent div button{
            margin:10px;
            width: 100%;
            padding:10px;
            background:#000;
            border:none;
            border-radius:5px;
            cursor:pointer;
            color:white;
            font-weight:bold;
        }

        .table-colors .color-box{
            padding:8px;
            border:solid 1px rgb(215, 215, 215);
            border-radius:5px;
            color:#808080;
            text-align:center;
        }

        .table-colors td ul li{
            display: inline-block;
            margin:5px;
        }

        .table-colors td ul li a{
            font-size:20px;
            color:black;
        }

        .tabContainer{
            display:flex;
            flex-direction: column;
            justify-content:center;
            align-items: center;
            align-content: center;
        }

        .estampado{
            width:100%;   
        }

        .imgContainer{
            width: 100%;
        }

        #drop-zone {
            width: 300px;
            height: 200px;
            border: 2px dashed #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            text-align: center;
        }
        #image {
            max-width: 100%;
            display: none;
        }

    </style>
</head>
<body>
    <?php include "includes/menu.php"; ?>
    
    <header>
        <h1>Editar Color</h1>
        <h4>Codigo: <?php echo $codigo; ?></h4>
    </header>

    <div class="container">
        <div class="tab">
            <button class="tablinks" onclick="openColor(event, 'entero')"><i class="fa-solid fa-plus"></i> CREAR COLOR ENTERO</button>
            <button class="tablinks" onclick="openColor(event, 'estampado')"><i class="fa-solid fa-plus"></i> CREAR COLOR ESTAMPADO</button>
            <button class="tbalinks" onclick="openColor(event, 'colorImagen')"><i class="fa-solid fa-eye-dropper"></i> OBTENER COLOR DE IMAGEN</button>
        </div>

        <!-- Tab content -->

        <div id="colorImagen" class="tabcontent">
            <div class="tabContainer">
                <div class="imgContainer">
                    <div id="drop-zone">Arrastra y suelta una imagen aquí</div>
                    <canvas id="canvas"></canvas>
                </div>

                <div class="textContainer">
                    <input type="text" id="colorValue" readonly>
                </div>

                <script>
                    const dropZone = document.getElementById('drop-zone');
                    const canvas = document.getElementById('canvas');
                    const ctx = canvas.getContext('2d');
                    const colorInput = document.getElementById('colorValue');
                    let img = new Image();
                    
                    dropZone.addEventListener('dragover', (e) => {
                        e.preventDefault();
                        dropZone.style.borderColor = "#000";
                    });

                    dropZone.addEventListener('dragleave', () => {
                        dropZone.style.borderColor = "#ccc";
                    });

                    dropZone.addEventListener('drop', (e) => {
                        e.preventDefault();
                        dropZone.style.borderColor = "#ccc";
                        
                        const file = e.dataTransfer.files[0];
                        if (file && file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = (event) => {
                                img.src = event.target.result;
                            };
                            reader.readAsDataURL(file);
                        }
                    });

                    img.onload = () => {
                        canvas.width = img.width / 2;
                        canvas.height = img.height / 2;
                        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                        dropZone.style.display = 'none';
                        canvas.style.display = 'block';
                    };

                    canvas.addEventListener('click', (e) => {
                        const rect = canvas.getBoundingClientRect();
                        const x = e.clientX - rect.left;
                        const y = e.clientY - rect.top;
                        const pixel = ctx.getImageData(x, y, 1, 1).data;
                        const hexColor = `#${pixel[0].toString(16).padStart(2, '0')}${pixel[1].toString(16).padStart(2, '0')}${pixel[2].toString(16).padStart(2, '0')}`;
                        colorInput.value = hexColor;
                        colorInput.style.backgroundColor = hexColor;
                    });
                </script>
            </div>
        </div>

        <div id="entero" class="tabcontent" style="width:30%;">
            <div>
                <form action="" method="post">
                    <input type="hidden" name="tipo_color" value="entero">
                    <label for="codigoColor"><b>Codigo de color</b></label>
                    <input type="text" name="codigoColor" oninput="this.value = this.value.replace(/[,|-]/g, '')" required>

                    <label for="colorHex"><b>Color en Hexadecimal</b></label>
                    <input type="text" name="colorHex" placeholder="#000000" required>

                    <label for="descripcion"><b>Descripcion</b></label>
                    <input type="text" name="descripcion">

                    <label for="estado"><b>Estado</b></label>
                    <select name="estado" id="">
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>

                    <button>REGISTRAR</button>
                </form>

<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $tipo_color = $_POST['tipo_color'];
        $codigo_color = $_POST['codigoColor'];
        $color_hex = $_POST['colorHex'];
        $descripcion = $_POST['descripcion'];
        if ($img_estampado != 0){ $img_estampado = $_POST['img_estampado']; }
        $estado = $_POST['estado'];

        $stmt = $mysqli->prepare("INSERT INTO 
                                  colores (id_prenda, tipo_color, codigo_color, color_hex, descripcion, img_estampado, estado)
                                  VALUES (?, ?, ?, ?, ?, ?, ?) 
                                ");
    
        $stmt->bind_param("issssss", $id, $tipo_color, $codigo_color, $color_hex, $descripcion,
                                         $img_estampado, $estado);

        $stmt->execute();
        $stmt->close();
        
        $registro = ["user" => $_SESSION['username'],
                     "actividad" => "Color \"".$codigo_color."\" añadido al codigo (".$codigo.").",
                     "nivel" => 2];
        registroActividad($registro);

        ob_end_clean();
        header("location: colores.php?id={$id}&codigo={$codigo}");
        exit();
    }
?>
            </div>
        </div>
<!-- TAB ESTAMPADO FIXED -->
        <div id="estampado" class="tabcontent">
            <div class="estampado-container">
                <h1>Recortar Imagen</h1>
                
                <div class="upload-section">
                    <input type="file" id="imageInput" accept="image/*">
                    <button id="uploadBtn">Subir Imagen</button>
                </div>
                
                <div class="editor-section">
                    <div class="image-container">
                        <img id="originalImage" style="display: none;">
                        <canvas id="imageCanvas"></canvas>
                    </div>
                    
                    <div class="preview-section">
                        <canvas id="previewCanvas"></canvas>
                        <input type="hidden" class="id_prenda" name="id_prenda" value="<?= $id ?>">
                        <input type="text" class="codigo_color" name="codigo_color" placeholder="Codigo">
                        <input type="text" class="nombre_estampado" name="nombre_estampado" placeholder="Nombre color">
                        <select class="estado" name="estado">
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                        <button id="saveCropBtn" disabled><i class="fa-solid fa-floppy-disk"></i>Guardar estampado</button>
                    </div>
                </div>
                
                <div class="controls">
                    <button id="cropBtn" disabled><i class="fa-solid fa-scissors"></i> Recortar</button>
                    <button id="cancelBtn" disabled>Cancelar</button>
                </div>
                
                <div id="resultMessage"></div>
            </div>
        </div>
<!-- END ESTAMPADO -->
        <script>
            function openColor(evt, tabName){
                var i, tabcontent, tablinks;
                tabcontent = document.querySelectorAll('.tabcontent');
                for (i=0; i < tabcontent.length; i++){
                    tabcontent[i].style.display = "none";
                }

                tablinks = document.querySelectorAll('.tablinks');
                for (i = 0; i < tablinks.length; i++){
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                }
                document.querySelector("#"+tabName).style.display = "flex";
                evt.currentTarget.className = " active";
            }
        </script>

        <div class="table-colors">
                <table>
                    <tr>
                        <th>VISTA COLOR</th>
                        <th>CODIGO COLOR</th>
                        <th>TIPO</th>
                        <th>DESCRIPCION</th>    
                        <th>ESTADO</th>
                        <th>OPCIONES</th>
                    </tr>

<?php
    $colores = $mysqli->query("SELECT * FROM colores WHERE id_prenda={$id}");
    while($row = $colores->fetch_assoc()){
?>
                    <tr>
                        <td>
                            <div style="height:20px;background:<?php 
                                if (!empty($row['color_hex'])){
                                    echo $row['color_hex'];
                                }else{
                                    echo "url(".$row['img_estampado'].")";
                                }
                            ?>;" class="color-box"><?php echo $row['color_hex'];?></div>
                        </td>
                        
                        <td><?php echo $row['codigo_color']; ?></td>
                        <td><?php echo $row['tipo_color']; ?></td>
                        <td><?php echo $row['descripcion']; ?></td>
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
                                <li><a title="Editar" href="color_editar.php?id=<?= $row['id']?>&codigo=<?= $codigo ?>"><i class="fa-solid fa-pen-to-square"></i></a></li>
                                <button class="btn-eliminar-color" prenda-id="<?= $id ?>" data-id="<?= $row['id'] ?>"><i style="color:red;" class="fa-solid fa-trash-can"></i></button>
                            </ul>
                        </td>
                    </tr>
<?php
    }
    $colores->close();
?>
                </table>
            </div>
        <script src="js/imgcut.js"></script>
    </div>

    <?php  include 'includes/atras.php'; ?>
</body>
</html>