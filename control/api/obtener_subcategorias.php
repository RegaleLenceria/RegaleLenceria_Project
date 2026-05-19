<?php
    header('Content-Type: application/json');
    include_once "../conexion.php";
    include_once "../funciones.php";

    try {
        $subcategorias_arr = [];

        // Obtener todas las categorías
        $categorias = obtenerCategorias();
        
        while ($cat = $categorias->fetch_assoc()) {
            $categoria = $cat['categoria'];
            
            // Para cada categoría, obtener sus subcategorías
            $subcategorias = obtenerSubcategoriasPorCategoria($categoria);
            
            while ($sub = $subcategorias->fetch_assoc()) {
                $subcategorias_arr[] = [
                    'nombre' => $sub['subcategoria'],
                    'categoria' => $categoria,
                    'descripcion' => ''
                ];
            }
        }

        echo json_encode([
            'success' => true,
            'subcategorias' => $subcategorias_arr
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
?>
