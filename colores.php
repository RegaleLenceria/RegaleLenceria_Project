<?php include_once "control/conexion.php"; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colores Pickers</title>
    <style>
        *{
            margin: 0;
            padding: 0;
        }

        .container{
            width: 100vw;
            height: 100vh;

            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .box-colors{
            background-color: #ffffffd2;
            margin: 10px;
            padding: 10px;

            border:1px solid #818181;
            border-radius: 5px;
        }

        form select{
            height: 30px;
        }

        form select option{
            border: 1px solid white;
            color:#212121;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="box-colors">
            <form action="" method="post">
                <select onclick="colorSelect(this)" name="colores" class="colores">
<?php
    $stmt = $mysqli->query("SELECT DISTINCT * FROM colores");
    while($colores = $stmt->fetch_assoc()){
        if ($colores['img_estampado'] == 0){
?>
    <option style="background:<?= $colores['color_hex'] ?>;" value="<?= $colores['color_hex'] ?>"><?= $colores['descripcion'] ?> - <?= $colores['codigo_color'] ?></option>
<?php
        }
    }
?>

<!--
                    <option style="background:red;" dataColor="NONE" value="rgb(255, 0, 0)">ROJO</option>
                    <option style="background:green;" dataColor="NONE" value="rgb(0, 128, 0)">VERDE</option>
                    <option style="background:yellow;" dataColor="NONE" value="rgb(255, 255, 0)">AMARILLO</option>
                    <option style="background:white;" dataColor="NONE" value="#FFF">BLANCO</option>
-->
                </select>
                
                <script>
                    function colorSelect(valor){
                        const select_tag = document.querySelector(".colores");
                        select_tag.style.background = valor.value;
                    }                    
                </script>
                <button>OK</button>
            </form> 
        </div>
    </div>
</body>
</html>