import { motion } from 'motion/react';
import { Search, Filter, X } from 'lucide-react';
import { ProductCard } from './ProductCard';
import { useState } from 'react';

interface SearchResultsProps {
  query: string;
  onClearSearch: () => void;
  onProductClick?: () => void;
}

// Mock data para resultados de búsqueda
const allProducts = [
  { name: 'Conjunto Victoria', price: '1111.00', sku: 'LS-2024-123', category: 'Brasieres' },
  { name: 'Bikini Paradise', price: '1043.00', sku: 'LS-2024-003', category: 'Trajes de Baño' },
  { name: 'Faja Reductora Premium', price: '1391.00', sku: 'LS-2024-006', category: 'Fajas' },
  { name: 'Body Elegante', price: '625.00', sku: 'LS-2024-002', category: 'Courset/Body' },
  { name: 'Conjunto Sofía', price: '903.00', sku: 'LS-2024-001', category: 'Brasieres' },
  { name: 'Pijama Seda', price: '1251.00', sku: 'LS-2024-004', category: 'Pijamas' },
  { name: 'Conjunto Daniela', price: '973.00', sku: 'LS-2024-005', category: 'Brasieres' },
  { name: 'Courset Clásico', price: '1182.00', sku: 'LS-2024-007', category: 'Courset/Body' },
  { name: 'Bralette Encaje', price: '556.00', sku: 'LS-2024-008', category: 'Brasieres' },
  { name: 'Panty Brasilera', price: '319.00', sku: 'LS-2024-009', category: 'Panties' },
  { name: 'Malla Entera Victoria', price: '1320.00', sku: 'LS-2024-010', category: 'Trajes de Baño' },
  { name: 'Faja Post Parto', price: '1738.00', sku: 'LS-2024-011', category: 'Fajas' },
];

export function SearchResults({ query, onClearSearch, onProductClick }: SearchResultsProps) {
  const [selectedCategory, setSelectedCategory] = useState<string | null>(null);
  const [sortBy, setSortBy] = useState<'relevance' | 'price-asc' | 'price-desc'>('relevance');

  // Filtrar productos por búsqueda
  const searchResults = allProducts.filter((product) => {
    const searchLower = query.toLowerCase();
    return (
      product.name.toLowerCase().includes(searchLower) ||
      product.sku.toLowerCase().includes(searchLower) ||
      product.category.toLowerCase().includes(searchLower)
    );
  });

  // Filtrar por categoría seleccionada
  const filteredResults = selectedCategory
    ? searchResults.filter((p) => p.category === selectedCategory)
    : searchResults;

  // Ordenar
  const sortedResults = [...filteredResults].sort((a, b) => {
    if (sortBy === 'price-asc') {
      return parseInt(a.price.replace(/[$.]/g, '')) - parseInt(b.price.replace(/[$.]/g, ''));
    } else if (sortBy === 'price-desc') {
      return parseInt(b.price.replace(/[$.]/g, '')) - parseInt(a.price.replace(/[$.]/g, ''));
    }
    return 0; // relevance
  });

  // Obtener categorías únicas de los resultados
  const categories = Array.from(new Set(searchResults.map((p) => p.category)));

  return (
    <div className="min-h-screen bg-background py-8 md:py-12">
      <div className="container mx-auto px-4">
        {/* Header de búsqueda */}
        <div className="mb-8">
          <div className="flex items-center justify-between mb-4">
            <div>
              <h1 className="text-2xl md:text-3xl uppercase tracking-widest font-light mb-2">Resultados de búsqueda</h1>
              <p className="text-muted-foreground text-sm">
                {sortedResults.length} resultados para "{query}"
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

          {/* Filtros y ordenamiento */}
          <div className="flex flex-col md:flex-row gap-4 items-start md:items-center justify-between border-t border-b border-border py-4">
            {/* Categorías */}
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

            {/* Ordenar */}
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
        </div>

        {/* Resultados */}
        {sortedResults.length > 0 ? (
          <div className="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8">
            {sortedResults.map((product, index) => (
              <motion.div
                key={index}
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.4, delay: index * 0.05 }}
              >
                <ProductCard {...product} imageIndex={index} onClick={onProductClick} />
              </motion.div>
            ))}
          </div>
        ) : (
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            className="text-center py-20"
          >
            <Search className="w-16 h-16 text-muted-foreground mx-auto mb-4 opacity-30" />
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
