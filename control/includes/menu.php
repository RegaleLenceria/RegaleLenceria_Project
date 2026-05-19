    <div class="opciones-container">
        <div class="opciones">
            <ul>
                <li><a href="editar.php?id=<?php echo $_GET['id']; ?>&codigo=<?php echo $codigo; ?>" class="btn"><i class="fa-solid fa-pen-to-square"></i> EDITAR</a></li>
                <li><a href="fotos.php?id=<?php echo $_GET['id']; ?>&codigo=<?php echo $codigo; ?>" class="btn"><i class="fa-solid fa-image"></i> FOTOS</a></li>
                <li><a href="colores.php?id=<?php echo $_GET['id']; ?>&codigo=<?php echo $codigo; ?>" class="btn"><i class="fa-solid fa-palette"></i> COLORES</a></li>
                <li><a href="tallas.php?id=<?php echo $_GET['id']; ?>&codigo=<?php echo $codigo; ?>" class="btn"><i class="fa-solid fa-ruler-combined"></i> TALLAS</a></li>
                <li><a href="stock.php?id=<?php echo $_GET['id']; ?>&codigo=<?php echo $codigo; ?>" class="btn"><i class="fa-solid fa-boxes-stacked"></i> STOCK</a></li>
            </ul>
        </div>
    </div>