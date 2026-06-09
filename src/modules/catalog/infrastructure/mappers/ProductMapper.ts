import { Product, ProductColor } from '../../domain/entities/Product';
import { ApiProduct, ApiColor } from '../models/ApiProduct';

export class ProductMapper {
  static toDomain(apiProduct: ApiProduct): Product {
    return {
      id: apiProduct.id,
      name: apiProduct.name,
      sku: apiProduct.sku,
      price: apiProduct.price,
      category: apiProduct.category,
      subcategory: apiProduct.subcategory,
      brand: apiProduct.brand || undefined,
      gender: apiProduct.gender || undefined,
      description: {
        short: apiProduct.description?.short || '',
        long: apiProduct.description?.long || '',
        features: apiProduct.description?.features || [],
      },
      images: apiProduct.images || [],
      sizes: apiProduct.sizes || [],
      colors: (apiProduct.colors || []).map(ProductMapper.colorToDomain),
      stock: apiProduct.stock ?? 0,
      isNew: !!apiProduct.isNew,
      discount: apiProduct.discount ?? null,
    };
  }

  static colorToDomain(apiColor: ApiColor | string): ProductColor {
    if (typeof apiColor === 'string') {
      return {
        name: apiColor,
        hex: '#CCCCCC',
        type: 'entero',
      };
    }
    return {
      name: apiColor.descripcion || apiColor.codigo_color,
      hex: apiColor.color_hex || '#CCCCCC',
      type: apiColor.tipo_color || 'entero',
      image: apiColor.img_estampado || undefined,
    };
  }
}
