      <!-- Modal Search -->
    <div class="modal-search">
        <div class="btn-close">
            <span>&times;</span>
        </div>
        <!-- MODAL SEARCH -->
        <div class="search">
            <form action="search.php" method="post">
                <input type="search" name="buscador" placeholder="BUSCAR" required>

                <div class="filtro">
                    <h3>Filtrar por: </h3>
                    <div>
                        <input type="checkbox" 
                            id="codigo" 
                            name="filtro" 
                            value="codigo">
                        <label for="codigo" class="opcion-1">Codigo</label>
                    </div>

                    <div>
                        <input type="checkbox" 
                            id="color" 
                            name="filtro" 
                            value="color">
                        <label for="color" class="opcion-2">Color</label>
                    </div>

                    <div>
                        <input type="checkbox" 
                            id="talla" 
                            name="filtro" 
                            value="talla">
                        <label for="talla" class="opcion-3">Talla</label>
                    </div>
                </div>
            </form>
        </div>
    </div>

 <div class="nav-wrap">
    <!-- Botón Hamburguesa -->
    <button class="hamburger-menu" id="hamburgerMenu" aria-label="Menú">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <nav class="main-nav" id="mainNav" aria-label="Navegación principal">
      <ul>
        <li class="brand"><a href="index.php"><img src="src/imgs/logo.png" alt="Logo regale lenceria"></a></li>

        <!-- Ropa interior -->
        <li>
          <a href="#" aria-haspopup="true" aria-expanded="false"><b>ROPA INTERIOR</b></a>

          <div class="mega" role="menu" aria-label="ropainterior - submenú">
            <!-- Panel con subcategorías (grid) -->
            <div class="mega-grid">
              <div class="col" role="none">
                <h4>BRASIERES</h4>
                <ul>
                  <li><a href="tienda.php?c=ropainterior&bra=pushup">REALSE PUSH UP</a></li>
                  <li><a href="tienda.php?c=ropainteior&bra=soporteyreduccion">SOPORTE Y REDUCCION</a></li>
                  <li><a href="tienda.php?c=ropainterior&bra=postoperatorio">POSTOPERATORIO Y REDUCCION</a></li>
                  <li><a href="tienda.php?c=ropainterior&bra=escoteprofundo">ESCOTE PROFUNDO</a></li>
                  <li><a href="tienda.php?c=ropainterior&bra=strapple">STRAPPLES</a></li>
                  <li><a href="tienda.php?c=ropainterior&bra=bralette">BRALETTE</a></li>
                  <li><a href="tienda.php?c=ropainterior&bra=bustier">BUSTIER</a></li>
                  <li><a href="tienda.php?c=ropainterior&bra=controlespalda">CONTROL ESPALDA</a></li>
                  <li><a href="tienda.php?c=ropainterior&bra=top">TOP</a></li>
                  <li><a href="tienda.php?c=ropainterior&bra=senonero">SEÑORERO</a></li>
                  <li><a href="tienda.php?c=ropainterior&bra=principiantes">PRINCIPIANTES</a></li>
                </ul>
              </div>

              <div class="col" role="none">
                <h4>PANTIES</h4>
                <ul>
                  <li><a href="tienda.php?c=ropainterior&pan=brasilera-tanga">BRASILERA/TANGA</a></li>
                  <li><a href="tienda.php?c=ropainterior&pan=cachetero">CACHETERO</a></li>
                  <li><a href="tienda.php?c=ropainterior&pan=packs">PACKS</a></li>
                  <li><a href="tienda.php?c=ropainterior&pan=postparto">POST PARTO</a></li>
                  <li><a href="tienda.php?c=ropainterior&pan=levantacola">LEVANTA COLA</a></li>
                  <li><a href="tienda.php?c=ropainterior&pan=controlreduccion">CONTROL Y REDUCCION</a></li>
                  <li><a href="tienda.php?c=ropainterior&pan=invisible">INVISIBLE</a></li>
                  <li><a href="tienda.php?c=ropainterior&pan=maternidad">MATERNIDAD</a></li>
                  <li><a href="tienda.php?c=ropainterior&pan=senoneros">SEÑORERO</a></li>
                  <li><a href="tienda.php?c=ropainterior&pan=ninas">NIÑAS</a></li>
                </ul>
              </div>

              <div class="col" role="none">
                <h4>COURSET/BODY</h4>
                <ul>
                  <li><a href="tienda.php?c=ropainterior&sub=body">BODY</a></li>
                  <li><a href="tienda.php?c=ropainterior&sub=courset">COURSET</a></li>
                </ul>
              </div>
            </div>
          </div>
        </li>

        <!-- FAJAS -->
        <li>
          <a href="#" aria-haspopup="true" aria-expanded="false"><b>FAJAS</b></a>
          <div class="mega" role="menu" aria-label="fajas - submenú">

            <div class="mega-grid">
              <div class="col">
                <h4>BENEFICIOS</h4>
                <ul>
                  <li><a href="tienda.php?g=mujer&c=fajas&b=postquirurgica">FAJAS POST QUIRURGICAS</a></li>
                  <li><a href="tienda.php?g=mujer&c=fajas&b=postparto">FAJAS POST PARTO</a></li>
                  <li><a href="tienda.php?g=mujer&c=fajas&b=levantacola">FAJAS LEVANTA COLA</a></li>
                  <!-- <li><a href="#">FAJAS LEVANTA GLUTEOS</a></li> -->
                  <li><a href="tienda.php?g=mujer&c=fajas&b=fajadeportiva">FAJAS DEPORTIVAS</a></li>
                  <li><a href="tienda.php?g=mujer&c=fajas&b=fajareductora">FAJAS REDUCTORAS</a></li>
                  <li><a href="tienda.php?g=mujer&c=fajas&b=fajadeportiva">CORREDOR POSTURA</a></li>
                  <li><a href="tienda.php?g=mujer&c=fajas&b=correctordepostura">FAJAS MATERNAS</a></li>
                  <li><a href="tienda.php?g=mujer&c=fajas&b=controlespalda">CONTROL DE ESPALDA</a></li>
                </ul>
              </div>
              
              <div class="col">
                <h4>TIPO DE FAJAS</h4>
                <ul>
                  <li><a href="tienda.php?g=mujer&c=fajas&t=bustolibre">BUSTO LIBRE</a></li>
                  <li><a href="tienda.php?g=mujer&c=fajas&t=body">FAJAS TIPO BODY</a></li>
                  <li><a href="tienda.php?g=mujer&c=fajas&t=short">FAJAS SHORT</a></li>
                  <li><a href="tienda.php?g=mujer&c=fajas&t=cinturilla">CINTURILLAS</a></li>
                  <li><a href="tienda.php?g=mujer&c=fajas&t=chalecos">CHALECOS</a></li>
                  <li><a href="tienda.php?g=mujer&c=fajas&t=controltotal">CONTROL TOTAL</a></li>
                  <li><a href="tienda.php?g=hombre&c=fajas">MASCULINAS</a></li>
                  <li><a href="tienda.php?g=mujer&c=fajas&t=tipocachetero">FAJAS TIPO CACHETERO</a></li>
                  <li><a href="tienda.php?g=mujer&c=fajas&t=moldeo">MOLDEO INVISIBLE</a></li>
                </ul>
              </div>

              <div class="col">
                <h4>SISTEMA</h4>
                <ul>
                  <li><a href="tienda.php?g=mujer&c=fajas&s=broche">FAJAS CON BROCHE</a></li>
                  <li><a href="tienda.php?g=mujer&c=fajas&s=cierre">FAJAS CON CIERRE</a></li>
                </ul>
              </div>

              <div class="col">
                <h4>TIPO DE LINEA</h4>
                <ul>
                  <li><a href="tienda.php?c=fajas&tipolinea=powernet">POWERNET</a></li>
                  <li><a href="tienda.php?c=fajas&tipolinea=latex">LATEX</a></li>
                  <li><a href="tienda.php?c=fajas&tipolinea=leggins">LEGGINS</a></li>
                </ul>
              </div>

              <div class="col">
                <h4>NIVEL DE COMPRESION</h4>
                <ul>
                  <li><a href="tienda.php?c=fajas&com=medianacompresion">MEDIANA COMPRESION</a></li>
                  <li><a href="tienda.php?c=fajas&com=altacompresion">ALTA COMPRESION</a></li>
                </ul>
              </div>

            </div>
          </div>
        </li>

        <!-- TRAJE de BAÑO -->
        <li>
          <a href="#" aria-haspopup="true" aria-expanded="false"><b>TRAJES DE BAÑOS</b></a>
          <div class="mega" role="menu" aria-label="bikini - submenú">
 
            <div class="mega-grid">
              <div class="col">
                <h4>BIKINI</h4>
                <ul>
                  <li><a href="tienda.php?sub=bikini&bi=bikinistrapple">BIKINI STRAPPLESS</a></li>
                  <li><a href="tienda.php?sub=bikini&bi=bikinitriangular">BIKINI TRIANGULAR</a></li>
                  <li><a href="tienda.php?sub=bikini&bi=bikinicoparetro">BIKINI COPA RETRO</a></li>
                  <li><a href="tienda.php?sub=bikini&bi=bikinihalter">BIKINI HALTER</a></li>
                  <li><a href="tienda.php?c=bikini&sub=top">BIKINI TOP</a></li>
                </ul>
              </div>

              <div class="col">
                <h4>MALLA</h4>
                <ul>
                  <li><a href="tienda.php?sub=malla&ma=enterastrappless">ENTERA STRAPLESS</a></li>
                  <li><a href="tienda.php?sub=malla&ma=clasica">CLASICA</a></li>
                  <li><a href="tienda.php?sub=malla&ma=coparetro">COPA RETRO</a></li>
                  <li><a href="tienda.php?sub=malla&ma=triquini">TRIQUINI</a></li>
                </ul>
             </div>

             <div class="col">
                <h4>BALNEARIO</h4>
                <ul>
                  <li><a href="tienda.php?sub=balneario&bal=enterizo">ENTERIZO</a></li>
                  <li><a href="tienda.php?sub=balneario&bal=poncho-ruana">PONCHO/RUANA</a></li>
                  <li><a href="tienda.php?sub=balneario&bal=tunica">TUNICA</a></li>
                  <li><a href="tienda.php?sub=balneario&bal=pareos">PAREOS</a></li>
                  <li><a href="tienda.php?sub=balneario&bal=vestido">VESTIDO</a></li>
                  <li><a href="tienda.php?sub=balneario&bal=pantalon">PANTALON</a></li>
                  <li><a href="tienda.php?sub=balneario&bal=camisa">CAMISA</a></li>
                  <li><a href="tienda.php?sub=balneario&bal=short">SHORT</a></li>
                </ul>
             </div>
            </div>
          </div>
        </li>

        <!-- PIJAMAS -->
          <li>
          <a href="#" aria-haspopup="true" aria-expanded="false"><b>PIJAMAS</b></a>
          <div class="mega" role="menu" aria-label="PIJAMAS - submenú">
 
            <div class="mega-grid">
              <div class="col">
                <h4>PIJAMAS COMODAS</h4>
                <ul>
                  <li><a href="tienda.php?pijamacomoda=pijamapantalon">PIJAMA PANTALON</a></li>
                  <li><a href="tienda.php?pijamacomoda=pijamashort">PIJAMA SHORT</a></li>
                </ul>
              </div>

              <div class="col">
                <h4>PIJAMAS SEXIES</h4>
                <ul>
                  <li><a href="tienda.php?pijamasexy=babydoll">BABY DOLL</a></li>
                  <li><a href="tienda.php?pijamasexy=kimino">KIMONO</a></li>
                </ul>
             </div>
            </div>
          </div>
        </li>

        <!-- ACCESORIOS -->
          <li>
          <a href="#" aria-haspopup="true" aria-expanded="false"><b>ACCESORIOS</b></a>
          <div class="mega" role="menu" aria-label="PIJAMAS - submenú">
 
            <div class="mega-grid">
              <div class="col">
                <h4>LIGUEROS-LIGA</h4>
                <ul>
                  <li><a href="tienda.php?sub=liguero">LIGUERO</a></li>
                  <li><a href="tienda.php?sub=liga">LIGA</a></li>
                </ul>
              </div>

              <div class="col">
                <h4>SOMBREROS</h4>
                <ul>
                  <li><a href="tienda.php?sub=sombrero">SOMBRERO</a></li>
                </ul>
             </div>

              <div class="col">
                <h4>OTROS</h4>
                <ul>
                  <li><a href="tienda.php?sub=broche">BROCHE</a></li>
                  <li><a href="tienda.php?sub=antifaz">ANTIFAZ</a></li>
                </ul>
             </div>

            </div>
          </div>
        </li>

          <!-- ROPA DEPORTIVA -->
          <li>
          <a href="#" aria-haspopup="true" aria-expanded="false"><b>ROPA DEPORTIVA</b></a>
          <div class="mega" role="menu" aria-label="ropadeportiva - submenú">
 
            <div class="mega-grid">
              <div class="col">
                <h4>HOMBRE</h4>
                <ul>
                  <li><a href="tienda.php?g=hombre&sub=camisetas">CAMISETA</a></li>
                  <li><a href="tienda.php?g=hombre&sub=short">SHORT</a></li>
                </ul>
              </div>

              <div class="col">
                <h4>MUJER</h4>
                <ul>
                  <li><a href="tienda.php?g=mujer&c=deportiva&sub=leggins">LEGGINS</a></li>
                  <li><a href="tienda.php?g=mujer&c=deportiva&sub=short">SHORT</a></li>
                  <li><a href="tienda.php?g=mujer&c=deportiva&sub=top">TOP</a></li>
                  <li><a href="tienda.php?g=mujer&c=deportiva&sub=camisetas">CAMISETAS</a></li>
                  <li><a href="tienda.php?g=mujer&c=deportiva&sub=polo">POLO</a></li>
                  <li><a href="tienda.php?g=mujer&c=deportiva&sub=falda">SHORT FALDA</a></li>
                </ul>
             </div>
            </div>
          </div>
        </li>

        <!-- HOMBRE -->
        <li>
          <a href="#" aria-haspopup="true" aria-expanded="false"><b>HOMBRE</b></a>
          <div class="mega" role="menu" aria-label="hombre - submenú">
            <div class="mega-grid">
              <div class="col">
                <h4>ROPA INTERIOR</h4>
                <ul>
                  <li><a href="tienda.php?g=hombre&sub=pantaloncilloclasico">PANTALONCILLO CLASICO</a></li>
                  <li><a href="tienda.php?g=hombre&sub=boxercorto">BOXER CORTO</a></li>
                  <li><a href="tienda.php?g=hombre&sub=boxerlargo">BOXER LARGO</a></li>
                  <li><a href="tienda.php?g=hombre&sub=boxersuelto">BOXER SUELTO</a></li>
                  <li><a href="tienda.php?g=hombre&sub=boxerdeportivo">BOXER DEPORTIVO</a></li>
                  <li><a href="tienda.php?g=hombre&sub=boxermedio">BOXER MEDIO</a></li>
                </ul>
              </div>
            </div>
          </div>
        </li>

      </ul>
    </nav>

    <!-- Icono de búsqueda -->
    <i class="buscar"><i class="fa-solid fa-magnifying-glass"></i></i>
    <!-- <i class="user"><i class="fa-solid fa-user"></i></i> -->
    
    <script>
            // Toggle Menú Hamburguesa
            const hamburgerMenu = document.getElementById('hamburgerMenu');
            const mainNav = document.getElementById('mainNav');

            hamburgerMenu.addEventListener('click', function() {
                mainNav.classList.toggle('active');
                hamburgerMenu.classList.toggle('active');
            });

            // Manejo de dropdown en móvil
            const menuItems = document.querySelectorAll('.main-nav li');
            menuItems.forEach(item => {
                const link = item.querySelector('a[aria-haspopup="true"]');
                if (link) {
                    link.addEventListener('click', function(e) {
                        if (window.innerWidth <= 768) {
                            e.preventDefault();
                            item.classList.toggle('active');
                        }
                    });
                }
            });

            // Cerrar menú al hacer click en un enlace
            const navLinks = document.querySelectorAll('.main-nav a');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    // No cerrar si es un dropdown que acaba de abrirse
                    if (!link.hasAttribute('aria-haspopup')) {
                        mainNav.classList.remove('active');
                        hamburgerMenu.classList.remove('active');
                    }
                });
            });

            // Cerrar menú al hacer click fuera
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.nav-wrap')) {
                    mainNav.classList.remove('active');
                    hamburgerMenu.classList.remove('active');
                    menuItems.forEach(item => item.classList.remove('active'));
                }
            });

            // Cerrar menú al hacer scroll
            window.addEventListener('scroll', function() {
                mainNav.classList.remove('active');
                hamburgerMenu.classList.remove('active');
                menuItems.forEach(item => item.classList.remove('active'));
            });

            // Cerrar dropdowns cuando se redimensiona a desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    menuItems.forEach(item => item.classList.remove('active'));
                }
            });

            const btnBuscar = document.querySelector(".buscar");
            const btnClose = document.querySelector(".btn-close");
            const modal = document.querySelector(".modal-search");

            btnBuscar.onclick = function(){
                modal.style.display = "block";
            }

            btnClose.onclick = function(){
                modal.style.display = "none";
            }
     </script>
      </ul>
    </nav>
  </div>


  