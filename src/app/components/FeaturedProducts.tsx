import { useState, useEffect } from 'react';
import { motion } from 'motion/react';
import { ProductCard } from './ProductCard';
import { fetchFeaturedProducts, ApiProduct } from '../utils/api';

interface FeaturedProductsProps {
  onProductClick?: (product: ApiProduct) => void;
}

export function FeaturedProducts({ onProductClick }: FeaturedProductsProps) {
  const [products, setProducts] = useState<ApiProduct[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchFeaturedProducts(8)
      .then(setProducts)
      .catch(console.error)
      .finally(() => setLoading(false));
  }, []);

  return (
    <section className="py-12 md:py-16 bg-background">
      <div className="container mx-auto px-4">
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.6 }}
          className="text-center mb-8 md:mb-12"
        >
          <h2 className="text-2xl md:text-3xl font-light tracking-widest uppercase">
            Seleccionados para ti
          </h2>
        </motion.div>

        {loading ? (
          /* Skeleton */
          <div className="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8">
            {Array.from({ length: 8 }).map((_, i) => (
              <div key={i} className="animate-pulse">
                <div className="aspect-[3/4] rounded-2xl bg-muted mb-4" />
                <div className="h-4 bg-muted rounded mb-2" />
                <div className="h-3 bg-muted/60 rounded w-2/3 mx-auto" />
              </div>
            ))}
          </div>
        ) : products.length === 0 ? (
          <p className="text-center text-muted-foreground py-12">
            No hay productos disponibles aún.
          </p>
        ) : (
          <div className="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8">
            {products.map((product, index) => (
              <motion.div
                key={product.id}
                initial={{ opacity: 0, y: 20 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.4, delay: index * 0.06 }}
              >
                <ProductCard
                  name={product.name}
                  price={product.price}
                  sku={product.sku}
                  image={product.images[0]}
                  onClick={() => onProductClick?.(product)}
                />
              </motion.div>
            ))}
          </div>
        )}
      </div>
    </section>
  );
}
