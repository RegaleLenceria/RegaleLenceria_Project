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
        $data = json_decode(file_get_contents('php://input'), true);
        $nombre = isset($data['nombre']) ? trim($data['nombre']) : '';

        if (empty($nombre)) {
            echo json_encode([
                'success' => false,
                'message' => 'Nombre de categoría requerido'
            ]);
            exit;
        }

        // Verificar si hay productos con esta categoría
        $check = $mysqli->query("SELECT COUNT(*) as count FROM inventario WHERE categoria = '$nombre'");
        $row = $check->fetch_assoc();
        
        if ($row['count'] > 0) {
            echo json_encode([
                'success' => false,
                'message' => 'No se puede eliminar esta categoría porque tiene productos asociados. Por favor, reasigna los productos primero.'
            ]);
            exit;
        }

        // Registrar en log de actividades
        $registro = [
            "user" => $_SESSION['username'],
            "actividad" => "Categoría eliminada: $nombre",
            "nivel" => 3
        ];
        registroActividad($registro);

        echo json_encode([
            'success' => true,
            'message' => 'Categoría eliminada exitosamente'
        ]);

    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
?>
