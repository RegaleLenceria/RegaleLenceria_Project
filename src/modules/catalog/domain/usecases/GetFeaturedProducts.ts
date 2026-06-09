import { Product } from '../entities/Product';
import { ProductRepository } from '../repositories/ProductRepository';

export class GetFeaturedProducts {
  constructor(private productRepository: ProductRepository) {}

  async execute(limit?: number): Promise<Product[]> {
    return this.productRepository.getFeaturedProducts(limit);
  }
}
