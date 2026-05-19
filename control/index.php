<?php 
    include_once "conexion.php"; 
    include "funciones.php";

    if (isset($_SESSION['logged']) && $_SESSION['logged'] === true){
        ob_end_clean();
        header("location: panel.php"); 
        exit;   
    }
    
    $msg_err = "";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/panel.css">
    <title>Regale Lenceria | Panel de Control</title>
</head>
<body>
    <header>
        <img src="../src/imgs/logo.png" alt="Logo Regale Lenceria">
    </header>

    <section>
        <div class="box-login">
            <div class="login-img">
                <img src="../src/imgs/favicon_medium.png" alt="Logo Regale">
            </div>

            <div class="login">
                <div class="logo">
                    <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxZW0iIGhlaWdodD0iMWVtIiB2aWV3Qm94PSIwIDAgNDggNDgiPjx0aXRsZSB4bWxucz0iIj5zZWN1cmUtYm94PC90aXRsZT48cGF0aCBmaWxsPSJub25lIiBzdHJva2U9ImN1cnJlbnRDb2xvciIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIiBkPSJNMTEuNjI4IDE4LjM2YzEuODE1LTUuMDAzIDYuNjEtOC41NzggMTIuMjQxLTguNTc4YzMuNzIyIDAgNy4wOCAxLjU2MyA5LjQyNyA0LjAwNG0yLjY5MiA0LjgyYy4xMy42NjUgMy44MTcgMTAuOTg1IDEuOTI3IDIzLjg3NU0xNS42MSA3LjU0MmExNy45IDE3LjkgMCAwIDAtNy43MzQgNy44OThtMjguMTQtNS4xNDZjMS4zMDMgMS4wNjMgMi40MyAyLjU0IDMuMzcgNC4zNzFNNS41IDEwLjI2OWEyMi43IDIyLjcgMCAwIDEgNC4wMzctNC4zMjZtMjguNTUtLjA5MmEyMi43IDIyLjcgMCAwIDEgMy45ODggNC4xOTRtLTMxLjIyIDEzLjYzMnMuNDY1IDQuNTM0LjM4NCAxMC4wNzNtLS4zMjQgNS45OWE0OSA0OSAwIDAgMS0uMzUgMi43NG00LjUwOCAwYy4zMzUtMS40NzUuNDYzLTIuOTY3LjU3OS00LjQ2N20xOC4xMi0uMDE3YTc5IDc5IDAgMCAxLS4zMyA0LjQ4NG0tMTMuODYtMi40MThjLjA1LS42OTIuMDktMS4zODcuMTM4LTIuMDdtOS4zNTcgMi4yMWE5NiA5NiAwIDAgMS0uMjMzIDIuMjc4bS00LjM5MS00LjQ0OGMtLjAyNS44ODctLjA2NiAxLjc3LS4xMjcgMi42MzRjLS4wNC41ODMtLjE1NCAxLjI1NS0uMjE1IDEuODE1Ii8+PHBhdGggZmlsbD0ibm9uZSIgc3Ryb2tlPSJjdXJyZW50Q29sb3IiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIgZD0iTTE5LjM2NSAxNy4yMDlhNC40MDcgNC40MDcgMCAxIDEgOC44MTUgMHYyLjM2MiIvPjxyZWN0IHdpZHRoPSIxNi44OTkiIGhlaWdodD0iMTUuNzc4IiB4PSIxNS4zMjMiIHk9IjE5LjczOSIgZmlsbD0ibm9uZSIgc3Ryb2tlPSJjdXJyZW50Q29sb3IiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIgcng9IjEuNTY3IiByeT0iMS41NjciLz48cGF0aCBmaWxsPSJub25lIiBzdHJva2U9ImN1cnJlbnRDb2xvciIgc3Ryb2tlLW1pdGVybGltaXQ9IjciIGQ9Ik0xOS4zNjUgMTcuMjA5djIuNTMiLz48Y2lyY2xlIGN4PSIyMy43NzIiIGN5PSIyNy4wMSIgcj0iMi4yNTUiIGZpbGw9Im5vbmUiIHN0cm9rZT0iY3VycmVudENvbG9yIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz48cGF0aCBmaWxsPSJub25lIiBzdHJva2U9ImN1cnJlbnRDb2xvciIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIiBkPSJNMzguNSA1LjVoLTI5YTQgNCAwIDAgMC00IDR2MjlhNCA0IDAgMCAwIDQgNGgyOWE0IDQgMCAwIDAgNC00di0yOWE0IDQgMCAwIDAtNC00Ii8+PC9zdmc+" alt="">
                </div>

                <form action="" method="post">
                    <label for="username"><b>USUARIO</b></label>
                    <input type="text" placeholder="Usuario" name="username" required>

                    <label for="password"><b>CONTRASEÑA</b></label>
                    <input type="password" placeholder="Contraseña" name="password" required>

                    <button type="submit">INGRESAR</button>
                </form>
<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if (empty(trim($_POST['username'])) && empty(trim($_POST['password']))){
            echo "<b>Error, rellena todos los campos</b>";
        }else{
            $username = $_POST['username'];
            $password = $_POST['password'];

            $sql = "SELECT id, usuario, password FROM control WHERE usuario = ?";
            if ($conect = $mysqli->prepare($sql)){
                $conect->bind_param("s", $username);

                if ($conect->execute()){
                    $conect->store_result();

                    if ($conect->num_rows == 1){
                        $conect->bind_result($id, $username, $hashed_password);
                        if ($conect->fetch()){
                            //Verificar contraseña usando el Hash almacenado
                            if (password_verify($password, $hashed_password)){
                                //Almacenando variables dentro de las sessiones
                                $_SESSION['logged'] = true;
                                $_SESSION['id'] = $id;
                                $_SESSION['username'] = $username;

                                //Obteniendo privilegios de usuario
                                $stmt = $mysqli->query("SELECT * FROM control WHERE usuario = '{$username}'");
                                $getPriv = $stmt->fetch_assoc();   
                                $privilegio = $getPriv['privilegios']; 
                                $_SESSION['privilegio'] = $privilegio;
                                
                                //Registra
                                $registro = ["user" => $username,
                                             "actividad" => "Inicio de seccion exitoso!!",
                                             "nivel" => 1];
                                             
                                registroActividad($registro);

                                ob_end_clean();
                                header("location: panel.php");
                                exit();
                            }
                        }
                    }
                }
                $msg_err = "<b>Acceso denegado</b>, verifica el usuario y contraseña.";
                $conect->close();
                sleep(1);
            }

            $mysqli->close();
        }
        
    }
?> 
            </div>

<?php 
    if (!empty($msg_err)){
        echo '<div class="msgError">';
        echo '<p class="msg_error">'.$msg_err.'</p>';
        echo '</div>';
    }
?>

    <script>
        const msgError = document.querySelector('.msgError');
        msgError.addEventListener('click', function(){
            msgError.style.display = "none";
        });
    </script>
        </div>
    </section>
</body>
</html>