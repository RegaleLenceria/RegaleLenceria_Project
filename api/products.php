<?php
/**
 * api/products.php
 * Endpoint principal de productos
 *
 * GET /api/products.php
 *   ?page=1         Página (default: 1)
 *   ?limit=12       Productos por página (default: 12)
 *   ?c=ropainterior Categoría
 *   ?sub=bralette   Subcategoría
 *   ?g=mujer        Género (mujer | hombre)
 *   ?m=leonisa      Marca
 *   ?bra=pushup     Tipo de brasier
 *   ?pan=brasilera  Tipo de panty
 *   ?b=postquirurgica Beneficio (fajas)
 *   ?q=palabra      Búsqueda de texto libre
 *   ?featured=1     Solo productos destacados (primeros 8 ordenados por RAND)
 *
 * GET /api/products.php?id=18
 *   Detalle de un producto específico con tallas y colores
 */

require_once __DIR__ . '/config.php';
setCORSHeaders();

$db = getDB();

// ── Detalle de un producto ────────────────────────────────────────────
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $sql = "SELECT
                i.id, i.nombre_prenda, i.codigo, i.precio_base, i.genero,
                i.marca_prenda, i.descripcion_corta, i.descripcion_larga, i.estado,
                (SELECT cat.nombre FROM prenda_subcategoria ps JOIN subcategorias s ON ps.subcategoria_id = s.id JOIN categorias cat ON s.categoria_id = cat.id WHERE ps.prenda_id = i.id LIMIT 1) AS categoria,
                (SELECT s.nombre FROM prenda_subcategoria ps JOIN subcategorias s ON ps.subcategoria_id = s.id WHERE ps.prenda_id = i.id LIMIT 1) AS subcategoria,
                GROUP_CONCAT(DISTINCT f.ruta ORDER BY f.id SEPARATOR '|') AS fotos
            FROM inventario i
            LEFT JOIN fotos f ON i.id = f.id_prenda AND f.estado = 'activo'
            WHERE i.id = ? AND i.estado != 'no'
            GROUP BY i.id";

    $stmt = $db->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    if (!$row) {
        http_response_code(404);
        echo json_encode(['error' => 'Product not found']);
        exit;
    }

    // Tallas
    $tallasRes = $db->prepare("SELECT DISTINCT talla FROM tallas WHERE id_prenda = ? AND estado = 'activo' ORDER BY talla");
    if (!$tallasRes) {
        // Fallback si no tiene campo estado
        $tallasRes = $db->prepare("SELECT DISTINCT talla FROM tallas WHERE id_prenda = ? ORDER BY talla");
    }
    $tallasRes->bind_param('i', $id);
    $tallasRes->execute();
    $tallas = array_column($tallasRes->get_result()->fetch_all(MYSQLI_ASSOC), 'talla');

    // Colores
    $coloresRes = $db->prepare(
        "SELECT codigo_color, color_hex, descripcion, tipo_color, img_estampado
         FROM colores WHERE id_prenda = ? AND estado = 'activo'"
    );
    $coloresRes->bind_param('i', $id);
    $coloresRes->execute();
    $colores = $coloresRes->get_result()->fetch_all(MYSQLI_ASSOC);

    // Materiales
    $materialesRes = $db->prepare(
        "SELECT m.nombre 
         FROM prenda_material pm 
         JOIN materiales m ON pm.material_id = m.id 
         WHERE pm.prenda_id = ?"
    );
    $materiales = [];
    if ($materialesRes) {
        $materialesRes->bind_param('i', $id);
        $materialesRes->execute();
        $materiales = array_column($materialesRes->get_result()->fetch_all(MYSQLI_ASSOC), 'nombre');
    }

    // Stock total
    $stockRes = $db->prepare("SELECT IFNULL(SUM(stock), 0) AS total_stock FROM stock WHERE id_prenda = ? AND estado != 'no'");
    $totalStock = 0;
    if ($stockRes) {
        $stockRes->bind_param('i', $id);
        $stockRes->execute();
        $totalStock = (int)$stockRes->get_result()->fetch_assoc()['total_stock'];
    }

    echo json_encode(formatProduct($row, $tallas, $colores, $materiales, $totalStock));
    exit;
}

// Mapa de subcategorías del frontend a la BD
$subMap = [
    // Brasier
    'pushup' => 'Push Up',
    'bralette' => 'Bralette',
    'strapple' => 'Strapless',
    'escote' => 'Escote Profundo',
    'bustier' => 'Bustier',
    'top' => 'top',
    'soporte' => 'Control y Soporte',
    'postoperatorio' => 'PostOperatorio y Maternidad',
    'controlespalda' => 'Control y Soporte',
    'senyorero' => 'Senonero',
    // Panty
    'brasilera' => 'Brasilera',
    'brasilera-tanga' => 'Brasilera',
    'cachetero' => 'Cachetero',
    'bikini' => 'Bikini',
    'boxer' => 'Boxer',
    'maternidad' => 'Meternidad',
    'packs' => 'pack',
    // Deportivos
    'top_deportivos' => 'Top',
    'camiseta' => 'Camiseta',
    'chaqueta' => 'Chaqueta',
    'short' => 'Short',
    'falda' => 'Falda',
    'biker' => 'Biker',
    'legging' => 'Leggins',
    'enterizo' => 'Enterizo',
    'jogger' => 'Jogger',
    'mangalarga' => 'Manga Larga',
    // Trajes de Baño
    'malla' => 'Malla',
    'pareo' => 'Pareo',
    'camisa' => 'Camisa',
    'pantalon' => 'Pantalon',
    'vestido' => 'Vestido',
    // Pijamas
    'babydoll' => 'Baby Doll',
    'camison' => 'Camison',
    'kimono' => 'Kimono',
    'body' => 'Body',
];

// ── Lista de productos ────────────────────────────────────────────────
$limit  = min((int)($_GET['limit'] ?? 12), 48);
$page   = max((int)($_GET['page']  ?? 1), 1);
$offset = ($page - 1) * $limit;

$conditions = ["i.estado != 'no'"];
$params     = [];
$types      = '';

// 1. Categoría
if (!empty($_GET['c'])) {
    $cVal = $_GET['c'];
    if ($cVal === 'ropainterior') {
        $conditions[] = "i.id IN (
            SELECT ps.prenda_id 
            FROM prenda_subcategoria ps 
            JOIN subcategorias s ON ps.subcategoria_id = s.id 
            JOIN categorias cat ON s.categoria_id = cat.id 
            WHERE cat.nombre IN ('Brasier', 'Panty')
        )";
    } else {
        $mappedCat = $cVal;
        if (strcasecmp($cVal, 'deportiva') === 0) $mappedCat = 'Deportivos';
        if (strcasecmp($cVal, 'trajes') === 0) $mappedCat = 'Trajes de Baño';
        if (strcasecmp($cVal, 'accesorios') === 0) $mappedCat = 'Accesorios';
        
        $conditions[] = "i.id IN (
            SELECT ps.prenda_id 
            FROM prenda_subcategoria ps 
            JOIN subcategorias s ON ps.subcategoria_id = s.id 
            JOIN categorias cat ON s.categoria_id = cat.id 
            WHERE cat.nombre = ? OR cat.nombre LIKE ?
        )";
        $params[] = $mappedCat;
        $params[] = '%' . $mappedCat . '%';
        $types   .= 'ss';
    }
}

// 2. Género
if (!empty($_GET['g'])) {
    $conditions[] = "i.genero = ?";
    $params[]     = $_GET['g'];
    $types       .= 's';
}

// 3. Marca
if (!empty($_GET['m'])) {
    $conditions[] = "i.marca_prenda = ?";
    $params[]     = $_GET['m'];
    $types       .= 's';
}

// 4. Subcategoría
$subVal = '';
if (!empty($_GET['sub'])) {
    $subVal = $_GET['sub'];
} elseif (!empty($_GET['bra'])) {
    $subVal = $_GET['bra'];
} elseif (!empty($_GET['pan'])) {
    $subVal = $_GET['pan'];
} elseif (!empty($_GET['bi'])) {
    $subVal = $_GET['bi'];
} elseif (!empty($_GET['ma'])) {
    $subVal = $_GET['ma'];
} elseif (!empty($_GET['bal'])) {
    $subVal = $_GET['bal'];
}

if ($subVal !== '') {
    $mappedSub = $subVal;
    $lowerSub = strtolower($subVal);
    if (isset($subMap[$lowerSub])) {
        $mappedSub = $subMap[$lowerSub];
    }
    
    $conditions[] = "i.id IN (
        SELECT ps.prenda_id 
        FROM prenda_subcategoria ps 
        JOIN subcategorias s ON ps.subcategoria_id = s.id 
        WHERE s.nombre = ? OR s.nombre LIKE ?
    )";
    $params[] = $mappedSub;
    $params[] = '%' . $mappedSub . '%';
    $types   .= 'ss';
}

// 5. Beneficio
if (!empty($_GET['b'])) {
    $conditions[] = "i.id IN (
        SELECT pb.prenda_id 
        FROM prenda_beneficio pb 
        JOIN beneficios b ON pb.beneficio_id = b.id 
        WHERE b.nombre = ? OR b.nombre LIKE ?
    )";
    $params[] = $_GET['b'];
    $params[] = '%' . $_GET['b'] . '%';
    $types   .= 'ss';
}

// Búsqueda de texto libre
if (!empty($_GET['q'])) {
    $conditions[] = "(i.nombre_prenda LIKE ? OR i.codigo LIKE ? OR i.descripcion_corta LIKE ?)";
    $like = '%' . $_GET['q'] . '%';
    $params = array_merge($params, [$like, $like, $like]);
    $types .= 'sss';
}

$where = implode(' AND ', $conditions);

// Total para paginación
$countStmt = $db->prepare("SELECT COUNT(DISTINCT i.id) AS total FROM inventario i WHERE $where");
if ($types) $countStmt->bind_param($types, ...$params);
$countStmt->execute();
$total = (int)$countStmt->get_result()->fetch_assoc()['total'];

// ORDER BY: productos más nuevos primero, o aleatorio para featured
$orderBy = !empty($_GET['featured']) ? 'RAND()' : 'i.id DESC';

$sql = "SELECT
            i.id, i.nombre_prenda, i.codigo, i.precio_base, i.genero,
            i.marca_prenda, i.descripcion_corta, i.estado,
            (SELECT cat.nombre FROM prenda_subcategoria ps JOIN subcategorias s ON ps.subcategoria_id = s.id JOIN categorias cat ON s.categoria_id = cat.id WHERE ps.prenda_id = i.id LIMIT 1) AS categoria,
            (SELECT s.nombre FROM prenda_subcategoria ps JOIN subcategorias s ON ps.subcategoria_id = s.id WHERE ps.prenda_id = i.id LIMIT 1) AS subcategoria,
            (SELECT IFNULL(SUM(st.stock), 0) FROM stock st WHERE st.id_prenda = i.id AND st.estado != 'no') AS stock,
            GROUP_CONCAT(DISTINCT f.ruta ORDER BY f.id SEPARATOR '|') AS fotos
        FROM inventario i
        LEFT JOIN fotos f ON i.id = f.id_prenda AND f.estado = 'activo'
        WHERE $where
        GROUP BY i.id
        ORDER BY $orderBy
        LIMIT ? OFFSET ?";

$params[] = $limit;
$params[] = $offset;
$types   .= 'ii';

$stmt = $db->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$products = array_map(fn($r) => formatProduct($r, [], [], [], [], (int)($r['stock'] ?? 0)), $rows);

echo json_encode([
    'data'       => $products,
    'total'      => $total,
    'page'       => $page,
    'limit'      => $limit,
    'totalPages' => (int)ceil($total / $limit),
]);

// ── Helper ────────────────────────────────────────────────────────────
function formatProduct(array $row, array $tallas = [], array $colores = [], array $materiales = [], int $stock = 0): array {
    $fotos = $row['fotos']
        ? array_map(
            fn($f) => UPLOAD_BASE . '/' . ltrim($f, '/upload/'),
            explode('|', $row['fotos'])
          )
        : [];

    return [
        'id'          => (string)$row['id'],
        'name'        => $row['nombre_prenda'],
        'sku'         => $row['codigo'],
        'price'       => (string)($row['precio_base'] ?? '0'),
        'category'    => $row['categoria']    ?? '',
        'subcategory' => $row['subcategoria'] ?? '',
        'brand'       => $row['marca_prenda'] ?? '',
        'gender'      => $row['genero']       ?? '',
        'description' => [
            'short'    => $row['descripcion_corta'] ?? '',
            'long'     => $row['descripcion_larga'] ?? '',
            'features' => [],
        ],
        'images'   => $fotos,
        'sizes'    => $tallas,
        'colors'   => $colores,
        'material' => $materiales,
        'stock'    => $stock,
        'isNew'    => false,
        'discount' => null,
    ];
}
