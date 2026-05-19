<?php
    include_once "conexion.php";
	include_once "funciones.php";

    if (!isset($_SESSION['logged']) || $_SESSION['logged'] != true){
        ob_end_clean();
        header("location: index.php");
        exit;
    }
    
    elseif($_SERVER['REQUEST_METHOD'] === "GET"){
        if (isset($_GET['borrar'])){
            borrarPrenda($_GET['borrar']);

            ob_end_clean();
            header("Location: inventario.php");
            exit();
        }
    }

    // Configuración de paginación
    $registros_por_pagina = 20; // Puedes ajustar este valor
    
    // Obtener la página actual
    $pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    if ($pagina_actual < 1) $pagina_actual = 1;
    
    // Calcular el offset
    $offset = ($pagina_actual - 1) * $registros_por_pagina;
    
    // Obtener el total de registros
    $total_registros = $mysqli->query("SELECT COUNT(*) as total FROM inventario")->fetch_assoc()['total'];
    
    // Calcular el total de páginas
    $total_paginas = ceil($total_registros / $registros_por_pagina);
    
    // Asegurar que la página actual no exceda el total de páginas
    if ($pagina_actual > $total_paginas && $total_paginas > 0) {
        $pagina_actual = $total_paginas;
        $offset = ($pagina_actual - 1) * $registros_por_pagina;
    }
    
    // Consulta con paginación
    $datos = $mysqli->query("SELECT * FROM inventario ORDER BY id DESC LIMIT $offset, $registros_por_pagina");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../src/imgs/favicon-32x32.png" type="image/png">
    <link rel="stylesheet" href="style/style.css">
    <!-- Panel Style -->
    <link rel="stylesheet" href="style/panel.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="js/app.js"></script>
    <title>Panel Control - Inventario</title>

    <!-- Stylo para el efecto de carga -->
    <style>
    
    .no-foto{
        background-color: #fe784fff;
        padding: 5px;
        border-radius: 5px;
        color: white;
    }

    .menu-lista{
        background: #965154;
        padding: 10px;
        border-radius: 5px;
        margin-left: 10px;
    }

    .menu-lista a{
        color: white;
        text-decoration: none;
        font-weight: bold;
    }

    header{
        background-image: url(src/img/inventario_banner.png);
        background-size: cover;
        background-position:center;
        border-image: fill 0 linear-gradient(#0003, #000000b1);
        border-radius:9px;

        color:white;
        padding: 20px;
    }

    .container-cargando{
        width: 100%;
        height: 100%;
        position: fixed;
        z-index: 1000;
        display: flex;
        justify-content: center;
    }

    .cargando{
        width:48px;
        height:48px;
        border:3px solid #FFF;
        border-radius:50%;
        display:inline-block;
        position:relative;
        box-sizing:border-box;
        animation:girar 1s linear infinite;
     }

     .cargando::after{
        content:'';
        box-sizing:border-box;
        position:absolute;
        left:50%;
        top:50%;
        transform:translate(-50%, -50%);
        width:56px;
        height:56px;
        border-radius:50%;
        border:3px solid transparent;
        border-bottom-color:#FF3D00;
     }

     @keyframes girar{
        0%{
            transform:rotate(0deg);
        }
        100%{
            transform:rotate(360deg);
        }
     }

     /* Estilos para la paginación */
     .paginacion {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 20px 0;
        gap: 10px;
        flex-wrap: wrap;
     }

     .paginacion a, .paginacion span {
        padding: 8px 16px;
        text-decoration: none;
        border: 1px solid #ddd;
        border-radius: 5px;
        color: #333;
        transition: all 0.3s ease;
     }

     .paginacion a:hover {
        background-color: #965154;
        color: white;
        border-color: #965154;
     }

     .paginacion .activa {
        background-color: #965154;
        color: white;
        border-color: #965154;
     }

     .paginacion .deshabilitada {
        color: #ccc;
        cursor: not-allowed;
        border-color: #eee;
     }

     .info-paginacion {
        text-align: center;
        margin: 10px 0;
        color: #666;
        font-size: 14px;
     }

     .btn-stock input{
        width:20%;
        padding: 0.5em;
        font-size: 15px;
     }

     .btn-stock button{
        padding: 0.5em;
        border: none;
        border-radius:5px;
        background-color: #965154;
        color: white;
     }
    </style>
</head>
<body>
    <?php include "includes/nav.php"; ?>
    <header>
        <h1>Inventario</h1>
    </header>
    <!-- Animacion de carga -->
    <div class="container-cargando">
        <span class="cargando"></span>
    </div>

    <!-- Modal registro prenda -->
    <div class="modal">
        <div class="box-modal">
        <!-- Boton salida -->
            <span class="close"><i class="fa-solid fa-circle-xmark"></i></span>

            <form action="" method="post">
                <label for="nombre">Nombre prenda</label>
                <input name="nombre" type="text" required>

                <label for="codigo">Codigo</label>
                <input name="codigo" type="text" required>

                <label for="genero">Genero</label>
                <select name="genero">
                    <option value="mujer">Mujer</option>
                    <option value="hombre">Hombre</option>
                    <option value="ninos">Niños</option>
                </select>
                
                <label for="marca">Marca de Prenda</label>
                <select name="marca" id="marca">
                    <option value="leonisa">Leonisa</option>
                    <option value="ellipse">Ellipse</option>
                    <option value="touche">Touche</option>
                    <option value="fajate">Fajate</option>
                    <option value="annchery">Ann Chery</option>
                </select>

                <label for="categoria">Categoria</label>
                <select name="categoria" id="">
                    <option value="ropainterior">Ropa Interior</option>
                    <option value="fajas">Fajas</option>
                    <option value="bikini">Trajes de Baño</option>
                    <option value="pijamas">Pijamas</option>
                    <option value="accesorios">Accesorios</option>
                    <option value="deportiva">Ropa Deportiva</option>
                </select>
 
                <label for="subcategoria">SubCategoria</label>
                <select name="subcategoria">
                    <option value="none">Selecciona una categoria</option>
                    <option value="brasieres">Brasieres</option>
                    <option value="panties">Panties</option>
                    <option value="tanga">Tanga</option>
                    <option value="pijamas">Pijamas </option>
                    <option value="bikini">Bikini</option>
                    <option value="vestido">Vestido</option>
                    <option value="malla">Malla</option>
                    <option value="balneario">Balneario</option>
                    <option value="CorsetBody">Corset/Body</option>
                    <option value="top">Top</option>
                    <option value="leggins">Leggins</option>
                    <option value="short">Short</option>
                    <option value="camisetas">Camisetas</option>
                    <option value="boxer">Boxer</option>
                    <option value="leggin">Leggin</option>
                    <option value="kimono">Kimono</option>
                    <option value="polo">Polo</option>
                </select>

                <label for="descripcion_corta">Descripcion Corta</label>
                <textarea type="text" name="descripcion_corta"></textarea>
                
                <label for="descripcion_larga">Descripcion Larga</label>
                <textarea name="descripcion_larga" style="height:200px;"></textarea>

                <h3>Costo, Moneda y Ofertas</h3>    

                <label for="precio_base">Precio (Boliviano)</label>
                <input type="text" name="precio_base" placeholder="0.00" required>

                <label for="precio_dolar">Ver precio de dolares</label>
                <select name="precio_dolar">
                    <option value="si">Si</option>
                    <option value="no">No</option>
                </select>

                <label for="estado">Habilitar codigo</label>
                <select name="estado">
                    <option value="si">Si</option>
                    <option value="no">No</option>
                </select>

                <button>REGISTRAR</button>
            </form>
        </div>
<?php
        if ($_SERVER['REQUEST_METHOD'] == "POST"){  
            $nombre = $_POST['nombre'];
            $codigo = $_POST['codigo'];
            $genero = $_POST['genero'];
            $marca = $_POST['marca'];
            $descripcion_corta = $_POST['descripcion_corta'];
            $descripcion_larga = $_POST['descripcion_larga'];
            $precio_base = $_POST['precio_base'];
            $estado =$_POST['estado'];
            
            if (verificarRepetidos($codigo)){
                echo "<script>alert('El codigo $codigo ya existe, Verifica el codigo y vuelve a registrarlo')</script>";
            }else{
                $sql = "INSERT INTO 
                        inventario (nombre_prenda, 
                                    codigo,
                                    genero, 
                                    marca_prenda, 
                                    descripcion_corta,
                                    descripcion_larga, 
                                    precio_base, 
                                    estado)
                        VALUES
                        (?, ?, ?, ?, ?, ?, ?, ?)";

                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("ssssssds", $nombre, $codigo, $genero, $marca, $descripcion_corta,
                                $descripcion_larga, $precio_base, $estado);
                $stmt->execute();
                $stmt->close();

                // Registro creacion codigo
                $registro = ["user" => $_SESSION['username'],
                             "actividad" => "Creacion de codigo (".$codigo.") exitoso.",
                             "nivel" => 2];

                registroActividad($registro);
                
                echo "<script>alert('Registrado con exito!!');</script>";
            }
    }
?>

    </div>
    <div class="container">
        <div class="menu">
            <div class="btn-add">
                <button class="btnModal"><i class="fa-solid fa-plus"></i>CREAR CODIGO</button>
                <script>
                        var modal = document.querySelector('.modal');
                        var btn = document.querySelector('.btnModal');
                        var span = document.getElementsByClassName("close")[0];

                        btn.onclick = function() {
                            modal.style.display = "block";
                        }

                        span.onclick = function() {
                            modal.style.display = "none";
                        }

                        window.onclick = function(event) {
                            if (event.target == modal) {
                                modal.style.display = "none";
                            }
                        }
                </script>
            </div>

            <div class="buscador">
                    <input id="busqueda" name="buscador" type="search" placeholder="Ingresa nombre, marca o codigo para la busqueda">
                    <button><i class="fa-solid fa-magnifying-glass"></i></button>
                    <!-- Script para la busqueda de prenda -->
                    <script>
                        document.getElementById('busqueda').addEventListener('keyup', function() {
                            let consulta = this.value;
                            if (consulta.length > 2) {
                                fetch(`buscar.php?q=${consulta}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        let html = data.map(item => `
                                            <tr>
                                                <td><b>${item.codigo}</b></td>
                                                <td>${item.nombre_prenda}</td>
                                                <td>${item.stock}</td>
                                                <td>${item.estado}</td>
                                                <td>
                                                    <ul>
                                                        <li><a title="Editar" href="editar.php?id=${item.id}&codigo=${item.codigo}"><i class="fa-solid fa-pen-to-square"></i></a></li>
                                                        <li><a title="Fotos" href="fotos.php?id=${item.id}&codigo=${item.codigo}"><i class="fa-solid fa-image"></i></a></li>
                                                        <li><a title="Colores" href="colores.php?id=${item.id}&codigo=${item.codigo}"><i class="fa-solid fa-palette"></i></a></li>
                                                        <li><a title="Tallas" href="tallas.php?id=${item.id}&codigo=${item.codigo}"><i class="fa-solid fa-ruler-combined"></i></a></li>
                                                        <li><a title="Stock" href="stock.php?id=${item.id}&codigo=${item.codigo}"><i class="fa-solid fa-boxes-stacked"></i></a></li>
                                                   </ul>
                                                </td>
                                            </tr>
                                        `).join('');
                                        document.querySelector('#tabla-resultados tbody').innerHTML = html;
                                        // Ocultar la paginación durante la búsqueda
                                        document.querySelector('.paginacion').style.display = 'none';
                                        document.querySelector('.info-paginacion').style.display = 'none';
                                    })
                                    .catch(error => console.error('Error:', error));
                            } else {
                                document.querySelector('#tabla-resultados tbody').innerHTML = '';
                                // Mostrar la paginación cuando no hay búsqueda
                                document.querySelector('.paginacion').style.display = 'flex';
                                document.querySelector('.info-paginacion').style.display = 'block';
                            }
                        });
                    </script>
            </div>
        </div>
        <div class="menu_2">
            <div class="total">
                <h3><?php echo totalPrendas(); ?></h3>
                <h4>TOTAL PRENDAS</h4>
            </div>

            <div class="total" style="background-color: rgba(248, 79, 79, 1);">
                <h3><?php echo prendasCeroStock(); ?></h3>
                <h4>PRENDAS CON 0 STOCK</h4>
            </div>
<!--
            <div class="btn-stock">
                <h4>BUSCAR SEGUN CANTIDAD DE STOCK</h4>
                <input type="number" max="100" min="0" value="0" class="search_stock">
                <button onclick="buscarStock()">BUSCAR</button>

                <script>
                    function buscarStock() {
                        let consulta = document.querySelector('.search_stock').value;
                        
                            if (consulta.length > 0) {
                                fetch(`obtenerStock.php?stock=${consulta}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        let html = data.map(item => `
                                            <tr>
                                                <td><b>${item.codigo}</b></td>
                                                <td>${item.nombre_prenda}</td>
                                                <td>${item.stock}</td>
                                                <td>${item.estado}</td>
                                                <td>
                                                    <ul>
                                                        <li><a title="Editar" href="editar.php?id=${item.id}&codigo=${item.codigo}"><i class="fa-solid fa-pen-to-square"></i></a></li>
                                                        <li><a title="Fotos" href="fotos.php?id=${item.id}&codigo=${item.codigo}"><i class="fa-solid fa-image"></i></a></li>
                                                        <li><a title="Colores" href="colores.php?id=${item.id}&codigo=${item.codigo}"><i class="fa-solid fa-palette"></i></a></li>
                                                        <li><a title="Tallas" href="tallas.php?id=${item.id}&codigo=${item.codigo}"><i class="fa-solid fa-ruler-combined"></i></a></li>
                                                        <li><a title="Stock" href="stock.php?id=${item.id}&codigo=${item.codigo}"><i class="fa-solid fa-boxes-stacked"></i></a></li>
                                                   </ul>
                                                </td>
                                            </tr>
                                        `).join('');
                                        document.querySelector('#tabla-resultados tbody').innerHTML = html;
                                        // Ocultar la paginación durante la búsqueda
                                        document.querySelector('.paginacion').style.display = 'none';
                                        document.querySelector('.info-paginacion').style.display = 'none';
                                    })
                                    .catch(error => console.error('Error:', error));
                            } else {
                                document.querySelector('#tabla-resultados tbody').innerHTML = '';
                                // Mostrar la paginación cuando no hay búsqueda
                                document.querySelector('.paginacion').style.display = 'flex';
                                document.querySelector('.info-paginacion').style.display = 'block';
                            }
                        }
                </script>
            </div>
-->
            <div class="menu-lista">
                <a href="excelsalida.php"><i class="fa-solid fa-file-excel"></i> DESCARGAR EXCEL</a>
            </div>
        </div>
        
        <!-- Información de paginación -->
        <div class="info-paginacion">
            Mostrando <?php echo ($total_registros > 0 ? $offset + 1 : 0); ?> - 
            <?php echo min($offset + $registros_por_pagina, $total_registros); ?> 
            de <?php echo $total_registros; ?> registros
        </div>

        <div class="lista-inventario">
            <table id="tabla-resultados">
                <thead>
                    <tr>
                        <th>CODIGO</th>
                        <th>NOMBRE</th>
                        <th>STOCK</th>
                        <th>ESTADO</th>
                        <th>OPCIONES</th>
                    </tr>
                </thead>
                <tbody class="tbody-resultados">
<?php
    if ($datos->num_rows > 0) {
        while($raw = $datos->fetch_assoc()){
            $id_prenda = $raw['id'];
?>
    <tr>
        <td><b><?php echo $raw['codigo']; ?></b></td>
        <td><?php echo $raw['nombre_prenda']; ?></td>
        <td>
<?php
    if (obteniendoStock($raw['id']) < 2){
        echo '<span style="color:white;background:#e75b5b;padding:5px;border-radius:5px;">'.obteniendoStock($raw['id']).'</span>'; 
    }
    elseif (obteniendoStock($raw['id']) <= 5){
        echo '<span style="color:white;background:orange;padding:5px;border-radius:5px;">'.obteniendoStock($raw['id']).'</span>'; 
    }
    else{
        echo '<span style="color:white;background:#3ac062;padding:5px;border-radius:5px;">'.obteniendoStock($raw['id']).'</span>'; 
    }
?>
        </td>
        <td>
<?php
    if($raw['estado'] != "si"){
        echo '<i title="Desactivado" style="color:#818181;" class="fa-solid fa-eye-slash"></i> Desactivado';
    }else{
        echo '<i title="Activado" class="fa-solid fa-eye"></i> Activado';
    }
?>
        </td>
        
        <td>
            <ul>
                <li><a title="Editar" href="editar.php?id=<?= $raw['id'] ?>&codigo=<?= $raw['codigo'] ?>"><i class="fa-solid fa-pen-to-square"></i></a></li>
                <li><a class="<?php if (verificarFotos($raw['id']) == 0){echo 'no-foto';}?>" title="Fotos" href="fotos.php?id=<?= $raw['id']."&codigo=".$raw['codigo'] ?>"><i class="fa-solid fa-image"></i></a></li>
                <li><a title="Colores" href="colores.php?id=<?= $raw['id']."&codigo=".$raw['codigo'] ?>"><i class="fa-solid fa-palette"></i></a></li>
                <li><a title="Tallas" href="tallas.php?id=<?= $raw['id']."&codigo=".$raw['codigo'] ?>"><i class="fa-solid fa-ruler-combined"></i></a></li>
                <li><a title="Stock" href="stock.php?id=<?= $raw['id']."&codigo=".$raw['codigo'] ?>"><i class="fa-solid fa-boxes-stacked"></i></a></li>
                <?php
                if ($_SESSION['privilegio'] != 1) 
                ?>
                <button class="btn-eliminar" data-id="<?= $raw['id'] ?>"><i style="color:red;" class="fa-solid fa-trash-can"></i></button>
            </ul>
        </td>
    </tr>
<?php
        }
    } else {
        echo '<tr><td colspan="5" style="text-align: center;">No se encontraron registros</td></tr>';
    }
?>
        </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <?php if ($total_paginas > 1): ?>
        <div class="paginacion">
            <!-- Primera página -->
            <?php if ($pagina_actual > 1): ?>
                <a href="?pagina=1">&laquo; Primera</a>
            <?php else: ?>
                <span class="deshabilitada">&laquo; Primera</span>
            <?php endif; ?>

            <!-- Página anterior -->
            <?php if ($pagina_actual > 1): ?>
                <a href="?pagina=<?php echo $pagina_actual - 1; ?>">&lsaquo; Anterior</a>
            <?php else: ?>
                <span class="deshabilitada">&lsaquo; Anterior</span>
            <?php endif; ?>

            <!-- Números de página -->
            <?php
            $inicio = max(1, $pagina_actual - 2);
            $fin = min($total_paginas, $pagina_actual + 10);
            
            for ($i = $inicio; $i <= $fin; $i++):
            ?>
                <?php if ($i == $pagina_actual): ?>
                    <span class="activa"><?php echo $i; ?></span>
                <?php else: ?>
                    <a href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <!-- Página siguiente -->
            <?php if ($pagina_actual < $total_paginas): ?>
                <a href="?pagina=<?php echo $pagina_actual + 10; ?>">Siguiente &rsaquo;</a>
            <?php else: ?>
                <span class="deshabilitada">Siguiente &rsaquo;</span>
            <?php endif; ?>

            <!-- Última página -->
            <?php if ($pagina_actual < $total_paginas): ?>
                <a href="?pagina=<?php echo $total_paginas; ?>">Última &raquo;</a>
            <?php else: ?>
                <span class="deshabilitada">Última &raquo;</span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Script para imagen de carga -->
    <script>
        window.addEventListener("load", function(){
            const cargando = document.querySelector('.container-cargando');
            cargando.style.display = "none";
        });
    </script>
<?php  include 'includes/atras.php'; ?>
</body>
</html>
