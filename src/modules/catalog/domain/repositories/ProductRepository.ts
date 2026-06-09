import { Product } from '../entities/Product';

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

export interface ProductsResponse {
  data: Product[];
  total: number;
  page: number;
  limit: number;
  totalPages: number;
}

export interface ProductRepository {
  getProducts(filters?: ProductFilters): Promise<ProductsResponse>;
  getProductDetail(id: string | number): Promise<Product>;
  getFeaturedProducts(limit?: number): Promise<Product[]>;
}
