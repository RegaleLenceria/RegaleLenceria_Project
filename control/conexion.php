<?php
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $database = "regalele_base";

    $mysqli = new mysqli ($hostname, $username, $password, $database);
    if ($mysqli->connect_errno){
        echo "<b>Error al conectar con la base de datos</b>";
    }
?>