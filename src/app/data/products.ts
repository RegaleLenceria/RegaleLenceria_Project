// ============================================================
// Base de datos de productos - Conectar con API o BD real
// La interfaz refleja la estructura de la tabla `inventario`
// del backend MySQL (campo fotos viene de JOIN con tabla fotos)
// ============================================================

export interface Product {
  id: string;
  name: string;          // inventario.nombre_prenda
  price: string;         // inventario.precio_base (en Bolivianos)
  sku: string;           // inventario.codigo
  category: string;      // inventario.categoria
  subcategory: string;   // inventario.subcategoria
  brand?: string;        // inventario.marca_prenda
  gender?: string;       // inventario.genero (mujer | hombre)
  material: string[];
  colors: string[];
  sizes: string[];
  description: {
    short: string;       // inventario.descripcion_corta
    long: string;        // inventario.descripcion_larga
    features: string[];
  };
  images: string[];      // fotos.ruta (rutas relativas del upload/)
  stock: number;
  isNew?: boolean;
  discount?: number;
  // Filtros específicos por categoría (igual que en el PHP)
  benefit?: string;      // inventario.beneficio
  fajaType?: string;     // inventario.tipoFaja
  bra?: string;          // inventario.brasier
  panty?: string;        // inventario.panties
  bikini?: string;       // inventario.bikini
  malla?: string;        // inventario.malla
  beachwear?: string;    // inventario.balneario
  pajamaComfy?: string;  // inventario.pijamacomoda
  pajamaSexy?: string;   // inventario.pijamasexy
  lineType?: string;     // inventario.tipolinea
}

// Lista de productos - se llena desde la API / backend
// Ver: control/funciones.php → obtenerProductos()
export const products: Product[] = [];

// ============================================================
// Funciones de utilidad
// ============================================================

export const getProductById = (id: string): Product | undefined => {
  return products.find(p => p.id === id);
};

export const getProductsByCategory = (category: string): Product[] => {
  return products.filter(p => p.category === category);
};

export const getProductsBySubcategory = (category: string, subcategory: string): Product[] => {
  return products.filter(p => p.category === category && p.subcategory === subcategory);
};

export const searchProducts = (query: string): Product[] => {
  const lowerQuery = query.toLowerCase();
  return products.filter(p =>
    p.name.toLowerCase().includes(lowerQuery) ||
    p.sku.toLowerCase().includes(lowerQuery) ||
    p.category.toLowerCase().includes(lowerQuery) ||
    p.subcategory.toLowerCase().includes(lowerQuery) ||
    (p.brand && p.brand.toLowerCase().includes(lowerQuery))
  );
};

export const getUniqueValues = (category: string, field: keyof Product): string[] => {
  const categoryProducts = getProductsByCategory(category);
  const values = new Set<string>();

  categoryProducts.forEach(product => {
    const value = product[field];
    if (Array.isArray(value)) {
      value.forEach(v => values.add(v));
    } else if (typeof value === 'string') {
      values.add(value);
    }
  });

  return Array.from(values).sort();
};
