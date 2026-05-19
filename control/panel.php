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
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Bienvenido al Panel Control</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/panel.css">
</head>
<body>

<?php include "includes/nav.php"; ?>
    <section>
        <div class="reloj-container">
            <div class="time-display">00:00:00</div>
            <div class="date-display">Lunes, 1 de enero de 2026</div>
        </div>

        <script>
            const hourDisplay = document.querySelector(".time-display");
            const dateDisplay = document.querySelector(".date-display");

            const daysOfWeek = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
            const monthsOfYear = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

            
            function updateHour(){
                const now = new Date(); 

                const hour = String(now.getHours()).padStart(2, "0");
                const minute = String(now.getMinutes()).padStart(2, "0");
                const second = String(now.getSeconds()).padStart(2, "0");
                hourDisplay.textContent = `${hour}:${minute}:${second}`;

                const dayName = daysOfWeek[now.getDay()];
                const day = now.getDate();
                const monthName = monthsOfYear[now.getMonth()];
                const year = now.getFullYear(); 
                dateDisplay.textContent = `${dayName}, ${day} de ${monthName} de ${year}`;

            }

            function init(){
                updateHour();
                setInterval(updateHour, 1000);
            }

            document.addEventListener('DOMContentLoaded', init);
            

        </script>

        <div class="control-container">
<?php     
        if ($_SESSION['privilegio'] != 1){
?>
            <div class="icon-container">
                <div class="icon">
                    <a href="configweb/index.php"><i class="fa-solid fa-gear"></i></a>
                </div>
                <div class="title">
                    <h3>Configuracion pagina Web</h3>
                </div>    
            </div>
<?php
    }
?>

            <div class="icon-container">
                <div class="icon">
                    <a href="inventario.php"><i class="fa-solid fa-boxes-stacked"></i></a>
                </div>
                <div class="title">
                    <h3>Inventario</h3>
                </div>              
            </div>

            <div class="icon-container">
                <div class="icon">
                    <a href="categorias.php"><i class="fa-solid fa-table-list"></i></a>
                </div>
                <div class="title">
                    <h3>Categorias</h3>
                </div>
            </div>
            
            <div class="icon-container">
                <div class="icon">
                    <a href="#"><i class="fa-solid fa-chart-simple"></i></a>
                </div>
                <div class="title">
                    <h3>Estadisticas</h3>
                </div>
            </div>

            <div class="icon-container">
                <div class="icon">
                    <a href="seguimiento.php"><i class="fa-solid fa-magnifying-glass-chart"></i></a>
                </div>
                <div class="title">
                    <h3>Sistema de seguimiento</h3>
                </div>
            </div>
        </div>
    </section>
</body>
</html>