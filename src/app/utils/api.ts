/**
 * src/app/utils/api.ts
 * Cliente para comunicarse con la API PHP (api/products.php)
 *
 * En desarrollo (Vite :5173) usa el proxy configurado en vite.config.ts
 * En producción (Docker Nginx) la API sirve desde /api/
 */

export const API_BASE = '/api';

// ─── Tipos ────────────────────────────────────────────────────────────

export interface ApiProduct {
  id: string;
  name: string;
  sku: string;
  price: string;
  category: string;
  subcategory: string;
  brand: string;
  gender: string;
  description: {
    short: string;
    long: string;
    features: string[];
  };
  images: string[];
  sizes: string[];
  colors: ApiColor[];
  stock: number;
  isNew: boolean;
  discount: number | null;
}

export interface ApiColor {
  codigo_color: string;
  color_hex: string;
  descripcion: string;
  tipo_color: 'entero' | 'estampado';
  img_estampado: string;
}

export interface ProductsResponse {
  data: ApiProduct[];
  total: number;
  page: number;
  limit: number;
  totalPages: number;
}

// ─── Filtros ──────────────────────────────────────────────────────────

export interface ProductFilters {
  page?: number;
  limit?: number;
  c?: string;        // categoría
  sub?: string;      // subcategoría
  g?: string;        // género
  m?: string;        // marca
  bra?: string;      // tipo brasier
  pan?: string;      // tipo panty
  bi?: string;       // bikini
  ma?: string;       // malla
  bal?: string;      // balneario
  pijamacomoda?: string;
  pijamasexy?: string;
  b?: string;        // beneficio
  tipolinea?: string;
  q?: string;        // búsqueda libre
  featured?: boolean;
}

// ─── Funciones de fetch ───────────────────────────────────────────────

function buildQuery(filters: ProductFilters): string {
  const params = new URLSearchParams();
  (Object.entries(filters) as [string, unknown][]).forEach(([key, val]) => {
    if (val !== undefined && val !== null && val !== '') {
      params.set(key, String(val));
    }
  });
  return params.toString() ? `?${params.toString()}` : '';
}

export async function fetchProducts(filters: ProductFilters = {}): Promise<ProductsResponse> {
  const url = `${API_BASE}/products.php${buildQuery(filters)}`;
  const res = await fetch(url);

  if (!res.ok) {
    throw new Error(`API error ${res.status}: ${res.statusText}`);
  }

  return res.json();
}

export async function fetchProductDetail(id: string | number): Promise<ApiProduct> {
  const url = `${API_BASE}/products.php?id=${id}`;
  const res = await fetch(url);

  if (!res.ok) {
    throw new Error(`API error ${res.status}: ${res.statusText}`);
  }

  return res.json();
}

export async function fetchFeaturedProducts(limit = 8): Promise<ApiProduct[]> {
  const result = await fetchProducts({ featured: true, limit });
  return result.data;
}
