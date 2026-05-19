<?php
    include_once "../conexion.php";
    include_once "../funciones.php";

    if (!isset($_SESSION['logged']) || $_SESSION['logged'] != true) {
        echo "No autorizado";
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $usuario_id = intval($_POST['usuario_id']);
        $permisos = intval($_POST['permisos']);
        $cambiar_password = isset($_POST['pass']) && !empty($_POST['pass']);

        // Validar que el usuario exista
        $check = $mysqli->prepare("SELECT id FROM control WHERE id = ?");
        $check->bind_param("i", $usuario_id);
        $check->execute();
        $resultado = $check->get_result();

        if ($resultado->num_rows == 0) {
            echo "Usuario no encontrado";
            exit;
        }

        // Actualizar permisos
        $update = $mysqli->prepare("UPDATE control SET privilegios = ? WHERE id = ?");
        $update->bind_param("ii", $permisos, $usuario_id);
        
        if (!$update->execute()) {
            echo "Error al actualizar permisos";
            exit;
        }

        // Actualizar contraseña si es necesario
        if ($cambiar_password) {
            $nueva_pass = $_POST['pass'];
            $pass_hash = password_hash($nueva_pass, PASSWORD_BCRYPT);
            
            $update_pass = $mysqli->prepare("UPDATE control SET contraseña = ? WHERE id = ?");
            $update_pass->bind_param("si", $pass_hash, $usuario_id);
            
            if (!$update_pass->execute()) {
                echo "Error al actualizar contraseña";
                exit;
            }
        }

        echo "éxito";
    }
?>
