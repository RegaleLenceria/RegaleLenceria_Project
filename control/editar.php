<?php 
	include_once "conexion.php"; 
    include_once "funciones.php";

    if (!isset($_SESSION['logged']) || $_SESSION['logged'] != true){
        ob_end_clean();
        header("location: index.php");
        exit();
    }

    ob_start();
    
    $codigo = isset($_GET['codigo']) ? htmlspecialchars($_GET['codigo']) : '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Panel Control | Editar inventario</title>
</head>
<body>
    
<?php 
    include "includes/nav.php"; 
    include "includes/menu.php";
?>

    <header>
        <h1>EDITAR PRENDA</h1>
    </header>
    <section>
        
<?php 
    if(isset($_GET['id'])){
        if (!is_numeric($_GET['id'])){
            ob_end_clean();
            header("Location: inventario.php");
            exit(); 
        }

        $id = intval($_GET['id']);
        $stmt = $mysqli->prepare("SELECT * FROM inventario WHERE inventario.id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()){
?>
        <div class="box-inventario">
            <form action="" method="post">
                <div class="row">
                <div class="col">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>" >
                    <label for="nombre">NOMBRE PRENDA:</label>    
                    <input type="text" name="nombre_prenda" value="<?php echo htmlspecialchars($row['nombre_prenda']); ?>" readonly>
                </div>
                
                <div class="col">
                    <label for="codigo">CODIGO:</label>    
                    <input type="text" name="codigo" value="<?php echo htmlspecialchars($row['codigo']); ?>" readonly>
                </div>

                <div class="col">
                    <label for="genero">GENERO:</label>
                    <select name="genero">
                        <option value="<?= htmlspecialchars($row['genero']) ?>"><?= htmlspecialchars(strtoupper($row['genero'])) ?></option>
                        <option value="mujer">MUJER</option>
                        <option value="hombre">HOMBRE</option>
                        <option value="ninos">NIÑOS</option>
                    </select>
                </div>

                <div class="col">
                    <label for="marca">MARCA DE PRENDA:</label>
                    <select name="marca">
                        <option value="<?= htmlspecialchars($row['marca_prenda']) ?>"><?= htmlspecialchars(strtoupper($row['marca_prenda'])) ?></option>
                        <option value="leonisa">LEONISA</option>
                        <option value="leo">LEO</option>
                        <option value="ellipse">ELLIPSE</option>
                        <option value="touche">TOUCHE</option>
                        <option value="fajate">FAJATE</option>
                        <option value="annchery">ANN CHERRY</option>
                        <option value="chamela">CHAMELA</option>
                    </select>
                </div>
                
                <div class="col">
                    <label for="categoria">LINEA:</label>
                    <select name="categoria" id="categoria">
                        <option value="<?= htmlspecialchars($row['categoria']) ?>"><?= htmlspecialchars(strtoupper($row['categoria'])) ?></option>
                        <option value="ropainterior">ROPA INTERIOR</option>
                        <option value="fajas">FAJAS</option>
                        <option value="bikini">TRAJES DE BAÑO</option>
                        <option value="pijamas">PIJAMAS</option>
                        <option value="accesorios">ACCESORIOS</option>
                        <option value="deportiva">ROPA DEPORTIVA</option>
                    </select>
                </div>

                <div class="col">
                    <label for="subcategoria">CATEGORIA:</label>
                    <select name="subcategoria" id="subcategoria">
                        <option value="<?= htmlspecialchars($row['subcategoria']) ?>"><?= htmlspecialchars(strtoupper($row['subcategoria'])) ?></option>
                        <option value="brasieres">BRASIERES</option>
                        <option value="panties">PANTIES</option>
                        <option value="courset">COURSET</option>
                        <option value="body">BODY</option>
                        <option value="bikini">BIKINI</option>
                        <option value="malla">MALLA</option>
                        <option value="balneario">BALNEARIO</option>
                        <option value="pijamasexy">PIJAMA SEXY</option>
                        <option value="pijamacomodas">PIJAMA COMODA</option>
                        <option value="liguero">LIGUERO</option>
                        <option value="liga">LIGA</option>
                        <option value="sombrero">SOMBRERO</option>
                        <option value="camiseta">CAMISETA</option>
                        <option value="short">SHORT</option>
                        <option value="falda">FALDA</option>
                        <option value="leggins">Leggins</option>
                        <option value="top">TOP</option>
                        <option value="polo">POLO</option>
                        <option value="broche">BROCHE</option>
                        <option value="antifaz">ANTIFAZ</option>
                        <optgroup label="Ropa Interior Hombres">
                            <option value="pantaloncilloclasico">PANTALONCILLO CLASICO</option>
                            <option value="boxercorto">BOXER CORTO</option>
                            <option value="boxerlargo">BOXER LARGO</option>
                            <option value="boxersuelto">BOXER SUELTOS</option>
                            <option value="boxerdeportivos">BOXER DEPORTIVOS</option>
                            <option value="boxermedio">BOXER MEDIO</option>
                        </optgroup>
                    </select>
                </div>

                <script>
                    window.addEventListener("load", function(){

                        const subcategoria = document.getElementById("subcategoria"); // Menu de subcategoria
                        const categoriaBrasier = document.querySelector("#categoriaBrasieres"); // Select Brasieres

                        subcategoria.addEventListener("change", function(){
                            console.log(this.value);

                            if (this.value == "brasieres"){
                                categoriaBrasier.style.display = "block";
                            }else{
                                categoriaBrasier.style.display = "none";
                            }
                        });
                    });
                </script>

                <!-- Categoria Brasieres -->
                <div class="col" id="categoriaBrasieres" style="display:none;">
                    <label for="categoriabrasieres">SUBCATEGORIA - BRASIER</label>
                    <select name="categoriabrasieres" id="categoriabrasieres">
                        <option value="<?= htmlspecialchars($row['brasier']) ?>"><?= htmlspecialchars(strtoupper($row['brasier'])) ?></option>
                        <option value="pushup">REALSE PUSH UP</option>
                        <option value="soporteyreduccion">SOPORTE Y REDUCCION</option>
                        <option value="postoperatorio">POSTOPERATORIO Y POSTPARTO</option>
                        <option value="escoteprofundo">ESCOTE PROFUNDO</option>
                        <option value="strapple">STRAPPLES</option>
                        <option value="bralette">BRALETTE</option>
                        <option value="bustier">BUSTIER</option>
                        <option value="controlespalda">CONTROL ESPALDA</option>
                        <option value="top">TOP</option>
                        <option value="senoneros">SEÑOREROS</option>
                        <option value="principiantes">PRINCIPIANTES</option>
                    </select>    
                </div>

                
                <!-- Categoria Panties -->
                <div class="col" id="categoriaPanties" style="display:none;">
                    <label for="categoriaPanty">SUBCATEGORIA -PANTIES</label>
                    <select name="categoriaPanty" id="categoriaPanty">
                        <option value="<?= htmlspecialchars($row['panties']) ?>"><?= htmlspecialchars(strtoupper($row['panties'])) ?></option>
                        <option value="brasilera-tanga">BRASILERA/TANGA</option>
                        <option value="cachetero">CACHETERO</option>
                        <option value="packs">PACKS</option>
                        <option value="postparto">POST PARTO</option>
                        <option value="levantacola">LEVANTA COLA</option>
                        <option value="controlreduccion">CONTROL REDUCCION</option>
                        <option value="invisible">INVISIBLE</option>
                        <option value="maternidad">MATERNIDAD</option>
                        <option value="senoneros">SEÑOREROS</option>
                        <option value="ninas">NIÑAS</option>
                    </select>    
                </div>

                <script>
                    window.addEventListener("load", function(){

                        const subcategoria = document.getElementById("subcategoria"); // Menu de subcategoria
                        const categoriaPanties = document.querySelector("#categoriaPanties"); // Select Brasieres

                        subcategoria.addEventListener("change", function(){
                            console.log(this.value);

                            if (this.value == "panties"){
                                categoriaPanties.style.display = "block";
                            }else{
                                categoriaPanties.style.display = "none";
                            }
                        });
                    });
                </script>

                <!-- BIKINI -->
                <div class="col" id="categoriaBikini" style="display:none;">
                    <label for="categoriaBikini">SUBCATEGORIA - BIKINI</label>
                    <select name="categoriaBikini" id="categoriabikini">
                        <option value="<?= htmlspecialchars($row['bikini']) ?>"><?= htmlspecialchars(strtoupper($row['bikini'])) ?></option>
                        <option value="bikinistrapple">BIKINI STRAPPLESS</option>
                        <option value="bikinitriangular">BIKINI TRIANGULAR</option>
                        <option value="bikinicoparetro">BIKINI COPA RETRO</option>
                        <option value="bikinihalter">BIKINI HALTER</option>
                        <option value="bikinitop">BIKINI TOP</option>
                    </select>    
                </div>
                <script>
                    window.addEventListener("load", function(){

                        const subcategoria = document.getElementById("subcategoria"); // Menu de subcategoria
                        const categoriaBikini = document.querySelector("#categoriaBikini"); 

                        subcategoria.addEventListener("change", function(){
                            console.log(this.value);

                            if (this.value == "bikini"){
                                categoriaBikini.style.display = "block";
                            }else{
                                categoriaBikini.style.display = "none";
                            }
                        });
                    });
                </script>

                <!-- MALLA -->
                <div class="col" id="categoriaMalla" style="display:none;">
                    <label for="categoriaMalla">SUBCATEGORIA - MALLA</label>
                    <select name="categoriaMalla" id="categoriamalla">
                        <option value="<?= htmlspecialchars($row['malla']) ?>"><?= htmlspecialchars(strtoupper($row['malla'])) ?></option>
                        <option value="enterastrappless">ENTERA STRAPPLESS</option>
                        <option value="clasica">CLASICA</option>
                        <option value="coparetro">COPA RETRO</option>
                        <option value="triquini">TRIQUINI</option>
                    </select>    
                </div>

                <script>
                    window.addEventListener("load", function(){

                        const subcategoria = document.getElementById("subcategoria"); // Menu de subcategoria
                        const categoriaMalla = document.querySelector("#categoriaMalla"); 

                        subcategoria.addEventListener("change", function(){
                            console.log(this.value);

                            if (this.value == "malla"){
                                categoriaMalla.style.display = "block";
                            }else{
                                categoriaMalla.style.display = "none";
                            }
                        });
                    });
                </script>

                <!-- BALNEARIO -->
                <div class="col" id="categoriaBalneario" style="display:none;">
                    <label for="categoriaBalneario">SUBCATEGORIA - BALNEARIO</label>
                    <select name="categoriaBalneario" id="categoriabalneario">
                        <option value="<?= htmlspecialchars($row['balneario']) ?>"><?= htmlspecialchars(strtoupper($row['balneario'])) ?></option>
                        <option value="enterizo">ENTERIZO</option>
                        <option value="poncho-ruana">PONCHO/RUANA</option>
                        <option value="tunica">TUNICA</option>
                        <option value="pareos">PAREOS</option>
                        <option value="vestido">VESTIDO</option>
                        <option value="pantalon">PANTALON</option>
                        <option value="camisa">CAMISA</option>
                        <option value="short">SHORT</option>
                    </select>    
                </div>

                <script>
                    window.addEventListener("load", function(){

                        const subcategoria = document.getElementById("subcategoria"); // Menu de subcategoria
                        const categoriaBalneario = document.querySelector("#categoriaBalneario"); 

                        subcategoria.addEventListener("change", function(){
                            console.log(this.value);

                            if (this.value == "balneario"){
                                categoriaBalneario.style.display = "block";
                            }else{
                                categoriaBalneario.style.display = "none";
                            }
                        });
                    });
                </script>

                <!-- PIJAMAS COMODAS -->
                    <div class="col" id="categoriaPijamaComoda" style="display:none;">
                    <label for="categoriaPijamaComoda">SUBCATEGORIA - PIJAMAS COMODAS</label>
                    <select name="categoriaPijamaComoda">
                        <option value="<?= htmlspecialchars($row['pijamacomoda']) ?>"><?= htmlspecialchars(strtoupper($row['pijamacomoda'])) ?></option>
                        <option value="pijamapantalon">PIJAMA PANTALON</option>
                        <option value="pijamashort">PIJAMA SHORT</option>
                    </select>    
                </div>

                <script>
                    window.addEventListener("load", function(){

                        const subcategoria = document.getElementById("subcategoria"); // Menu de subcategoria
                        const categoriaPijamaComoda = document.querySelector("#categoriaPijamaComoda"); 

                        subcategoria.addEventListener("change", function(){
                            console.log(this.value);

                            if (this.value == "pijamacomodas"){
                                categoriaPijamaComoda.style.display = "block";
                            }else{
                                categoriaPijamaComoda.style.display = "none";
                            }
                        });
                    });
                </script>

                <!-- PIJAMAS SEXYS -->
                    <div class="col" id="categoriaPijamaSexy" style="display:none;">
                    <label for="categoriaPijamaSexy">SUBCATEGORIA - PIJAMAS COMODAS</label>
                    <select name="categoriaPijamaSexy">
                        <option value="<?= htmlspecialchars($row['pijamasexy']) ?>"><?= htmlspecialchars(strtoupper($row['pijamasexy'])) ?></option>
                        <option value="babydoll">BABYDOLL</option>
                        <option value="pijamashort">KIMONO</option>
                    </select>    
                </div>

                <script>
                    window.addEventListener("load", function(){

                        const subcategoria = document.getElementById("subcategoria"); // Menu de subcategoria
                        const categoriaPijamaSexy = document.querySelector("#categoriaPijamaSexy"); 

                        subcategoria.addEventListener("change", function(){
                            console.log(this.value);

                            if (this.value == "pijamasexy"){
                                categoriaPijamaSexy.style.display = "block";
                            }else{
                                categoriaPijamaSexy.style.display = "none";
                            }
                        });
                    });
                </script>

                
                <!-- Categoria para fajas -->
                <div class="col" id="categoriaFaja" style="display:none;">
                    <label for="Beneficios">Beneficios</label>
                    <select name="beneficios" id="Beneficios">
                        <option value="<?= htmlspecialchars($row['beneficio']) ?>"><?= htmlspecialchars(ucfirst($row['beneficio'])) ?></option>
                        <option value="postquirurgica">POST QUIRURGICA</option>
                        <option value="postparto">POST PARTO</option>
                        <option value="levantacola">LEVANTA COLA</option>
                        <option value="fajareductora">FAJA REDUCTORA</option>
                        <option value="fajadeportiva">FAJA DEPORTIVA</option>
                        <option value="correctordepostura">CORRECTOR DE POSTURA</option>
                        <option value="fajamaterna">FAJA MATERNA</option>
                        <option value="controlespalda">CONTROL ESPALDA</option>
                    </select>
                </div>

                <div class="col" id="categoriaFaja" style="display:none;">
                    <label for="tipoFajas">Tipo de Faja</label>
                    <select name="tipoFaja" id="tipoFajas">
                        <option value="<?= htmlspecialchars($row['tipoFaja']) ?>"><?= htmlspecialchars(ucfirst($row['tipoFaja'])) ?></option>
                        <option value="bustolibre">BUSTO LIBRE</option>
                        <option value="body">TIPO BODY</option>
                        <option value="shorts">FAJA SHORT</option>
                        <option value="cinturilla">CINTURILLAS</option>
                        <option value="chalecos">CHALECOS</option>
                        <option value="controltotal">CONTROL TOTAL</option>
                        <option value="masculino">MASCULINOS</option>
                        <option value="tipocachetero">TIPO CACHETERO</option>
                        <option value="moldeo">MOLDEO INVISIBLE</option>
                    </select>
                </div>

                <div class="col" id="categoriaFaja" style="display:none;">
                    <label for="sistema">Sistema</label>
                    <select name="sistema" id="sistema">
                        <option value="<?= htmlspecialchars($row['sistema']) ?>"><?= htmlspecialchars(ucfirst($row['sistema'])) ?></option>
                        <option value="broche">FAJA CON BROCHE</option>
                        <option value="cierre">FAJA CON CIERRE</option>
                    </select>
                </div>

                <div class="col" id="categoriaFaja" style="display:none;">
                    <label for="compresion">Nivel de compresion</label>
                    <select name="compresion" id="compresion">
                        <option value="<?= htmlspecialchars($row['compresion']) ?>"><?= htmlspecialchars(ucfirst($row['compresion'])) ?></option>
                        <option value="medianacompresion">MEDIANA</option>
                        <option value="altacompresion">ALTA</option>
                    </select>
                </div>

                <div class="col" id="categoriaFaja" style="display:none;">
                    <label for="tipolinea">TIPO DE LINEA</label>
                    <select name="tipolinea">
                        <option value="<?= htmlspecialchars($row['tipolinea']) ?>"><?= htmlspecialchars(strtoupper($row['tipolinea'])) ?></option>
                        <option value="powernet">POWERNET</option>
                        <option value="latex">LATEX</option>
                        <option value="leggins">LEGGINS</option>
                    </select>
                </div>

                <!-- Campo de descripciones -->
                <div class="col">
                    <label for="descripcion_corta">Descripcion corta:</label>
                    <textarea style="height:200px;" name="descripcion_corta"><?php echo htmlspecialchars($row['descripcion_corta']); ?></textarea>
                </div>

                <div class="col">
                    <label for="descripcion_larga">Descripcion Larga:</label>
                    <textarea style="height:200px;" name="descripcion_larga"><?php echo htmlspecialchars($row['descripcion_larga']); ?></textarea>
                </div>

                <!-- Campo de Precio -->
                <div class="col">
                    <label for="precio_dolar">Precio en Dolares</label>
                    <input type="text" id="precio_dolar" placeholder="00.0$">

                    <label for="precio_bs">Precio en Boliviano:</label>
                    <input type="text" id="precio_boliviano" name="precio" value="<?php echo htmlspecialchars($row['precio_base']); ?>" required>
                    <!-- Conversion de dolar a bolivianos -->
                    <script>
                        const inputDolar = document.querySelector("#precio_dolar");
                        const inputBoliviano = document.querySelector("#precio_boliviano");

                        
                        inputDolar.addEventListener('keyup', function(){

                            conversionDolar = inputDolar.value * 6.96;
                            inputBoliviano.value = conversionDolar.toFixed(2);  
                        });
                    </script>
                </div>

                <div class="col">
                    <label for="estado">Ver prenda Online:</label>
                    <select name="estado">
                        <option value="<?= htmlspecialchars($row['estado']) ?>"><?= htmlspecialchars(ucfirst($row['estado'])) ?></option>
                        <option value="si">SI</option>
                        <option value="no">NO</option>
                    </select>
                </div>

                    <script>
                        // Magic for Noobs :P
                        window.addEventListener("load", function(){
                            const categoria = document.getElementById("categoria");
                            const subcategoria = document.getElementById("subcategoria");

                            const categoriaFaja = document.querySelectorAll("#categoriaFaja");

                            categoria.addEventListener("change", function(){
                                if (this.value == "fajas"){
                                    subcategoria.style.display = "none";
                                    
                                    categoriaFaja.forEach(function(a){
                                        a.style.display = "block";
                                    });
                                    
                                }else{
                                    subcategoria.style.display = "block";
                                        categoriaFaja.forEach(function(a){
                                        a.style.display = "none";
                                    });
                                }
                            });
                        });
                    </script>
                </div>                            
                <button>ACTUALIZAR</button>
            </form>
        </div>
<?php
        }
        $result->close();
        $stmt->close();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        if (isset($_POST['id']) && !empty($_POST['id'])){
            $id = intval($_POST['id']);
            $nombrePrenda = htmlspecialchars($_POST['nombre_prenda']);
            $codigo = htmlspecialchars($_POST['codigo']);
            $genero = htmlspecialchars($_POST['genero']);
            $marca = htmlspecialchars($_POST['marca']);
            $categoria = htmlspecialchars($_POST['categoria']);
            $subcategoria = htmlspecialchars($_POST['subcategoria']);
            $descripcionCorta = htmlspecialchars($_POST['descripcion_corta']);
            $descripcionLarga = htmlspecialchars($_POST['descripcion_larga']);
            $precio = doubleval($_POST['precio']);
            $estado = htmlspecialchars($_POST['estado']);

            $beneficio = isset($_POST['beneficios']) ? htmlspecialchars($_POST['beneficios']) : '';
            $categoriaFaja = isset($_POST['tipoFaja']) ? htmlspecialchars($_POST['tipoFaja']) : '';
            $sistema = isset($_POST['sistema']) ? htmlspecialchars($_POST['sistema']) : '';
            $compresion = isset($_POST['compresion']) ? htmlspecialchars($_POST['compresion']) : '';
            $tipolinea = isset($_POST['tipolinea']) ? htmlspecialchars($_POST['tipolinea']) : '';

            $brasier = isset($_POST['categoriabrasieres']) ? htmlspecialchars($_POST['categoriabrasieres']) : '';
            $panties = isset($_POST['categoriaPanty']) ? htmlspecialchars($_POST['categoriaPanty']) : '';
            $bikini = isset($_POST['categoriaBikini']) ? htmlspecialchars($_POST['categoriaBikini']) : '';
            $malla = isset($_POST['categoriaMalla']) ? htmlspecialchars($_POST['categoriaMalla']) : '';
            $balneario = isset($_POST['categoriaBalneario']) ? htmlspecialchars($_POST['categoriaBalneario']) : '';
            $pijamacomoda = isset($_POST['categoriaPijamaComoda']) ? htmlspecialchars($_POST['categoriaPijamaComoda']) : '';
            $pijamasexy = isset($_POST['categoriaPijamaSexy']) ? htmlspecialchars($_POST['categoriaPijamaSexy']) : '';

            $consulta = $mysqli->prepare("UPDATE inventario
                                          SET nombre_prenda = ?,
                                              codigo = ?,
                                              genero = ?,
                                              marca_prenda = ?,
                                              categoria = ?,
                                              subcategoria = ?,
                                              descripcion_corta = ?,
                                              descripcion_larga = ?,
                                              precio_base = ?,
                                              estado = ?,
                                              beneficio = ?,
                                              tipoFaja = ?,
                                              sistema = ?,
                                              compresion = ?,
                                              tipolinea = ?,
                                              brasier = ?,
                                              panties = ?,
                                              bikini = ?,
                                              malla = ?,
                                              balneario = ?,
                                              pijamacomoda = ?,
                                              pijamasexy = ?
                                           WHERE id = ?
                                        ");

            $consulta->bind_param("ssssssssdsssssssssssssi", $nombrePrenda, 
                                                $codigo, 
                                                $genero,
                                                $marca, 
                                                $categoria, 
                                                $subcategoria,
                                                $descripcionCorta, 
                                                $descripcionLarga, 
                                                $precio, 
                                                $estado,
                                                $beneficio,
                                                $categoriaFaja,
                                                $sistema,
                                                $compresion,
                                                $tipolinea,
                                                $brasier,
                                                $panties,
                                                $bikini,
                                                $malla,
                                                $balneario,
                                                $pijamacomoda,
                                                $pijamasexy,
                                                $id);
            if ($consulta->execute()){

                $registro = ["user" => $_SESSION['username'],
                             "actividad" => "Edicion de codigo (".$codigo.") exitoso.",
                             "nivel" => 2];

                registroActividad($registro);
                
                ob_end_clean();
                header("Location: editar.php?id=" . $id . "&codigo=" . urlencode($codigo));
                exit();
            }else{
                echo "<script>alert('Error al actualizar los datos, Ponte en contacto con el administrador')</script>";
                return 0;
            }

           $consulta->close(); 
        }
    }
?>
</section>

    <?php include 'includes/atras.php'; ?>
</body>
</html>
