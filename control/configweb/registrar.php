<?php
    include_once "../conexion.php";
    include_once "../funciones.php";

    $username = $_POST['username'];
    $password1 = $_POST['pass'];

    if (empty(trim($username)) &&  empty(trim($password1)) 
        && empty(trim($_POST['pass2']))){
        echo "<b>Rellena todos los campos</b>";
    }
    
    else{
        $password2 = $_POST['pass2'];
        
        $privilegios = $_POST['permisos'];

        if ($password1 != $password2){
            echo "<b>Las contraseñas no coinciden</b>";
        }else{
            $sql = "INSERT INTO control (usuario, password, privilegios) VALUES (?, ?, ?)";
            if ($conect = $mysqli->prepare($sql)){
                $conect->bind_param("sss", $username, password_hash($password1, PASSWORD_DEFAULT), $privilegios);

                if ($conect->execute()){
                    $msg = "Registrado con exito!!";
                }else{
                    $msg = "Error al registrar";
                }

                ob_end_clean();
                header("Location: index.php");
                exit();
                // Cerrar conexion
                $conect->close();
            }
            $mysqli->close();
        }
    }
?>