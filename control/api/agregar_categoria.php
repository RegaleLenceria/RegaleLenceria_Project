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
        $nombre_categoria = isset($_POST['nombre_categoria']) ? trim($_POST['nombre_categoria']) : '';
        $descripcion_categoria = isset($_POST['descripcion_categoria']) ? trim($_POST['descripcion_categoria']) : '';

        if (empty($nombre_categoria)) {
            echo json_encode([
                'success' => false,
                'message' => 'El nombre de la categoría es requerido'
            ]);
            exit;
        }

        // Verificar si la categoría ya existe
        $check = $mysqli->query("SELECT COUNT(*) as count FROM inventario WHERE categoria = '$nombre_categoria'");
        $row = $check->fetch_assoc();
        
        if ($row['count'] > 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Esta categoría ya existe'
            ]);
            exit;
        }

        // Registrar en log de actividades
        $registro = [
            "user" => $_SESSION['username'],
            "actividad" => "Nueva categoría agregada: $nombre_categoria",
            "nivel" => 2
        ];
        registroActividad($registro);

        echo json_encode([
            'success' => true,
            'message' => 'Categoría agregada exitosamente',
            'categoria' => [
                'nombre' => $nombre_categoria,
                'descripcion' => $descripcion_categoria
            ]
        ]);

    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
?>
