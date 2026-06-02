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
                i.marca_prenda, i.categoria, i.subcategoria,
                i.descripcion_corta, i.descripcion_larga,
                i.beneficio, i.tipoFaja, i.brasier, i.panties,
                i.bikini, i.malla, i.balneario, i.pijamacomoda,
                i.pijamasexy, i.tipolinea, i.estado,
                GROUP_CONCAT(DISTINCT f.ruta ORDER BY f.id) AS fotos
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
    $tallasRes = $db->prepare("SELECT DISTINCT talla FROM tallas WHERE id_prenda = ? ORDER BY talla");
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

    echo json_encode(formatProduct($row, $tallas, $colores));
    exit;
}

// ── Lista de productos ────────────────────────────────────────────────
$limit  = min((int)($_GET['limit'] ?? 12), 48);
$page   = max((int)($_GET['page']  ?? 1), 1);
$offset = ($page - 1) * $limit;

$conditions = ["i.estado != 'no'"];
$params     = [];
$types      = '';

$filterMap = [
    'g'             => 'i.genero',
    'c'             => 'i.categoria',
    'm'             => 'i.marca_prenda',
    'sub'           => 'i.subcategoria',
    'b'             => 'i.beneficio',
    'bra'           => 'i.brasier',
    'pan'           => 'i.panties',
    'bi'            => 'i.bikini',
    'ma'            => 'i.malla',
    'bal'           => 'i.balneario',
    'pijamacomoda'  => 'i.pijamacomoda',
    'pijamasexy'    => 'i.pijamasexy',
    'tipolinea'     => 'i.tipolinea',
];

foreach ($filterMap as $param => $col) {
    if (!empty($_GET[$param])) {
        $conditions[] = "$col = ?";
        $params[]     = $_GET[$param];
        $types       .= 's';
    }
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
            i.marca_prenda, i.categoria, i.subcategoria,
            i.descripcion_corta, i.estado,
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

$products = array_map(fn($r) => formatProduct($r), $rows);

echo json_encode([
    'data'       => $products,
    'total'      => $total,
    'page'       => $page,
    'limit'      => $limit,
    'totalPages' => (int)ceil($total / $limit),
]);

// ── Helper ────────────────────────────────────────────────────────────
function formatProduct(array $row, array $tallas = [], array $colores = []): array {
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
        'stock'    => 1, // Se puede sumar de tabla stock si se requiere
        'isNew'    => false,
        'discount' => null,
    ];
}
