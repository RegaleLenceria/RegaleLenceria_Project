    <?php 
        include_once "control/conexion.php";
        include_once "control/funciones.php";

        $producto = null;
        $codigo_prenda = "";
        $og_image = "https://" . ($_SERVER['HTTP_HOST'] ?? 'localhost') . "/src/imgs/favicon_medium.png";
        $og_url = "https://" . ($_SERVER['HTTP_HOST'] ?? 'localhost') . "/vista.php";
        $subcategoria = ""; 

        if (isset($_GET['id'])){
            if (is_numeric($_GET['id'])){
                $id = intval($_GET['id']);

                // Cargar datos de producto para meta tags
                $info = $mysqli->query("SELECT * FROM inventario WHERE id = $id");
                if ($info && $info->num_rows > 0) {
                    $producto = $info->fetch_assoc();
                    $codigo_prenda = $producto['codigo'];
                    $og_url .= "?id=" . $id;
                    $subcategoria = $producto['subcategoria'];
                }

                // Cargar imagen principal para preview
                $foto = $mysqli->query("SELECT ruta FROM fotos WHERE id_prenda = $id AND estado = 'activo' LIMIT 1");
                if ($foto && $foto->num_rows > 0) {
                    $ruta_foto = $foto->fetch_assoc()['ruta'];
                    if (!empty($ruta_foto)) {
                        $og_image = "https://" . ($_SERVER['HTTP_HOST'] ?? 'localhost') . "/" . ltrim($ruta_foto, '/');
                    }
                }

            }else{
                ob_end_clean();
                header("Location: index.php");
                exit();
            }
        }
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="src/imgs/favicon_medium.png" type="image/png">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="src/style/style.css?v=<?= ASSETS_VERSION ?>">
        <link rel="stylesheet" href="src/style/vista.css?v=<?= ASSETS_VERSION ?>">
        <title><?= htmlspecialchars($producto['nombre_prenda'] ?? 'Vista Producto') ?> - Regale Lencería</title>

        <meta property="og:type" content="product">
        <meta property="og:title" content="<?= htmlspecialchars($producto['nombre_prenda'] ?? 'Regale Lencería') ?>">
        <meta property="og:description" content="<?= htmlspecialchars($producto['descripcion_corta'] ?? 'Descubre esta prenda en Regale Lencería') ?>">
        <meta property="og:image" content="<?= htmlspecialchars($og_image) ?>">
        <meta property="og:url" content="<?= htmlspecialchars($og_url) ?>">
        <meta property="og:site_name" content="Regale Lencería">

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="<?= htmlspecialchars($producto['nombre_prenda'] ?? 'Regale Lencería') ?>">
        <meta name="twitter:description" content="<?= htmlspecialchars($producto['descripcion_corta'] ?? 'Descubre esta prenda en Regale Lencería') ?>">
        <meta name="twitter:image" content="<?= htmlspecialchars($og_image) ?>">
        <style>

    /* Accordion CSS */  

    .precio{
        display: flex;
        flex-direction: row;
        justify-content: flex-start;
        align-items: center;
        gap: 20px;
    }

    .precio div{
        margin-right: 0;
    }

    .accordion {
        max-width: 100%;
        margin: 0 auto 20px auto;
        border-top: 1px solid #e5e5e5;
        border-bottom: 1px solid #e5e5e5;
    }

    .accordion button {
        position: relative;
        display: block;
        text-align: left;
        width: 100%;
        padding: 1em 0;
        color: #7288a2;
        font-size: 1rem;
        font-weight: 400;
        border: none;
        background: none;
        outline: none;
    }

    .accordion button:hover, .accordion button:focus {
        cursor: pointer;
        color: #e49191ff;
    }

    .accordion button:hover::after, .accordion button:focus::after {
        cursor: pointer;
        color: #000;
        border: 1px solid #000;
    }

    .accordion button .accordion-title {
        padding: 1em 1.5em 1em 0;
        color: #000;
        font-weight: 600;
    }

    .accordion button .icon {
        display: inline-block;
        position: absolute;
        top: 18px;
        right: 0;
        width: 22px;
        height: 22px;
        border: 1px solid;
        border-radius: 22px;
    }

    .accordion button .icon::before {
        display: block;
        position: absolute;
        content: "";
        top: 9px;
        left: 5px;
        width: 10px;
        height: 2px;
        background: currentColor;
    }

    .accordion button .icon::after {
        display: block;
        position: absolute;
        content: "";
        top: 5px;
        left: 9px;
        width: 2px;
        height: 10px;
        background: currentColor;
    }

    .accordion button[aria-expanded=true] {
        color: #03b5d2;
    }

    .accordion button[aria-expanded=true] .icon::after {
        width: 0;
    }

    .accordion button[aria-expanded=true] + .accordion-content {
        opacity: 1;
        max-height: 9em;
        transition: all 200ms linear;
        will-change: opacity, max-height;
    }

    .accordion .accordion-content {
        opacity: 0;
        max-height: 0;
        overflow: hidden;
        transition: opacity 200ms linear, max-height 200ms linear;
        will-change: opacity, max-height;
        text-align: left;
    }

    .accordion .accordion-content p {
        font-size: 0.95rem;
        margin: 10px 0;
        font-family: 'Montserrat', sans-serif;
        line-height: 1.5;
    }

    .stock-container {
        margin: 25px 0;
        width: 100%;
    }

    .stock-container h3 {
        font-size: 1em;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .stock {
        background: #f5f5f5;
        color: #444;
        padding: 12px 15px;
        border-radius: 6px;
        border: 1px solid #ddd;
        font-weight: 500;
        text-align: center;
    }
    
    .accordion button[aria-expanded=true] + .accordion-content {
    opacity: 1;
    max-height: 9em;
    transition: all 200ms linear;
    will-change: opacity, max-height;
    }
    .accordion .accordion-content {
    opacity: 0;
    max-height: 0;
    overflow: hidden;
    transition: opacity 200ms linear, max-height 200ms linear;
    will-change: opacity, max-height;
    text-align: left;
    }

    .accordion .accordion-content p {
    font-size: 1rem;
    /* font-weight: 300; */
    margin: 10px;
    font-family: 'Montserrat', sans-serif;
    }

    /* MODAL PREVIEW */
    .modal-preview {
        display: none;
        position: fixed;
        z-index: 1001;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.95);
        backdrop-filter: blur(5px);
    }

    .modal-content {
        margin: auto;
        display: block;
        max-width: 85%;
        max-height: 85vh;
        border-radius: 10px;
        box-shadow: 0 0 50px rgba(255, 255, 255, 0.2);
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        object-fit: contain;
    }

    #caption {
        position: absolute;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        color: #fff;
        font-size: 16px;
        text-align: center;
        font-family: 'Montserrat', Arial, sans-serif;
        background: rgba(0, 0, 0, 0.5);
        padding: 10px 20px;
        border-radius: 5px;
        max-width: 80%;
    }

    .modal-content, #caption {  
        animation-name: zoom;
        animation-duration: 0.6s;
    }

    @keyframes zoom {
        from {
            transform: translate(-50%, -50%) scale(0.8);
            opacity: 0;
        } 
        to {
            transform: translate(-50%, -50%) scale(1);
            opacity: 1;
        }
    }

    .close-preview {
        position: fixed;
        top: 20px;
        right: 40px;
        color: #fff;
        font-size: 40px;
        font-weight: bold;
        transition: all 0.3s ease;
        padding: 10px 15px;
        border-radius: 50%;
        background: rgba(244, 95, 95, 0.9);
        cursor: pointer;
        z-index: 1002;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .close-preview:hover,
    .close-preview:focus {
        background: rgba(244, 95, 95, 1);
        transform: scale(1.1);
        text-decoration: none;
    }

    /* Controles del modal */
    .modal-nav {
        position: fixed;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: none;
        padding: 15px 20px;
        font-size: 24px;
        cursor: pointer;
        border-radius: 5px;
        transition: all 0.3s ease;
        z-index: 1002;
    }

    .modal-nav:hover {
        background: rgba(255, 255, 255, 0.4);
        transform: translateY(-50%) scale(1.1);
    }

    .modal-nav.prev {
        left: 20px;
    }

    .modal-nav.next {
        right: 20px;
    }

    .modal-counter {
        position: fixed;
        top: 20px;
        left: 20px;
        color: #fff;
        font-family: 'Montserrat', Arial, sans-serif;
        background: rgba(0, 0, 0, 0.5);
        padding: 10px 15px;
        border-radius: 5px;
        z-index: 1002;
        font-size: 14px;
    }

        </style>
    </head>
    <body>
        <!-- Imagen modal -->
        <div id="myModal" class="modal-preview">
            <span onclick="closeModal()" class="close-preview"><i class="fa-solid fa-circle-xmark"></i></span>
            <div class="modal-counter">
                <span id="currentImage">1</span> / <span id="totalImages">1</span>
            </div>
            <button class="modal-nav prev" onclick="prevImage()" title="Imagen anterior">&larr;</button>
            <img class="modal-content" id="img01">
            <button class="modal-nav next" onclick="nextImage()" title="Siguiente imagen">&rarr;</button>
            <div id="caption"></div>
        </div>

        <!-- Botón flotante de compartir -->
        <button class="floating-share-btn" onclick="openShareModal()" title="Compartir en redes sociales">
            <i class="fa-solid fa-share-nodes"></i>
        </button>

        <!-- Modal para compartir en redes sociales -->
        <div id="shareModal" class="modal-share">
            <div class="modal-share-content">
                <div class="modal-share-header">
                    <h2>Comparte esta prenda</h2>
                    <button class="close-share" onclick="closeShareModal()">&times;</button>
                </div>
                <div class="share-options">
                    <a class="share-btn share-whatsapp" id="shareWhatsApp" target="_blank">
                        <i class="fa-brands fa-whatsapp"></i> WhatsApp
                    </a>
                    <a class="share-btn share-facebook" id="shareFacebook" target="_blank">
                        <i class="fa-brands fa-facebook"></i> Facebook
                    </a>
                    <a class="share-btn share-twitter" id="shareTwitter" target="_blank">
                        <i class="fa-brands fa-x-twitter"></i> X (Twitter)
                    </a>
                    <a class="share-btn share-linkedin" id="shareLinkedIn" target="_blank">
                        <i class="fa-brands fa-linkedin"></i> LinkedIn
                    </a>
                    <a class="share-btn share-email" id="shareEmail" target="_blank">
                        <i class="fa-solid fa-envelope"></i> Email
                    </a>
                </div>
            </div>
        </div>

        <script>
            const sharedTitle = "<?= addslashes($producto['nombre_prenda'] ?? 'Regale Lencería') ?>";
            const sharedDescription = "<?= addslashes($producto['descripcion_corta'] ?? 'Mira esta prenda en Regale Lencería') ?>";
            const sharedUrl = "<?= htmlspecialchars($og_url) ?>";

            const whatsappLink = document.getElementById('shareWhatsApp');
            const facebookLink = document.getElementById('shareFacebook');
            const twitterLink = document.getElementById('shareTwitter');
            const linkedinLink = document.getElementById('shareLinkedIn');
            const emailLink = document.getElementById('shareEmail');

            if (whatsappLink) {
                whatsappLink.href = `https://api.whatsapp.com/send?text=${encodeURIComponent(sharedTitle + ' - ' + sharedDescription + ' ' + sharedUrl)}`;
            }
            if (facebookLink) {
                facebookLink.href = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(sharedUrl)}`;
            }
            if (twitterLink) {
                twitterLink.href = `https://twitter.com/intent/tweet?text=${encodeURIComponent(sharedTitle + ' - ' + sharedDescription)}&url=${encodeURIComponent(sharedUrl)}`;
            }
            if (linkedinLink) {
                linkedinLink.href = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(sharedUrl)}`;
            }
            if (emailLink) {
                emailLink.href = `mailto:?subject=${encodeURIComponent(sharedTitle)}&body=${encodeURIComponent(sharedDescription + ' ' + sharedUrl)}`;
            }

            function openShareModal() {
                document.getElementById('shareModal').style.display = 'block';
            }
            function closeShareModal() {
                document.getElementById('shareModal').style.display = 'none';
            }
        </script>

        <?php include 'nav.php'; ?>
        <div class="container-prenda">
            <div class="preview">
    <?php
        $fotos = $mysqli->query("SELECT * FROM fotos WHERE id_prenda = {$id}");
        $numeroImagenes = $fotos->num_rows;

        while($fila = $fotos->fetch_assoc()){
            $ruta = "control/".$fila['ruta'];

            if ($fila["estado"] === "activo"){
                if ($numeroImagenes === 1){
                    echo '<div class="foto">';
                    echo '<img onclick="openModal(this)" class="imagenes" src="'.$ruta.'" id="color-'.trim($fila['color']).'">';
                    echo '</div>';
                }else{
                    echo '<div class="foto">';
                    echo '<img onclick="openModal(this)" class="imagenes" src="'.$ruta.'" id="color-'.trim($fila['color']).'">';
                    echo '</div>';
                }
            }

        }
    ?>
            </div>
                            <!-- Preview de las imagenes -->
                    <script>
                        let allModalImages = [];
                        let currentModalImageIndex = 0;

                        function openModal(element) {
                            var modal = document.getElementById("myModal");
                            var modalImg = document.getElementById("img01");
                            var captionText = document.getElementById("caption");
                            
                            // Obtener todas las imágenes del preview
                            allModalImages = Array.from(document.querySelectorAll('.preview .foto img'));
                            
                            // Encontrar el índice de la imagen clicada
                            currentModalImageIndex = allModalImages.indexOf(element);
                            
                            modal.style.display = "block";
                            displayModalImage(currentModalImageIndex);
                        }

                        function displayModalImage(index) {
                            var modal = document.getElementById("myModal");
                            var modalImg = document.getElementById("img01");
                            var captionText = document.getElementById("caption");
                            var currentImage = document.getElementById("currentImage");
                            var totalImages = document.getElementById("totalImages");
                            
                            if (index < 0) {
                                currentModalImageIndex = allModalImages.length - 1;
                            } else if (index >= allModalImages.length) {
                                currentModalImageIndex = 0;
                            }
                            
                            modalImg.src = allModalImages[currentModalImageIndex].src;
                            captionText.innerHTML = allModalImages[currentModalImageIndex].alt || "Imagen " + (currentModalImageIndex + 1);
                            currentImage.innerHTML = currentModalImageIndex + 1;
                            totalImages.innerHTML = allModalImages.length;
                        }

                        function nextImage() {
                            currentModalImageIndex++;
                            displayModalImage(currentModalImageIndex);
                        }

                        function prevImage() {
                            currentModalImageIndex--;
                            displayModalImage(currentModalImageIndex);
                        }

                        function closeModal() {
                            var modal = document.getElementById("myModal");
                            modal.style.display = "none";
                        }

                        // Navegación con teclado
                        document.addEventListener('keydown', function(event) {
                            var modal = document.getElementById("myModal");
                            if (modal.style.display === "block") {
                                if (event.key === "ArrowLeft") {
                                    prevImage();
                                } else if (event.key === "ArrowRight") {
                                    nextImage();
                                } else if (event.key === "Escape") {
                                    closeModal();
                                }
                            }
                        });

                        // Cerrar modal al hacer clic fuera de la imagen
                        document.getElementById("myModal").addEventListener('click', function(event) {
                            if (event.target === this) {
                                closeModal();
                            }
                        });

                        // Inicializar - mostrar todas las imágenes al cargar
                        document.addEventListener('DOMContentLoaded', function() {
                            const todasLasFotos = document.querySelectorAll('.preview .foto');
                            todasLasFotos.forEach(foto => {
                                foto.style.display = "flex";
                            });
                        });
                    </script>
            <div class="informacion">
                <div class="container-sticky">
    <?php 
        $informacion = $mysqli->query("SELECT * FROM inventario WHERE id = $id");
        if ($informacion){
            while($fila = $informacion->fetch_assoc()){
                $codigo_prenda = $fila['codigo'];
    ?>
            <div class="titulo">
                <h1><?php echo $fila['nombre_prenda']; ?></h1>
            </div>

            <div class="info">
                <div class="precio">
                    <div>
                        <h3 class="precio-boliviano"><b><?= $fila['precio_base'] ?> BS</b></h3>
                    </div>

                    <div>
                        <h3 class="precio-dolar"></h3>
                    </div>
                    
                    <script>
                        let precio_boliviano = document.querySelector(".precio-boliviano").textContent
                        let precio_dolar = document.querySelector(".precio-dolar");

                        let dolar = precio_boliviano.split(" ")[0] / 6.96;
                        precio_dolar.textContent = "$" + dolar.toFixed(2);                   
                    </script>
                </div>

                <div class="codigo">
                    <small>REF: <?php echo $fila['codigo']; ?></small>
                </div>
            </div>

            <div class="descripcion">
                <div class="descripcion-text">
                    <p><?php echo $fila['descripcion_corta']; ?></p>
                </div>
            </div>

            <!-- Acordeon para la descripcion larga -->
            <div class="accordion">
                <div class="accordion-item">
                    <button aria-expanded="false"><span class="accordion-title">Descripcion del Producto</span><span class="icon" aria-hidden="true"></span></button>
                    <div class="accordion-content">
                        <p><?= $fila['descripcion_larga']; ?></p>
                    </div>
                </div>
            </div>
        
            <script>
                const items = document.querySelectorAll(".accordion button");

                function toggleAccordion() {
                    const itemToggle = this.getAttribute('aria-expanded');
                    
                    items.forEach(item => {
                        item.setAttribute('aria-expanded', 'false');
                    })
                    
                    if (itemToggle == 'false') {
                        this.setAttribute('aria-expanded', 'true');
                    }
                }

                items.forEach(item => item.addEventListener('click', toggleAccordion));
            </script>

            <div class="colores-container">
                <div class="color-title">
                    <h3>COLORES DISPONIBLES: <span class="colorTitulo"></span></h3>
                </div>

                <div class="colores">
    <?php
            }
        }
        //Obteniendo colores
        $colores = $mysqli->query("SELECT * FROM colores WHERE id_prenda = $id");
        while ($fila = $colores->fetch_assoc()){
            $codigoColor = explode(",", $fila['codigo_color'])[0];

            if ($fila['estado'] === "activo"){
                if ($fila['tipo_color'] != "entero"){
                    $imgEstampado = "control/".$fila["img_estampado"];
                    

                    echo '<div onclick="imagenColor(\''.$codigoColor.'\', this.value)" class="color" style="background:url('.$imgEstampado.');" value="'.$codigoColor.'"></div>';
                    
                }else{
                    echo '<div onclick="imagenColor(\''.$codigoColor.'\', this.value)" class="color" style="background:'.$fila['color_hex'].';" value="'.$codigoColor.'"-"'.$fila['descripcion'].'"></div>';
                }
            }
        }
    ?>
        <!-- Script para eleccion de imagen segun color -->
        <script>
            let selectedColor = null;
            let selectedTalla = null;
            const prendaId = <?= intval($id) ?>;

            function imagenColor(codigo_color){
                selectedColor = codigo_color;
                const imagenes = document.querySelectorAll('.preview .foto');

                // Ocultar todas las imágenes
                imagenes.forEach(foto => {
                    foto.style.display = "none";
                });

                // Mostrar solo las imágenes del color seleccionado
                const imagenesColor = document.querySelectorAll(`.preview #color-${codigo_color}`);
                const spanColor = document.querySelector('.colorTitulo');

                imagenesColor.forEach(img => {
                    // Mostrar el contenedor padre (foto div) de la imagen
                    img.parentElement.style.display = "flex";
                });

                // Actualizar el texto del color
                spanColor.innerHTML = `<small>${codigo_color}</small>`;

                updateStock();
            }

            function getTalla(btn){
                selectedTalla = btn.textContent;
                const span = document.querySelector('#ver_talla');
                span.textContent = selectedTalla;
                updateStock();
            }

            function updateStock(){
                const stockDiv = document.querySelector('.stock');
                if (!stockDiv) return;

                if (!selectedColor || !selectedTalla){
                    stockDiv.textContent = 'Selecciona color y talla para ver stock';
                    stockDiv.style.background = '#f5f5f5';
                    stockDiv.style.color = '#444';
                    return;
                }

                stockDiv.textContent = 'Consultando stock...';
                stockDiv.style.background = '#f8f9fa';
                stockDiv.style.color = '#444';

                fetch(`control/get_stock.php?prenda_id=${prendaId}&codigo_color=${encodeURIComponent(selectedColor)}&talla=${encodeURIComponent(selectedTalla)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success){
                            const stock = parseInt(data.stock, 10);
                            stockDiv.textContent = stock;
                            stockDiv.style.background = stock > 0 ? '#f5bea0' : '#e75b5b';
                            stockDiv.style.color = '#212121';
                        } else {
                            stockDiv.textContent = data.error || 'Stock no encontrado';
                            stockDiv.style.background = '#ebbf82';
                            stockDiv.style.color = '#000';
                        }
                    })
                    .catch(() => {
                        stockDiv.textContent = 'Error consultando stock';
                        stockDiv.style.background = '#ebbf82';
                        stockDiv.style.color = '#000';
                    });
            }
        </script>
            </div>
        </div>

        <div class="talla-container">
            <div class="talla-title">
                <h3>TALLAS <span id="ver_talla"></span></h3></br>
            </div>
            
            <div class="talla">
    <?php
        // Obteniendo talla
        $tallas = $mysqli->query("SELECT * FROM tallas WHERE id_prenda = $id");
        while ($fila = $tallas->fetch_assoc()){
            if ($fila['estado'] != "no"){
                echo '<button onclick="getTalla(this)">'.$fila["talla"].'</button>';
            }
        }
    ?>
            </div>

        <div class="stock-container" style="margin: 15px 0;">
            <h3>Stock disponible</h3>
            <div class="stock" style="background: #f5f5f5; color: #444; padding: 8px 10px; border-radius: 5px; border: 1px solid #ddd;">Selecciona color y talla para ver stock</div>
        </div>

        </div>
            <div class="btn-compra">
                <a href="https://wa.me/59178555506?text=!Hola%20Regale%20Lenceria¡,%20%20Deseo%20adquirir%20la%20prenda%20con%20el%20codigo:<?= $codigo_prenda ?>">REALIZAR PEDIDO</a>
            </div>
            </div>
            </div>
        </div>

        <div class="productos-relacionados">
            <h2>TAMBIÉN TE GUSTARÁN</h2>
            <div class="grid-productos-relacionados">
<?php
    if ($producto && !empty($subcategoria)) {
        $relacionados = obtenerProductosRelacionados($id, $subcategoria, 8);
        
        if ($relacionados && $relacionados->num_rows > 0) {
            while ($rel = $relacionados->fetch_assoc()) {
                // Obtener foto principal
                if(!empty($rel['fotos'])){
                    $fotos_arr = explode(",", $rel['fotos']);
                    $foto_front = "control/".trim($fotos_arr[0]);
                } else {
                    $foto_front = 'src/imgs/placeholder.png';
                }
                
                // Obtener colores disponibles
                $colores_rel = vistaColors($rel['id']);
?>
                <div class="producto-card-relacionado">
                    <div class="imagen-producto">
                        <a href="vista.php?id=<?= $rel['id'] ?>">
                            <img src="<?= $foto_front ?>" alt="<?= $rel['nombre_prenda'] ?>">
                        </a>
                    </div>
                    
                    <div class="info-producto-relacionado">
                        <h4><?= $rel['nombre_prenda'] ?></h4>
                        <p class="precio-relacionado"><?= $rel['precio_base'] ?>BS</p>
                        <div class="colores-relacionados">
<?php
                        while ($color = $colores_rel->fetch_assoc()) {
                            if ($color['estado'] != "no") {
                                if ($color['tipo_color'] != "estampado") {
                                    echo '<div class="color-mini" style="background:'.$color['color_hex'].';" title="'.$color['codigo_color'].'"></div>';
                                } else {
                                    $imgEstampado = "control/".$color['img_estampado'];
                                    echo '<div class="color-mini" style="background:url('.$imgEstampado.');" title="'.$color['codigo_color'].'"></div>';
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
    }
?>
            </div>
        </div>

        <!-- Script para modal de compartir -->
        <script>
            function openShareModal() {
                const modal = document.getElementById('shareModal');
                const pageUrl = window.location.href;
                const pageTitle = document.querySelector('h1').textContent || document.title;

                const productCode = document.querySelector('.codigo small')?.textContent || 'Regale Lencería';


                document.getElementById('shareWhatsApp').href = 
                    `https://wa.me/?text=${encodeURIComponent(pageTitle + ' - ' + pageUrl)}`;

                document.getElementById('shareFacebook').href = 
                    `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(pageUrl)}`;

                // Twitter/X
                document.getElementById('shareTwitter').href = 
                    `https://twitter.com/intent/tweet?url=${encodeURIComponent(pageUrl)}&text=${encodeURIComponent(pageTitle)}`;

                // LinkedIn
                document.getElementById('shareLinkedIn').href = 
                    `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(pageUrl)}`;

                // Email
                document.getElementById('shareEmail').href = 
                    `mailto:?subject=${encodeURIComponent(pageTitle)}&body=${encodeURIComponent('Te comparto este producto: ' + pageUrl)}`;

                modal.style.display = 'block';
            }

            function closeShareModal() {
                const modal = document.getElementById('shareModal');
                modal.style.display = 'none';
            }

            window.onclick = function(event) {
                const shareModal = document.getElementById('shareModal');
                const imgModal = document.getElementById('myModal');

                if (event.target == shareModal) {
                    shareModal.style.display = 'none';
                }
                if (event.target == imgModal) {
                    imgModal.style.display = 'none';
                }
            }
        </script>

        <?php include("footer.php"); ?>
        
        <script>
            // Carrusel para móvil
            function initCarousel() {
                const preview = document.querySelector('.preview');
                const fotos = preview.querySelectorAll('.foto');
                
                if (window.innerWidth <= 480 && fotos.length > 1) {
                    // Activar modo carrusel
                    preview.classList.add('carousel-mode');
                    
                    // Crear controles si no existen
                    if (!document.querySelector('.carousel-controls')) {
                        // Contenedor de controles
                        const controls = document.createElement('div');
                        controls.className = 'carousel-controls';
                        
                        // Botones de navegación
                        const prevBtn = document.createElement('button');
                        prevBtn.className = 'carousel-btn prev';
                        prevBtn.innerHTML = '&#10094;';
                        prevBtn.onclick = () => movePrev();
                        
                        const nextBtn = document.createElement('button');
                        nextBtn.className = 'carousel-btn next';
                        nextBtn.innerHTML = '&#10095;';
                        nextBtn.onclick = () => moveNext();
                        
                        preview.appendChild(prevBtn);
                        preview.appendChild(nextBtn);
                        
                        // Indicadores
                        fotos.forEach((foto, index) => {
                            const dot = document.createElement('div');
                            dot.className = 'carousel-dot';
                            if (index === 0) dot.classList.add('active');
                            dot.onclick = () => goToSlide(index);
                            controls.appendChild(dot);
                        });
                        
                        preview.appendChild(controls);
                    }
                    
                    // Mostrar primera imagen
                    fotos[0].classList.add('active');
                } else if (window.innerWidth > 480) {
                    // Desactivar modo carrusel
                    preview.classList.remove('carousel-mode');
                    fotos.forEach(foto => foto.classList.remove('active'));
                    
                    // Remover controles
                    const prevBtn = preview.querySelector('.carousel-btn.prev');
                    const nextBtn = preview.querySelector('.carousel-btn.next');
                    const controls = preview.querySelector('.carousel-controls');
                    
                    if (prevBtn) prevBtn.remove();
                    if (nextBtn) nextBtn.remove();
                    if (controls) controls.remove();
                }
            }
            
            let currentSlide = 0;
            
            function goToSlide(index) {
                const preview = document.querySelector('.preview');
                const fotos = preview.querySelectorAll('.foto');
                
                fotos.forEach(foto => foto.classList.remove('active'));
                fotos[index].classList.add('active');
                
                const dots = document.querySelectorAll('.carousel-dot');
                dots.forEach(dot => dot.classList.remove('active'));
                dots[index].classList.add('active');
                
                currentSlide = index;
            }
            
            function moveNext() {
                const preview = document.querySelector('.preview');
                const fotos = preview.querySelectorAll('.foto');
                currentSlide = (currentSlide + 1) % fotos.length;
                goToSlide(currentSlide);
            }
            
            function movePrev() {
                const preview = document.querySelector('.preview');
                const fotos = preview.querySelectorAll('.foto');
                currentSlide = (currentSlide - 1 + fotos.length) % fotos.length;
                goToSlide(currentSlide);
            }
            
            // Inicializar carrusel al cargar
            document.addEventListener('DOMContentLoaded', initCarousel);
            
            // Reinicializar al redimensionar la ventana
            window.addEventListener('resize', initCarousel);
        </script>
    </body>
    </html>