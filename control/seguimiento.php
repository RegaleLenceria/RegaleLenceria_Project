<?php
    include_once "conexion.php";
    include_once "funciones.php";

    if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true){
        ob_end_clean();
        header("location: index.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de seguimiento de usuarios</title>
    <link rel="shortcut icon" href="../src/imgs/favicon-32x32.png" type="image/png">
    <link rel="stylesheet" href="style/style.css">
    <!-- Panel Style -->
    <link rel="stylesheet" href="style/panel.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />


</head>
<body>
    <?php include "includes/nav.php" ?>
    <header>
        <h1>Sistema de seguimiento de usuarios</h1>
    </header>

    <div class="container">
        <div class="container-logs">
            <table>
                <thead>
                    <tr>
                        <th>USUARIO</th>
                        <th>EVENTO</th>
                        <th>FECHA</th>
                    </tr>
                </thead>

                <tbody>
                    
<?php 
    $stmt = $mysqli->query("SELECT * FROM sys_registro ORDER BY id DESC");
    while ($row = $stmt->fetch_assoc()){
        echo "<tr>"; 
        switch($row['nivel']){
            case 2:
                echo "<td style='background:#ff7e43; color:#FFF;'>".$row['user']."</td>";
                echo "<td style='background:#ff7e43; color:#FFF;'>".$row['actividad']."</td>";
                echo "<td style='background:#ff7e43; color:#FFF;'>".$row['fecha']."</td>";
            break;
            
            case 3:
                echo "<td style='background:#f13224; color:#FFF;'>".$row['user']."</td>";
                echo "<td style='background:#f13224; color:#FFF;'>".$row['actividad']."</td>";
                echo "<td style='background:#f13224; color:#FFF;'>".$row['fecha']."</td>";
            break;

            default:
                echo "<td>".$row['user']."</td>";
                echo "<td>".$row['actividad']."</td>";
                echo "<td>".$row['fecha']."</td>";
        }

        echo "</tr>";
    }
?> 
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>