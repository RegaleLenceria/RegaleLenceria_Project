import { ProductRepository, ProductFilters, ProductsResponse } from '../repositories/ProductRepository';

export class GetProducts {
  constructor(private productRepository: ProductRepository) {}

  async execute(filters: ProductFilters = {}): Promise<ProductsResponse> {
    return this.productRepository.getProducts(filters);
  }
}
