<?php
    header('Content-Type: application/json');
    include_once "../conexion.php";
    include_once "../funciones.php";

    try {
        $resultado = obtenerCategorias();
        $categorias = [];

        if ($resultado && $resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                $categorias[] = [
                    'nombre' => $row['categoria'],
                    'descripcion' => ''
                ];
            }
        }

        echo json_encode([
            'success' => true,
            'categorias' => $categorias
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
?>
