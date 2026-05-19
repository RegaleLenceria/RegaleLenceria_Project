<?php require_once "conexion.php"; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de control | Regale Lenceria</title>
    <link rel="stylesheet" href="style/panel.css">
</head>
<body>
    <header>
        <a href="index.php"><img src="../src/imgs/logo.png" alt="Logo Regale Lenceria"></a>
    </header>

    <section>
        <div class="box-login">
            <form action="" method="post">
                <label for="username"><b>Usuario</b></label>
                <input type="text" placeholder="Usuario" name="username" required>

                <label for="password_1"><b>Contraseña</b></label>
                <input type="password" placeholder="Contraseña" name="password_1" required>
                
                
                <label for="password_2"><b>Repetir contraseña</b></label>
                <input type="password" placeholder="Ingresa nuevamente la Contraseña" name="password_2" required>
                
                <label for="privilegio"><b>Tipo de cuenta</b></label>
                <select name="privilegio">
                    <option value="0">Administrador</option>
                    <option value="1">Almacen</option>
                </select></br></br>
                
                <button type="submit">Registrar</button>
            </form>
<?php 
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        // Verificar que todos los campos no esten vacios
        if (empty(trim($_POST['username'])) &&  empty(trim($_POST['password_1'])) 
            && empty(trim($_POST['password_2']))){
            echo "<b>Rellena todos los campos</b>";
        }
        
        else{
            $username =  $_POST['username'];  
            $password1 = $_POST['password_1'];
            $password2 = $_POST['password_2'];
            
            $privilegios = $_POST['privilegio'];

            if ($password1 != $password2){
                echo "<b>Las contraseñas no coinciden</b>";
            }else{
                $sql = "INSERT INTO control (usuario, password, privilegios) VALUES (?, ?, ?)";
                if ($conect = $mysqli->prepare($sql)){
                    $conect->bind_param("sss", $username, password_hash($password1, PASSWORD_DEFAULT), $privilegios);

                    if ($conect->execute()){
                        echo "<b>Registrado con exito!!</b>";
                    }else{
                        echo "<b>Error al registrar</b>";
                    }
                    // Cerrar conexion
                    $conect->close();
                }
                $mysqli->close();
            }
        }
    }
?>
        </div>
    </section>

    <footer>
        <small>Development by DigitalCruz</small>
    </footer>
</body>
</html>