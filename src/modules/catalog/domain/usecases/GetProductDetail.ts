import { Product } from '../entities/Product';
import { ProductRepository } from '../repositories/ProductRepository';

export class GetProductDetail {
  constructor(private productRepository: ProductRepository) {}

  async execute(id: string | number): Promise<Product> {
    if (!id) {
      throw new Error('Product ID is required');
    }
    return this.productRepository.getProductDetail(id);
  }
}
