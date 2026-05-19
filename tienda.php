<?php
    include_once "control/conexion.php";
    include_once "control/funciones.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regale Lenceria</title>
    <link rel="shortcut icon" href="src/imgs/favicon_medium.png" type="image/png">
    <link rel="stylesheet" href="src/style/style.css?v=<?= ASSETS_VERSION ?>">
    <link rel="stylesheet" href="src/style/tienda.css?v=<?= ASSETS_VERSION ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- AOS Faded Scroll Effect -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <style>
        .color-prenda{
            width: 10px;
            height: 10px;
        }
        /* Estilo para la muestra de las imagenes traseras y delanteras */
        .prenda { position: relative; }
        .prenda-img a { display: block; position: relative; }
        .prenda-img img { display: block; width: 100%; height: auto; transition: opacity .28s ease; }
        .prenda-img .img-back { position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; }
        .prenda-img a:hover .img-back { opacity: 1; }
        .prenda-img a:hover .img-front { opacity: 0; }
    </style>
</head>

<body>
    <!-- NAV -->
<?php include("nav.php"); ?>
    <section>
        <div class="container">
            <div class="filtro-prendas">
<?php
    // Obtener opciones de filtro
    $tallas = obtenerTallasDisponibles();
    $resultadoColores = obtenerColoresDisponibles();
    
    // Eliminar colores duplicados
    $coloresUnicos = [];
    while($c = $resultadoColores->fetch_assoc()){
        // Verificar si el código de color ya existe
        if(!in_array($c['codigo_color'], array_column($coloresUnicos, 'codigo_color'))){
            $coloresUnicos[] = $c;
        }
    }
    
    $rangoPrecios = obtenerRangoPrecios();
    $precioMin = $rangoPrecios['precio_min'] ?? 0;
    $precioMax = $rangoPrecios['precio_max'] ?? 1000;
?>
<!--
                <div class="filtro-grupo">
                    <label for="filtro-talla">Talla:</label>
                    <select id="filtro-talla" class="filtro-select">
                        <option value="">Todas las tallas</option>
<?php while($t = $tallas->fetch_assoc()): ?>
                        <option value="<?= $t['talla'] ?>"><?= $t['talla'] ?></option>
<?php endwhile; ?>
                    </select>
                </div>

                <div class="filtro-grupo">
                    <label for="filtro-color">Color:</label>
                    <select id="filtro-color" class="filtro-select">
                        <option value="">Todos los colores</option>
<?php foreach($coloresUnicos as $c): ?>
                        <option value="<?= $c['codigo_color'] ?>" data-color="<?= $c['color_hex'] ?>"><?= $c['codigo_color'] ?></option>
<?php endforeach; ?>
                    </select>
                </div>

                <div class="filtro-grupo filtro-precio">
                    <div class="filtro-precio-item">
                        <label for="filtro-precio-min">Precio mín:</label>
                        <input type="number" id="filtro-precio-min" class="filtro-input" value="<?= $precioMin ?>" min="<?= $precioMin ?>" max="<?= $precioMax ?>">
                    </div>
                    
                    <div class="filtro-precio-item">
                        <label for="filtro-precio-max">Precio máx:</label>
                        <input type="number" id="filtro-precio-max" class="filtro-input" value="<?= $precioMax ?>" min="<?= $precioMin ?>" max="<?= $precioMax ?>">
                    </div>
                </div> 

                <div class="filtro-grupo">
                    <button id="limpiar-filtros" class="btn-limpiar">Limpiar filtros</button>
                </div>
            </div>
-->
            <div class="box-prendas" id="box-prendas">
<?php
    $filtros = [
        "g" => $_GET["g"] ?? null,  // Genero 
        "c" => $_GET["c"] ?? null,  // Categoria
        "m" => $_GET["m"] ?? null,  // Marca
        "sub" => $_GET["sub"] ?? null, //Subcategoria
        
        "b" => $_GET["b"] ?? null, // Beneficio
        "t" => $_GET["t"] ?? null, // Tipo faja
        "s" => $_GET["s"] ?? null, // Sistema
        "com" => $_GET["co"] ?? null, // Compresion
        "bra" => $_GET["bra"] ?? null, // Brasier
        "pan" => $_GET["pan"] ?? null, // Pantie
        "bi" => $_GET["bi"] ?? null, // Bikini
        "ma" => $_GET["ma"] ?? null, // Malla
        "bal" => $_GET["bal"] ?? null, // Balneario
        "pijamacomoda" => $_GET["pijamacomoda"] ?? null, // Pijama comoda
        "pijamasexy" => $_GET["pijamasexy"] ?? null, // Pijama Sexy
        "tipolinea" => $_GET["tipolinea"] ?? null, // Tipo de Linea

        "limite" => 12,
        "offset" => 0
    ];

    $resultados = obtenerProductos($filtros);
    $contador = 0;
    while($row = $resultados->fetch_assoc()){
        if ($row['estado'] != "no"){
            $contador++;
            // Obtener tallas del producto
            $tallas_producto = obtenerTallasProducto($row['id']);
            $tallas_arr = [];
            while($t = $tallas_producto->fetch_assoc()){
                $tallas_arr[] = $t['talla'];
            }
            
            // Obtener colores del producto
            $colores_producto = obtenerColoresProducto($row['id']);
            $colores_arr = [];
            while($col = $colores_producto->fetch_assoc()){
                $colores_arr[] = $col['codigo_color'];
            }
            
            if(!empty($row['fotos'])){
                $fotos_arr = explode(",", $row['fotos']);
                $front = "control/".trim($fotos_arr[0]);
                if (isset($fotos_arr[1]) && trim($fotos_arr[1]) !== ''){
                    $back = "control/".trim($fotos_arr[1]);
                } else {
                    $back = $front;
                }
            } else {
                $front = 'src/imgs/placeholder.png';
                $back = $front;
            }
?>
    <div class="prenda" data-talla="<?= implode(',', $tallas_arr) ?>" data-color="<?= implode(',', $colores_arr) ?>" data-precio="<?= $row['precio_base'] ?>">
        <div class="prenda-img">
            <a title="<?= $row['codigo'] ?>" href="vista.php?id=<?= $row['id'] ?>">
                <img class="img-front" src="<?= $front ?>" alt="Frontal">
                <img class="img-back" src="<?= $back ?>" alt="Trasera">
            </a>
        </div>

        <div class="prenda-info">
            <div class="prenda-info-title">
                <h4><?= $row['nombre_prenda'] ?></h4>
            </div>

            <div class="prenda-info-precio">
                <p><?= $row['precio_base'] ?>BS</p>
            </div>

            <div class="prenda-info-colores">
<?php
    $colores = vistaColors($row['id']);
    while ($color =  $colores->fetch_assoc()){
        if ($color['estado'] != "no"){
            if ($color['tipo_color'] != "estampado"){
                echo '<div class="color-prenda" style="background:'.$color['color_hex'].';"  title="'.$color['codigo_color'].'"></div>';
            }else{
                $imgEstampado = "control/".$color['img_estampado'];
                echo '<div class="color-prenda" style="background:url('.$imgEstampado.');"  title="'.$color['codigo_color'].'"></div>';
            }
        }
    }
?>
            </div>
        </div>
    </div>
<?php      
        }
    }
    $mysqli->close();
?>
            </div>
        </div>

    <div id="contenedor-cargar-mas">
        <button id="btn-cargar-mas" class="btn-cargar-mas">Cargar más productos</button>
        <div class="loading-spinner" id="loading-spinner" style="display:none;">
            <div class="spinner"></div>
            <p>Cargando productos...</p>
        </div>
    </div>

    <script>
        let offset = 12;
        const limite = 12;
        let cargando = false;

        // Obtener parámetros de filtro
        const urlParams = new URLSearchParams(window.location.search);
        const filtroG = urlParams.get('g') || '';
        const filtroC = urlParams.get('c') || '';
        const filtroM = urlParams.get('m') || '';
        const filtroSub = urlParams.get('sub') || '';

        document.getElementById('btn-cargar-mas').addEventListener('click', function() {
            if (cargando) return;
            
            cargando = true;
            document.getElementById('loading-spinner').style.display = 'flex';

            fetch(`control/cargar_mas_prendas.php?offset=${offset}&g=${filtroG}&c=${filtroC}&m=${filtroM}&sub=${filtroSub}`)
                .then(response => response.text())
                .then(html => {
                    if (html.trim().length === 0) {
                        document.getElementById('btn-cargar-mas').innerText = 'No hay más productos';
                        document.getElementById('btn-cargar-mas').disabled = true;
                    } else {
                        document.getElementById('box-prendas').insertAdjacentHTML('beforeend', html);
                        offset += limite;
                    }
                    
                    document.getElementById('loading-spinner').style.display = 'none';
                    cargando = false;
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('loading-spinner').style.display = 'none';
                    cargando = false;
                });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filtroTalla = document.getElementById('filtro-talla');
            const filtroColor = document.getElementById('filtro-color');
            const filtroPrecioMin = document.getElementById('filtro-precio-min');
            const filtroPrecioMax = document.getElementById('filtro-precio-max');
            const btnLimpiar = document.getElementById('limpiar-filtros');

            function aplicarFiltros() {
                const talla = filtroTalla.value;
                const color = filtroColor.value;
                const precioMin = parseInt(filtroPrecioMin.value) || 0;
                const precioMax = parseInt(filtroPrecioMax.value) || Infinity;

                const prendas = document.querySelectorAll('.prenda');
                prendas.forEach(prenda => {
                    const tallasProducto = prenda.dataset.talla ? prenda.dataset.talla.split(',') : [];
                    const coloresProducto = prenda.dataset.color ? prenda.dataset.color.split(',') : [];
                    const precioProducto = parseInt(prenda.dataset.precio);

                    let mostrar = true;

                    // Filtrar por talla
                    if (talla && !tallasProducto.includes(talla)) {
                        mostrar = false;
                    }

                    // Filtrar por color
                    if (color && !coloresProducto.includes(color)) {
                        mostrar = false;
                    }

                    // Filtrar por precio
                    if (precioProducto < precioMin || precioProducto > precioMax) {
                        mostrar = false;
                    }

                    // Mostrar u ocultar prenda
                    prenda.style.display = mostrar ? 'block' : 'none';
                });
            }

            // Eventos para filtrar
            filtroTalla.addEventListener('change', aplicarFiltros);
            filtroColor.addEventListener('change', aplicarFiltros);
            filtroPrecioMin.addEventListener('change', aplicarFiltros);
            filtroPrecioMax.addEventListener('change', aplicarFiltros);

            // Evento para limpiar filtros
            btnLimpiar.addEventListener('click', function() {
                filtroTalla.value = '';
                filtroColor.value = '';
                filtroPrecioMin.value = document.getElementById('filtro-precio-min').min;
                filtroPrecioMax.value = document.getElementById('filtro-precio-max').max;
                aplicarFiltros();
            });
        });
    </script>
    </section>
    
    <div class="subir">
        <a href="#"><i class="fa-solid fa-circle-chevron-up"></i></a>
    </div>

    <?php include("footer.php"); ?>

</body>
</html>

