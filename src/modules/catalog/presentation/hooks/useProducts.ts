import { useState, useEffect, useCallback } from 'react';
import { Product } from '../../domain/entities/Product';
import { ProductFilters } from '../../domain/repositories/ProductRepository';
import { GetProducts } from '../../domain/usecases/GetProducts';
import { productRepository } from '../../infrastructure/repositories/HttpProductRepository';

const getProductsUseCase = new GetProducts(productRepository);

export function useProducts(initialFilters: ProductFilters = {}) {
  const [products, setProducts] = useState<Product[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<Error | null>(null);
  const [total, setTotal] = useState(0);
  const [totalPages, setTotalPages] = useState(1);
  const [filters, setFilters] = useState<ProductFilters>(initialFilters);

  const fetchProducts = useCallback(async (currentFilters: ProductFilters) => {
    setLoading(true);
    setError(null);
    try {
      const response = await getProductsUseCase.execute(currentFilters);
      setProducts(response.data);
      setTotal(response.total);
      setTotalPages(response.totalPages);
    } catch (err) {
      console.error(err);
      setError(err instanceof Error ? err : new Error('Unknown error fetching products'));
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => {
    fetchProducts(filters);
  }, [filters, fetchProducts]);

  const updateFilters = useCallback((newFilters: Partial<ProductFilters>) => {
    setFilters((prev) => ({ ...prev, ...newFilters }));
  }, []);

  const setPage = useCallback((page: number) => {
    setFilters((prev) => ({ ...prev, page }));
  }, []);

  return {
    products,
    loading,
    error,
    total,
    totalPages,
    filters,
    updateFilters,
    setPage,
    refetch: () => fetchProducts(filters),
  };
}
