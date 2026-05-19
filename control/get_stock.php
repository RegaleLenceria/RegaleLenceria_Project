<?php
include_once "conexion.php";
header('Content-Type: application/json');

if (!isset($_GET['prenda_id']) || !isset($_GET['codigo_color']) || !isset($_GET['talla'])) {
    echo json_encode(['success' => false, 'error' => 'Parámetros faltantes']);
    exit;
}

$id_prenda = intval($_GET['prenda_id']);
$codigo_color = $mysqli->real_escape_string(trim($_GET['codigo_color']));
$talla = $mysqli->real_escape_string(trim($_GET['talla']));

$query = $mysqli->prepare("SELECT stock FROM stock WHERE id_prenda = ? AND codigo_color = ? AND talla = ? AND estado = 'si' LIMIT 1");
if (!$query) {
    echo json_encode(['success' => false, 'error' => 'Error en consulta']);
    exit;
}

$query->bind_param("iss", $id_prenda, $codigo_color, $talla);
$query->execute();
$query->bind_result($stock);
if ($query->fetch()) {
    echo json_encode(['success' => true, 'stock' => $stock]);
} else {
    echo json_encode(['success' => false, 'error' => 'No se encontró stock para esta combinación']);
}
$query->close();
exit;
