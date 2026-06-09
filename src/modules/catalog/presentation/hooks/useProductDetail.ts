import { useState, useEffect } from 'react';
import { Product } from '../../domain/entities/Product';
import { GetProductDetail } from '../../domain/usecases/GetProductDetail';
import { productRepository } from '../../infrastructure/repositories/HttpProductRepository';

const getProductDetailUseCase = new GetProductDetail(productRepository);

export function useProductDetail(id?: string | number) {
  const [product, setProduct] = useState<Product | null>(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<Error | null>(null);

  useEffect(() => {
    if (!id) {
      setProduct(null);
      return;
    }

    let isMounted = true;
    setLoading(true);
    setError(null);

    getProductDetailUseCase
      .execute(id)
      .then((data) => {
        if (isMounted) {
          setProduct(data);
        }
      })
      .catch((err) => {
        if (isMounted) {
          console.error(err);
          setError(err instanceof Error ? err : new Error('Unknown error loading product details'));
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
  }, [id]);

  return { product, loading, error };
}
