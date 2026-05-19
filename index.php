<?php include_once "control/funciones.php"; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="twitter:image" content="imgs/favicon_medium.png" />
    <meta name="twitter:site" content="https://regalelenceria.com" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="REGALE LENCERIA | Nueva experiencia Online" />
    <meta name="twitter:description" content="Descubre una nueva experiencia Online junto a Regale Lenceria" />

    <meta property="og:image" content="imgs/favicon_medium.png" />
    <meta property="og:image:alt" content="imgs/favicon_medium.png" />
    <meta property="og:site_name" content="REGALE LENCERIA | Nueva experiencia Online" />
    <meta property="og:type" content="object" />
    <meta property="og:title" content="REGALE LENCERIA" />
    <meta property="og:url" content="https://regalelenceria.com" />
    <meta property="og:description" content="Descubre una nueva experiencia Online junto a Regale Lenceria" />
    <!-- META TAG SEO -->
     <meta name="description" content="Regale Lenceria, Descubre una nueva experiencia en la compra Online de ropa interior">
     <link rel="shortcut icon" href="src/imgs/favicon_medium.png" type="image/png">
     <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- AOS Faded Scroll Effect -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <title>Regale Lenceria | Tu tienda Online</title>
    <link rel="stylesheet" href="src/style/style.css?v=<?= ASSETS_VERSION ?>">
</head>
<body>
    <div class="modal-catalogo">
        <!-- contenido catalogo -->
        <div class="contenido">

            <div class="contenido-imagen">
                <img src="src/imgs/catalogos/3.png" alt="">
            </div>

            <div class="contenido-texto">
                <button class="modal-catalogo-cerrar" aria-label="Cerrar modal">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <div class="text">
                    <h3>¡Explora nuestros catálogos!</h3>
                    <p>Mira nuestros catalogos y escoge la prenda perfecta para ti.</p>
                    <a target="_blank" href="https://catalogo.regalelenceria.com">VER CATÁLOGOS</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener("load", function (event){
            setTimeout(function(){
                const modalCatalogo = document.querySelector(".modal-catalogo");
                modalCatalogo.style.display = "flex";
            }, 5000);
        });

        document.addEventListener("DOMContentLoaded", function () {
            const closeModalBtn = document.querySelector(".modal-catalogo .modal-catalogo-cerrar");
            const modalCatalogo = document.querySelector(".modal-catalogo");

            if (closeModalBtn && modalCatalogo) {
                closeModalBtn.addEventListener("click", function () {
                    modalCatalogo.style.display = "none";
                });
            }
        });
    </script>

<!-- NAV -->
<?php include("nav.php"); ?>

    <header>
        <div class="video-bg">
            <video autoplay loop muted>
                <source src="src/bg_video.mp4" type="video/mp4">
            </video>
        </div>
    </header>
    <section>
        <div class="container">
            <div class="subtitle">
                <div class="subtitle-letter" data-aos="fade-up">
                    <h1>Descubre una nueva experiencia Online junto a Regale Lenceria</h1>
                </div>
            </div>

            <!-- Carrusel Div -->
                     <div class="carousel-container">
            <!-- Slides del carrusel -->
            <div class="carousel-slide active">
                <a href="https://regalelenceria.com/tienda.php?m=chamela">
                    <img src="src/imgs/carusel/img_1.png" alt="Imagen #1">
                </a>
            </div>
            
            <div class="carousel-slide">
                <a href="#">
                    <img src="src/imgs/carusel/img_2.jpg" alt="Imagen #2">
                </a>
            </div>
            
            <div class="carousel-slide">
                <a href="#">
                    <img src="src/imgs/carusel/img_3.jpg" alt="Imagen #3">
                </a>
            </div>
            
            <!-- Botones de navegación -->
            <button class="carousel-btn prev">&#10094;</button>
            <button class="carousel-btn next">&#10095;</button>
            
            <!-- Indicadores -->
            <div class="carousel-indicators"></div>
        </div>

            <!-- Script para carrusel -->
            <script>
            document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('.carousel-slide');
            const prevBtn = document.querySelector('.carousel-btn.prev');
            const nextBtn = document.querySelector('.carousel-btn.next');
            const indicatorsContainer = document.querySelector('.carousel-indicators');
            let currentSlide = 0;
            let slideInterval;
            
            // Crear indicadores
            slides.forEach((_, i) => {
                const indicator = document.createElement('div');
                indicator.classList.add('indicator');
                if (i === 0) indicator.classList.add('active');
                indicator.addEventListener('click', () => goToSlide(i));
                indicatorsContainer.appendChild(indicator);
            });
            
            const indicators = document.querySelectorAll('.indicator');
            
            // Función para ir a una slide específica
            function goToSlide(n) {
                slides[currentSlide].classList.remove('active');
                indicators[currentSlide].classList.remove('active');
                
                currentSlide = (n + slides.length) % slides.length;
                
                slides[currentSlide].classList.add('active');
                indicators[currentSlide].classList.add('active');
                
                // Reiniciar el intervalo
                restartInterval();
            }
            
            // Navegación
            function nextSlide() {
                goToSlide(currentSlide + 1);
            }
            
            function prevSlide() {
                goToSlide(currentSlide - 1);
            }
            
            // Event listeners para los botones
            nextBtn.addEventListener('click', nextSlide);
            prevBtn.addEventListener('click', prevSlide);
            
            // Configurar intervalo para cambio automático
            function startInterval() {
                slideInterval = setInterval(nextSlide, 5000);
            }
            
            function restartInterval() {
                clearInterval(slideInterval);
                startInterval();
            }
            
            // Iniciar el intervalo
            startInterval();
            
            // Pausar el carrusel cuando el ratón está sobre él
            const carousel = document.querySelector('.carousel-container');
            carousel.addEventListener('mouseenter', () => {
                clearInterval(slideInterval);
            });
            
            carousel.addEventListener('mouseleave', () => {
                startInterval();
            });
            
            // Soporte para teclado
            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') {
                    prevSlide();
                } else if (e.key === 'ArrowRight') {
                    nextSlide();
                }
            });
        });
    </script> 
            <div class="container-coleccion">
                <div class="coleccion-box" data-aos="fade-up" style="background-image: url(src/imgs/prendas/ropainterior.jpg); ">
                    <div class="coleccion-detalle">
                        <a class="btn-coleccion" href="tienda.php?g=mujer&c=ropainterior">ROPA INTERIOR</a>
                    </div>
                </div>

                <div class="coleccion-box" data-aos="fade-up" style="background-image: url(src/imgs/prendas/trajesdebano.jpg);">
                    <div class="coleccion-detalle">
                        <a href="tienda.php?g=mujer&c=ropainterior">TRAJES DE BAÑO</a>
                    </div>
                </div>

                <div class="coleccion-box" data-aos="fade-up" style="background-image: url(src/imgs/prendas/fajas.jpg);">
                    <div class="coleccion-detalle">
                        <a href="tienda.php?g=mujer&c=fajas">FAJAS</a>
                    </div>
                </div>

                <div class="coleccion-box" data-aos="fade-up" style="background-image: url(src/imgs/prendas/pijamas.jpg);">
                    <div class="coleccion-detalle">
                        <a href="tienda.php?g=mujer&c=pijamas">PIJAMAS</a>
                    </div>
                </div>

                <div class="coleccion-box" data-aos="fade-up" style="background-image: url(src/imgs/prendas/hombres.jpg);">
                    <div class="coleccion-detalle">
                        <a href="tienda.php?g=hombre">HOMBRES</a>
                    </div>
                </div>

                <div class="coleccion-box" data-aos="fade-up" style="background-image: url(src/imgs/prendas/sombrero.jpg);">
                    <div class="coleccion-detalle">
                        <a href="tienda.php?g=mujer&c=accesorios">ACCESORIOS</a>
                    </div>
                </div>
            </div>

            <!-- Ubicacion -->
            <div class="ubicacion" data-aos="fade-up">
                <div>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3799.024739110725!2d-63.1941767!3d-17.7905344!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x93f1e81a535a7a05%3A0x7315277f8c447eab!2sRegale%20Lencer%C3%ADa!5e0!3m2!1ses-419!2sbo!4v1736800583071!5m2!1ses-419!2sbo" width="100%" height="600" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>

<?php include("footer.php"); ?>
            </div>

        </div>
    </section>
    <!-- Whatsapp boton flotante -->
    <a href="https://wa.me/59178555506" target="_blank" class="whatsapp-float">
        <img class="whatsapp-icon" src="src/imgs/whatsapp.svg" alt="WhatsApp">
    </a>
    <!-- Init AOS -->
    <script>
        AOS.init();
    </script>
</body>
</html>



