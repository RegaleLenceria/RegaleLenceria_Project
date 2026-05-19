<?php
    include_once "conexion.php";
    
    ob_start();
    session_start();

    // Reparacion para el Cache Busting
    // Para reparacion para la actualizacion automatica en los navegadores
    define('ASSETS_VERSION', '1.0');

    $msg = "";

    if(isset($_GET['borrar']) && isset($_GET['id'])){
        $id = $_GET['id'];
        $stmt = $mysqli->prepare("DELETE FROM fotos WHERE id = ?");
        $stmt->bind_param("i", $_GET['borrar']);
        $stmt->execute();
        $stmt->close();
        
        ob_end_clean();
        header("location: fotos.php?id={$id}");
        exit();
    }

    //Conversion de Dolar a Boliviano
    function DolarABoliviano($precio){
        $tipo_de_cambio = 6.96;
        $monto_dolares = $precio * $tipo_de_cambio;

        return $monto_dolares;
    }
   
    function obteniendoColor($color, $id_prenda){
        global $mysqli;
        $colores = $mysqli->prepare("SELECT * FROM colores 
                                   WHERE codigo_color = ? AND id_prenda = ?
                                   LIMIT 1");
        $colores->bind_param("ss", $color, $id_prenda);
        $colores->execute();
        
        return $colores->get_result();  
    }

    function obteniendoStock($id_prenda){
        global $mysqli;
        $stock_total = 0;
        $stock = $mysqli->query("SELECT * FROM stock WHERE id_prenda = {$id_prenda}");
        while( $dato = $stock->fetch_assoc()){
            $stock_total = $stock_total + $dato['stock'];
        }

        return $stock_total;
    }

    function borrarPrenda($id){
        global $mysqli;
        $stmt = $mysqli->prepare("DELETE FROM inventario WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        //Registra actividad de borrado de prenda
        $registro = ["user" => $_SESSION['username'],
                     "actividad" => "Borrardo de prenda!!",
                     "nivel" => 3];

        registroActividad($registro);
    }

    function borrarTallas($id_talla){
        global $mysqli;
        $stmt = $mysqli->prepare("DELETE FROM tallas WHERE id = ?");
        if ($stmt){
            $stmt->bind_param("i", $id_talla);
            $stmt->execute();
            $stmt->close();

            return true;
        }else{
            return false;
        }
    }

    //Productos relacionados
    function productosRelacionados($subcategoria){
        global $mysqli;
        $sql = "SELECT inventario.id, 
            inventario.nombre_prenda,
            inventario.precio_base,
            inventario.genero,
            inventario.estado,
            inventario.marca_prenda,
            inventario.categoria,
            GROUP_CONCAT(fotos.ruta) AS fotos
            FROM inventario
            LEFT JOIN fotos ON inventario.id = fotos.id_prenda
            GROUP BY inventario.id
            ORDER BY RAND()
            LIMIT 6 WHERE inventario.subcategoria = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $subcategoria);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Obteniendo productos
    function obtenerProductos($filtros = []){
        global $mysqli;

        $sql = "SELECT 
                    inventario.id, 
                    inventario.nombre_prenda,
                    inventario.codigo,
                    inventario.precio_base,
                    inventario.genero,
                    inventario.estado,
                    inventario.marca_prenda,
                    inventario.categoria,
                    inventario.subcategoria,
                    inventario.beneficio,
                    inventario.tipoFaja,
                    inventario.sistema,
                    inventario.compresion,
                    inventario.brasier,
                    inventario.panties,
                    inventario.bikini,
                    inventario.malla,
                    inventario.balneario,
                    inventario.pijamacomoda,
                    inventario.pijamasexy,
                    inventario.tipolinea,
                    GROUP_CONCAT(DISTINCT fotos.ruta) AS fotos
                FROM inventario
                LEFT JOIN fotos ON inventario.id = fotos.id_prenda";
                


        $conditions = [];
        $params = [];

        // Marca (m)
        if (!empty($filtros['m'])) {
            $conditions[] = "inventario.marca_prenda = ?";
            $params[] = $filtros['m'];
        }

        // Género (g)
        if (!empty($filtros['g'])) {
            $conditions[] = "inventario.genero = ?";
            $params[] = $filtros['g'];
        }

        // Subcategoría (sub)
        if (!empty($filtros['sub'])) {
            $conditions[] = "inventario.subcategoria = ?";
            $params[] = $filtros['sub'];
        }

        // Categoría (c)
        if (!empty($filtros['c'])) {
            $conditions[] = "inventario.categoria = ?";
            $params[] = $filtros['c'];
        }

        // Beneficio
        if (!empty($filtros['b'])){
            $conditions[] = "inventario.beneficio = ?";
            $params[] = $filtros['b'];
        }

        // Tipo de Faja
        if (!empty($filtros['t'])){
            $conditions[] = "inventario.tipoFaja = ?";
            $params[] = $filtros['t'];
        }

        // Sistema
        if (!empty($filtros['s'])){
            $conditions[] = "inventario.sistema = ?";
            $params[] = $filtros['s'];
        }

        // Compresion
        if (!empty($filtros['com'])){
            $conditions[] = "inventario.compresion = ?";
            $params[] = $filtros['com'];
        }

        // Brasier
        if (!empty($filtros['bra'])){
            $conditions[] = "inventario.brasier = ?";
            $params[] = $filtros['bra'];
        }

        // Panties 
        if (!empty($filtros['pan'])){
            $conditions[] = "inventario.panties = ?";
            $params[] = $filtros['pan'];
        }

        // Bikini
        if (!empty($filtros['bi'])){
            $conditions[] = "inventario.bikini = ?";
            $params[] = $filtros['bi'];
        }

        // Malla
        if (!empty($filtros['ma'])){
            $conditions[] = "inventario.malla = ?";
            $params[] = $filtros['ma'];
        }

        // Balneario
        if (!empty($filtros['bal'])){
            $conditions[] = "inventario.balneario = ?";
            $params[] = $filtros['bal'];
        }

        // Pijama Comoda
        if (!empty($filtros['pijamacomoda'])){
            $conditions[] = "inventario.pijamacomoda = ?";
            $params[] = $filtros['pijamacomoda'];
        }

        // Beneficio
        if (!empty($filtros['pijamasexy'])){
            $conditions[] = "inventario.pijamasexy = ?";
            $params[] = $filtros['pijamasexy'];
        }

        // Tipo de Linea
        if (!empty($filtros['tipolinea'])){
            $conditions[] = "inventario.tipolinea = ?";
            $params[] = $filtros['tipolinea'];
        }

        // Si hay condiciones, unir con AND y añadir al SQL
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $sql .= " GROUP BY inventario.id";

        // Paginación
        $limite = $filtros['limite'] ?? 12; // 12 productos por página
        $offset = $filtros['offset'] ?? 0;
        $sql .= " LIMIT ? OFFSET ?";
        $params[] = $limite;
        $params[] = $offset;

        $stmt = $mysqli->prepare($sql);

        if (!empty($params)) {
            $types = str_repeat('s', count($params) - 2) . 'ii'; // últimos dos son ints
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        return $stmt->get_result();
    }

    //Funcion usada para verificar que no se registren codigo iguales
    function verificarRepetidos($codigo){
        global $mysqli;
        
        $sql = "SELECT * FROM inventario WHERE codigo = '$codigo'";
        $resultado = $mysqli->query($sql);

        if ($resultado->num_rows > 0){
            return true;
        }else{
            return false;
        }
    }

    function totalPrendas(){
        global $mysqli;
        $resultado = $mysqli->query("SELECT * FROM inventario");
        return $resultado->num_rows;
    }

    function prendasCeroStock() {
        global $mysqli;
        $resultado = $mysqli->query("SELECT * FROM inventario");
        $contador = 0;

        while($row = $resultado->fetch_assoc()){
            if (obteniendoStock($row['id']) == 0){
                $contador++;
            }
        }

        return $contador;
    }
    
    // Reparar para que las imagenes se borren de la carpeta y no solo de la base de datos
    function borrarFoto($id){
        global $mysqli;
        
        $prenda = $mysqli->query("SELECT * FROM fotos WHERE fotos.id = {$id}");
        while($datos = $prenda->fetch_assoc()){
            $ruta_imagen = $datos['ruta'];
            if (file_exists($ruta_imagen)){
                if (unlink($ruta_imagen)){
                    return true;
                }else{
                    return false;
                }
            }
        }
        
    }

    function verificarFotos($id){
        global $mysqli;
        $resultado = $mysqli->query("SELECT * FROM fotos WHERE id_prenda = {$id}");
        return $resultado->num_rows;
    }

    function vistaColors($id){
        global $mysqli;
        $resultado = $mysqli->prepare("SELECT * FROM colores WHERE id_prenda = ?");
        $resultado->bind_param('i', $id);
        $resultado->execute();
        return $resultado->get_result();
    }

    // Obtener todas las tallas disponibles
    function obtenerTallasDisponibles(){
        global $mysqli;
        $sql = "SELECT DISTINCT talla FROM tallas ORDER BY talla ASC";
        $resultado = $mysqli->query($sql);
        return $resultado;
    }

    // Obtener todos los colores disponibles
    function obtenerColoresDisponibles(){
        global $mysqli;
        $sql = "SELECT DISTINCT codigo_color, color_hex FROM colores WHERE estado != 'no' ORDER BY codigo_color ASC";
        $resultado = $mysqli->query($sql);
        return $resultado;
    }

    // Obtener rango de precios
    function obtenerRangoPrecios(){
        global $mysqli;
        $sql = "SELECT MIN(precio_base) as precio_min, MAX(precio_base) as precio_max FROM inventario WHERE estado != 'no'";
        $resultado = $mysqli->query($sql);
        return $resultado->fetch_assoc();
    }

    // Obtener tallas para un producto específico
    function obtenerTallasProducto($id_prenda){
        global $mysqli;
        $sql = "SELECT DISTINCT talla FROM tallas WHERE id_prenda = ? ORDER BY talla ASC";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('i', $id_prenda);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Obtener colores para un producto específico
    function obtenerColoresProducto($id_prenda){
        global $mysqli;
        $sql = "SELECT DISTINCT codigo_color FROM colores WHERE id_prenda = ? AND estado != 'no' ORDER BY codigo_color ASC";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('i', $id_prenda);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Obtener productos relacionados (misma subcategoría)
    function obtenerProductosRelacionados($id_prenda, $subcategoria, $limite = 8){
        global $mysqli;
        $sql = "SELECT 
                    inventario.id,
                    inventario.nombre_prenda,
                    inventario.precio_base,
                    inventario.estado,
                    GROUP_CONCAT(DISTINCT fotos.ruta) AS fotos
                FROM inventario
                LEFT JOIN fotos ON inventario.id = fotos.id_prenda
                WHERE inventario.subcategoria = ? 
                  AND inventario.id != ?
                  AND inventario.estado != 'no'
                GROUP BY inventario.id
                LIMIT ?";
        
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('sii', $subcategoria, $id_prenda, $limite);
        $stmt->execute();
        return $stmt->get_result();
    }

    // ===== FUNCIONES DE CATEGORÍAS Y SUBCATEGORÍAS =====
    
    // Obtener todas las categorías
    function obtenerCategorias(){
        global $mysqli;
        $sql = "SELECT DISTINCT categoria FROM inventario WHERE categoria IS NOT NULL AND categoria != '' ORDER BY categoria ASC";
        $resultado = $mysqli->query($sql);
        return $resultado;
    }

    // Obtener todas las subcategorías
    function obtenerSubcategorias(){
        global $mysqli;
        $sql = "SELECT DISTINCT subcategoria FROM inventario WHERE subcategoria IS NOT NULL AND subcategoria != '' ORDER BY subcategoria ASC";
        $resultado = $mysqli->query($sql);
        return $resultado;
    }

    // Obtener subcategorías por categoría padre
    function obtenerSubcategoriasPorCategoria($categoria){
        global $mysqli;
        $sql = "SELECT DISTINCT subcategoria FROM inventario WHERE categoria = ? AND subcategoria IS NOT NULL AND subcategoria != '' ORDER BY subcategoria ASC";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('s', $categoria);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Sistema de registro de actividad
    function registroActividad($registro){
        global $mysqli;

        date_default_timezone_set('America/La_Paz');
        $fecha = date('Y-m-d H:i:s');
        
        $stmt = $mysqli->prepare("INSERT INTO 
                                  sys_registro (user, fecha, actividad, nivel)
                                  VALUES (?, ?, ?, ?)");
        if ($stmt){
            $stmt->bind_param('ssss', $registro["user"], $fecha, $registro["actividad"], $registro["nivel"]);
            $stmt->execute();

            return true;
        }else{
            return false;
        }
    } 
?>

