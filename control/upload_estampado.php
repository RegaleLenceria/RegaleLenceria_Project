<?php
    include_once "conexion.php";
    header('Content-Type: application/json');

    // Configuración
    $uploadDir = 'upload/estampados/';
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxSize = 5 * 1024 * 1024; // 5MB

    // Verificar si se recibió un archivo
    if (!isset($_FILES['croppedImage'])) {
        echo json_encode(['success' => false, 'error' => 'No se recibió ninguna imagen.']);
        exit;
    }

    $file = $_FILES['croppedImage'];

    $id_prenda = $_POST['id_prenda'];
    $tipo_color = "estampado";
    $codigo_color = $_POST['codigo_color'];
    $descripcion = $_POST['nombre_estampado'] ?? '';
    $estado = in_array($_POST['estado'], ['activo', 'inactivo']) ? $_POST['estado'] : 'activo';

    // Validar el tipo de archivo
    if (!in_array($file['type'], $allowedTypes)) {
        echo json_encode(['success' => false, 'error' => 'Tipo de archivo no permitido.']);
        exit;
    }

    // Validar el tamaño del archivo
    if ($file['size'] > $maxSize) {
        echo json_encode(['success' => false, 'error' => 'El archivo es demasiado grande.']);
        exit;
    }

    // Crear directorio si no existe
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Generar nombre único para el archivo
    $fileName = uniqid() . '_' . basename($file['name']);
    $targetPath = $uploadDir . $fileName;

    // Mover el archivo subido al directorio de destino
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        
        $stmt = $mysqli->prepare("INSERT INTO 
                                colores (id_prenda, tipo_color, codigo_color, descripcion, img_estampado, estado)
                                VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $id_prenda, $tipo_color, $codigo_color, $descripcion, $targetPath, $estado); 
        $stmt->execute();
        $stmt->close();
        
        echo json_encode(['success' => true, 'filePath' => $targetPath]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al guardar el archivo.']);
    }
?>