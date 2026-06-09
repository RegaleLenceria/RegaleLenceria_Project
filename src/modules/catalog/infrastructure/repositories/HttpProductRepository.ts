import { Product } from '../../domain/entities/Product';
import { ProductFilters, ProductRepository, ProductsResponse } from '../../domain/repositories/ProductRepository';
import { ApiProduct } from '../models/ApiProduct';
import { ProductMapper } from '../mappers/ProductMapper';

export const API_BASE = '/api';

export class HttpProductRepository implements ProductRepository {
  private buildQuery(filters: ProductFilters): string {
    const params = new URLSearchParams();
    (Object.entries(filters) as [string, unknown][]).forEach(([key, val]) => {
      if (val !== undefined && val !== null && val !== '') {
        params.set(key, String(val));
      }
    });
    return params.toString() ? `?${params.toString()}` : '';
  }

  async getProducts(filters: ProductFilters = {}): Promise<ProductsResponse> {
    const url = `${API_BASE}/products.php${this.buildQuery(filters)}`;
    const res = await fetch(url);

    if (!res.ok) {
      throw new Error(`API error ${res.status}: ${res.statusText}`);
    }

    const json = await res.json();
    return {
      data: (json.data || []).map((p: ApiProduct) => ProductMapper.toDomain(p)),
      total: json.total || 0,
      page: json.page || 1,
      limit: json.limit || 12,
      totalPages: json.totalPages || 1,
    };
  }

  async getProductDetail(id: string | number): Promise<Product> {
    const url = `${API_BASE}/products.php?id=${id}`;
    const res = await fetch(url);

    if (!res.ok) {
      throw new Error(`API error ${res.status}: ${res.statusText}`);
    }

    const apiProduct: ApiProduct = await res.json();
    return ProductMapper.toDomain(apiProduct);
  }

  async getFeaturedProducts(limit = 8): Promise<Product[]> {
    const result = await this.getProducts({ featured: true, limit });
    return result.data;
  }
}
export const productRepository = new HttpProductRepository();
