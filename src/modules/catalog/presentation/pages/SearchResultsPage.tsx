import { motion } from 'motion/react';
import { Search as SearchIcon, Filter, X } from 'lucide-react';
import { ProductCard } from '../components/ProductCard';
import { useState, useMemo } from 'react';
import { useProducts } from '../hooks/useProducts';
import { Product } from '../../domain/entities/Product';

interface SearchResultsProps {
  query: string;
  onClearSearch: () => void;
  onProductClick?: (product: Product) => void;
}

export function SearchResultsPage({ query, onClearSearch, onProductClick }: SearchResultsProps) {
  const [selectedCategory, setSelectedCategory] = useState<string | null>(null);
  const [sortBy, setSortBy] = useState<'relevance' | 'price-asc' | 'price-desc'>('relevance');

  // Load actual search results from the PHP API repository using the useProducts hook
  const { products, loading } = useProducts({ q: query, limit: 50 });

  // Filter by selected category locally
  const filteredResults = useMemo(() => {
    return selectedCategory
      ? products.filter((p) => p.category === selectedCategory)
      : products;
  }, [products, selectedCategory]);

  // Sort locally (matching the backend capabilities)
  const sortedResults = useMemo(() => {
    return [...filteredResults].sort((a, b) => {
      if (sortBy === 'price-asc') {
        return parseFloat(a.price) - parseFloat(b.price);
      } else if (sortBy === 'price-desc') {
        return parseFloat(b.price) - parseFloat(a.price);
      }
      return 0; // relevance
    });
  }, [filteredResults, sortBy]);

  // Get unique categories list from results
  const categories = useMemo(() => {
    return Array.from(new Set(products.map((p) => p.category)));
  }, [products]);

  return (
    <div className="min-h-screen bg-background py-8 md:py-12">
      <div className="container mx-auto px-4">
        {/* Search Header */}
        <div className="mb-8">
          <div className="flex items-center justify-between mb-4">
            <div>
              <h1 className="text-2xl md:text-3xl uppercase tracking-widest font-light mb-2">Resultados de búsqueda</h1>
              <p className="text-muted-foreground text-sm">
                {loading ? 'Buscando...' : `${sortedResults.length} resultados para "${query}"`}
              </p>
            </div>
            <button
              onClick={onClearSearch}
              className="p-3 hover:bg-muted transition-all"
              aria-label="Cerrar búsqueda"
            >
              <X className="w-5 h-5" />
            </button>
          </div>

          {/* Filters and Sorting */}
          {!loading && products.length > 0 && (
            <div className="flex flex-col md:flex-row gap-4 items-start md:items-center justify-between border-t border-b border-border py-4">
              {/* Category tags */}
              <div className="flex flex-wrap gap-2">
                <button
                  onClick={() => setSelectedCategory(null)}
                  className={`px-4 py-2 text-xs uppercase tracking-wider transition-all ${
                    selectedCategory === null
                      ? 'bg-primary text-primary-foreground'
                      : 'bg-muted/30 hover:bg-muted/50'
                  }`}
                >
                  Todas
                </button>
                {categories.map((category) => (
                  <button
                    key={category}
                    onClick={() => setSelectedCategory(category)}
                    className={`px-4 py-2 text-xs uppercase tracking-wider transition-all ${
                      selectedCategory === category
                        ? 'bg-primary text-primary-foreground'
                        : 'bg-muted/30 hover:bg-muted/50'
                    }`}
                  >
                    {category}
                  </button>
                ))}
              </div>

              {/* Sort selector */}
              <div className="flex items-center gap-2">
                <Filter className="w-4 h-4 text-muted-foreground" />
                <select
                  value={sortBy}
                  onChange={(e) => setSortBy(e.target.value as any)}
                  className="px-4 py-2 bg-background border border-border focus:outline-none focus:border-primary text-xs uppercase tracking-wider"
                >
                  <option value="relevance">Más relevante</option>
                  <option value="price-asc">Precio: menor a mayor</option>
                  <option value="price-desc">Precio: mayor a menor</option>
                </select>
              </div>
            </div>
          )}
        </div>

        {/* Results view */}
        {loading ? (
          <div className="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8">
            {Array.from({ length: 4 }).map((_, i) => (
              <div key={i} className="animate-pulse">
                <div className="aspect-[3/4] rounded-2xl bg-muted mb-4" />
                <div className="h-4 bg-muted rounded mb-2" />
                <div className="h-3 bg-muted/60 rounded w-2/3 mx-auto" />
              </div>
            ))}
          </div>
        ) : sortedResults.length > 0 ? (
          <div className="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8">
            {sortedResults.map((product, index) => (
              <motion.div
                key={product.id}
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.4, delay: index * 0.05 }}
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
        ) : (
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            className="text-center py-20"
          >
            <SearchIcon className="w-16 h-16 text-muted-foreground mx-auto mb-4 opacity-30" />
            <h3 className="text-lg uppercase tracking-widest mb-2 font-light">No se encontraron resultados</h3>
            <p className="text-muted-foreground mb-6 text-sm">
              Intenta con otros términos de búsqueda
            </p>
            <button
              onClick={onClearSearch}
              className="bg-primary text-primary-foreground px-8 py-3 uppercase tracking-wider text-sm hover:bg-opacity-90 transition-all"
            >
              Volver al inicio
            </button>
          </motion.div>
        )}
      </div>
    </div>
  );
}
