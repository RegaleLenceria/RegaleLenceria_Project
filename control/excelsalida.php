<!DOCTYPE html>
<?php
    include_once "conexion.php";
    include_once "funciones.php";

    $fecha = date('d-m-Y');

    header("Content-Type: application/vnd.ms-excel; charset=iso-8859-1");
    header("Content-Disposition: attachment; filename={$fecha}_inventario.xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    $query = "SELECT * FROM inventario ORDER BY id DESC";
    $resultado = $mysqli->query($query);
?>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generador de Excel</title>
</head>
<body>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Código</th>
                <th>Nombre Prenda</th>
                <th>Marca Prenda</th>
                <th>Stock</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $resultado->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['codigo']; ?></td>
                <td><?php echo $row['nombre_prenda']; ?></td>
                <td><?php echo $row['marca_prenda']; ?></td>
                <td><?php echo obteniendoStock($row['id']); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>