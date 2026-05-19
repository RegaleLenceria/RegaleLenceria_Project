<?php
    header('Content-Type: application/json');
    include_once "../conexion.php";
    include_once "../funciones.php";

    if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
        echo json_encode([
            'success' => false,
            'message' => 'No autorizado'
        ]);
        exit;
    }

    try {
        $categoria_padre = isset($_POST['categoria_padre']) ? trim($_POST['categoria_padre']) : '';
        $nombre_subcategoria = isset($_POST['nombre_subcategoria']) ? trim($_POST['nombre_subcategoria']) : '';
        $descripcion_subcategoria = isset($_POST['descripcion_subcategoria']) ? trim($_POST['descripcion_subcategoria']) : '';

        if (empty($categoria_padre)) {
            echo json_encode([
                'success' => false,
                'message' => 'Debes seleccionar una categoría padre'
            ]);
            exit;
        }

        if (empty($nombre_subcategoria)) {
            echo json_encode([
                'success' => false,
                'message' => 'El nombre de la subcategoría es requerido'
            ]);
            exit;
        }

        // Verificar si la subcategoría ya existe
        $check = $mysqli->query("SELECT COUNT(*) as count FROM inventario WHERE subcategoria = '$nombre_subcategoria' AND categoria = '$categoria_padre'");
        $row = $check->fetch_assoc();
        
        if ($row['count'] > 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Esta subcategoría ya existe en esta categoría'
            ]);
            exit;
        }

        // Registrar en log de actividades
        $registro = [
            "user" => $_SESSION['username'],
            "actividad" => "Nueva subcategoría agregada: $nombre_subcategoria en $categoria_padre",
            "nivel" => 2
        ];
        registroActividad($registro);

        echo json_encode([
            'success' => true,
            'message' => 'Subcategoría agregada exitosamente',
            'subcategoria' => [
                'nombre' => $nombre_subcategoria,
                'categoria' => $categoria_padre,
                'descripcion' => $descripcion_subcategoria
            ]
        ]);

    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
?>
