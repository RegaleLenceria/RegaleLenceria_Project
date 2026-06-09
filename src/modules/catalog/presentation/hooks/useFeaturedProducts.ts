import { useState, useEffect } from 'react';
import { Product } from '../../domain/entities/Product';
import { GetFeaturedProducts } from '../../domain/usecases/GetFeaturedProducts';
import { productRepository } from '../../infrastructure/repositories/HttpProductRepository';

const getFeaturedProductsUseCase = new GetFeaturedProducts(productRepository);

export function useFeaturedProducts(limit = 8) {
  const [products, setProducts] = useState<Product[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<Error | null>(null);

  useEffect(() => {
    let isMounted = true;
    setLoading(true);
    setError(null);

    getFeaturedProductsUseCase
      .execute(limit)
      .then((data) => {
        if (isMounted) {
          setProducts(data);
        }
      })
      .catch((err) => {
        if (isMounted) {
          console.error(err);
          setError(err instanceof Error ? err : new Error('Unknown error loading featured products'));
        }
      })
      .finally(() => {
        if (isMounted) {
          setLoading(false);
        }
      });

    return () => {
      isMounted = false;
    };
  }, [limit]);

  return { products, loading, error };
}
