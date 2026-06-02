import { motion } from 'motion/react';
import { ChevronRight, SlidersHorizontal, X, ChevronLeft } from 'lucide-react';
import { ProductCard } from './ProductCard';
import { Breadcrumb } from './Breadcrumb';
import { MobileFiltersDrawer } from './MobileFiltersDrawer';
import { ImageWithFallback } from './ImageWithFallback';
import { useState, useEffect, useCallback } from 'react';
import { fetchProducts, ApiProduct, ProductFilters } from '../utils/api';
import { categories } from '../data/categories';

interface CategoryPageProps {
  categoryName: string;
  subcategory?: string;
  onSubcategoryChange?: (subcategory: string) => void;
  onProductClick?: (product: ApiProduct) => void;
  onHomeClick?: () => void;
}

// Mapeo de nombre de categoría del menú → valor en BD
const CATEGORY_MAP: Record<string, string> = {
  'Brasier': 'ropainterior',
  'Panty': 'ropainterior',
  'Fajas': 'fajas',
  'Deportivos': 'deportiva',
  'Trajes de Baño': 'trajes',
  'Pijamas': 'pijamas',
  'Hombre': 'mujer', // género en BD
  'Accesorios': 'accesorios',
};

// Para Brasier/Panty necesitamos filtrar por subcampo específico
const BRASIER_MAP: Record<string, string> = {
  'Push Up': 'pushup',
  'Bralette': 'bralette',
  'Strapless / Sin Espalda': 'strapple',
  'Escote Profundo': 'escote',
  'Bustier': 'bustier',
  'Top': 'top',
  'Soporte y Reducción': 'soporte',
  'Postoperatorio y Maternas': 'postoperatorio',
  'Control Espalda': 'controlespalda',
  'Señorero': 'senyorero',
};

const PANTY_MAP: Record<string, string> = {
  'Brasilera': 'brasilera-tanga',
  'Cachetero': 'cachetero',
  'Bikini': 'bikini',
  'Boxer': 'boxer',
  'Señorero': 'senyorero',
  'Maternidad': 'maternidad',
  'Pack': 'packs',
};

export function CategoryPage({ categoryName, subcategory, onSubcategoryChange, onProductClick, onHomeClick }: CategoryPageProps) {
  const [products, setProducts] = useState<ApiProduct[]>([]);
  const [loading, setLoading] = useState(true);
  const [total, setTotal] = useState(0);
  const [page, setPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const [showFilters, setShowFilters] = useState(false);
  const [sortBy, setSortBy] = useState<'newest' | 'price-asc' | 'price-desc' | 'name'>('newest');
  const [activeSubcategory, setActiveSubcategory] = useState(subcategory || '');

  const LIMIT = 12;

  // Construir filtros para la API
  const buildFilters = useCallback((): ProductFilters => {
    const filters: ProductFilters = {
      page,
      limit: LIMIT,
      c: CATEGORY_MAP[categoryName] || categoryName.toLowerCase(),
    };

    // Género para Hombre
    if (categoryName === 'Hombre') {
      filters.g = 'hombre';
      delete filters.c;
    }

    // Subcategoría activa
    if (activeSubcategory) {
      if (categoryName === 'Brasier' && BRASIER_MAP[activeSubcategory]) {
        filters.bra = BRASIER_MAP[activeSubcategory];
      } else if (categoryName === 'Panty' && PANTY_MAP[activeSubcategory]) {
        filters.pan = PANTY_MAP[activeSubcategory];
      } else {
        filters.sub = activeSubcategory.toLowerCase().replace(/\s+/g, '');
      }
    }

    return filters;
  }, [categoryName, activeSubcategory, page]);

  // Fetch de productos
  useEffect(() => {
    setLoading(true);
    fetchProducts(buildFilters())
      .then(res => {
        setProducts(res.data);
        setTotal(res.total);
        setTotalPages(res.totalPages);
      })
      .catch(console.error)
      .finally(() => setLoading(false));
  }, [buildFilters]);

  // Reset página al cambiar categoría/subcategoría
  useEffect(() => {
    setPage(1);
    setActiveSubcategory(subcategory || '');
  }, [categoryName, subcategory]);

  const handleSubcategoryClick = (sub: string) => {
    const next = activeSubcategory === sub ? '' : sub;
    setActiveSubcategory(next);
    setPage(1);
    onSubcategoryChange?.(next);
  };

  const clearAllFilters = () => {
    setActiveSubcategory('');
    setPage(1);
    onSubcategoryChange?.('');
  };

  // Ordenar localmente (la API no soporta sort aún)
  const sortedProducts = [...products].sort((a, b) => {
    if (sortBy === 'name') return a.name.localeCompare(b.name);
    if (sortBy === 'price-asc') return parseFloat(a.price) - parseFloat(b.price);
    if (sortBy === 'price-desc') return parseFloat(b.price) - parseFloat(a.price);
    return 0; // newest → por defecto del server
  });

  // Subcategorías del menú para este catálogo
  const categoryData = categories.find(cat => cat.name === categoryName);
  const menuSubcategories = categoryData?.subcategories.filter(s => !s.startsWith('---')) || [];

  const activeFiltersCount = activeSubcategory ? 1 : 0;

  return (
    <div className="min-h-screen bg-background py-8 md:py-12">
      <div className="container mx-auto px-4">
        {/* Breadcrumb */}
        <Breadcrumb
          items={[
            { label: 'Inicio', onClick: onHomeClick },
            { label: 'Tienda', onClick: onHomeClick },
            { label: categoryName, onClick: clearAllFilters },
            ...(activeSubcategory ? [{ label: activeSubcategory }] : []),
          ]}
        />

        {/* Header */}
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.6 }}
          className="mb-8"
        >
          <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
              <h1 className="text-4xl md:text-5xl mb-2">{categoryName}</h1>
              {activeSubcategory && (
                <div className="flex items-center gap-2 text-muted-foreground">
                  <ChevronRight className="w-4 h-4" />
                  <span>{activeSubcategory}</span>
                </div>
              )}
              <p className="text-muted-foreground mt-2">
                {loading ? 'Cargando…' : `${total} producto${total !== 1 ? 's' : ''} disponible${total !== 1 ? 's' : ''}`}
              </p>
            </div>

            <div className="flex items-center gap-3">
              {/* Sort */}
              <select
                value={sortBy}
                onChange={e => setSortBy(e.target.value as typeof sortBy)}
                className="px-3 py-2 border border-border bg-background text-sm rounded"
              >
                <option value="newest">Más recientes</option>
                <option value="price-asc">Precio: menor a mayor</option>
                <option value="price-desc">Precio: mayor a menor</option>
                <option value="name">Nombre A-Z</option>
              </select>

              <button
                onClick={() => setShowFilters(!showFilters)}
                className={`px-4 py-2 flex items-center gap-2 transition-all border text-sm ${
                  showFilters ? 'bg-primary text-primary-foreground border-primary' : 'bg-background border-border'
                }`}
              >
                <SlidersHorizontal className="w-4 h-4" />
                Filtros
                {activeFiltersCount > 0 && (
                  <span className="bg-primary text-primary-foreground text-xs w-5 h-5 rounded-full flex items-center justify-center">
                    {activeFiltersCount}
                  </span>
                )}
              </button>
            </div>
          </div>

          {/* Showcase de subcategorías */}
          <div className="mb-6 overflow-x-auto scrollbar-hide">
            <div className="flex gap-3 pb-2 min-w-max md:min-w-0 md:flex-wrap">
              {menuSubcategories.map((sub, i) => {
                const isActive = activeSubcategory === sub;
                return (
                  <motion.button
                    key={`${sub}-${i}`}
                    initial={{ opacity: 0, y: 10 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.3, delay: i * 0.04 }}
                    onClick={() => handleSubcategoryClick(sub)}
                    className={`flex-shrink-0 px-4 py-2 border text-xs uppercase tracking-wider transition-all ${
                      isActive
                        ? 'bg-primary text-primary-foreground border-primary'
                        : 'bg-background border-border hover:border-primary'
                    }`}
                  >
                    {sub}
                  </motion.button>
                );
              })}
            </div>
          </div>

          {/* Filtros activos */}
          {activeSubcategory && (
            <div className="flex gap-2 mb-4">
              <span className="px-3 py-1 bg-primary/10 text-primary text-sm flex items-center gap-2">
                {activeSubcategory}
                <button onClick={clearAllFilters} className="hover:bg-primary/20 rounded-full p-0.5">
                  <X className="w-3 h-3" />
                </button>
              </span>
            </div>
          )}
        </motion.div>

        {/* Filters drawer (mobile) */}
        <MobileFiltersDrawer
          isOpen={showFilters}
          onClose={() => setShowFilters(false)}
          filters={{ subcategories: activeSubcategory ? [activeSubcategory] : [], materials: [], colors: [], sizes: [], priceRange: [0, 9999] }}
          availableFilters={{ subcategories: menuSubcategories, materials: [], colors: [], sizes: [] }}
          sortBy={sortBy}
          onSortChange={v => setSortBy(v as typeof sortBy)}
          totalProducts={total}
          onToggleFilter={(type, val) => { if (type === 'subcategories') handleSubcategoryClick(val); }}
          onClearFilter={() => clearAllFilters()}
          onClearAll={clearAllFilters}
          onApply={() => setShowFilters(false)}
        />

        {/* Grid de productos */}
        {loading ? (
          <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            {Array.from({ length: LIMIT }).map((_, i) => (
              <div key={i} className="animate-pulse">
                <div className="aspect-[3/4] rounded-2xl bg-muted mb-4" />
                <div className="h-4 bg-muted rounded mb-2" />
                <div className="h-3 bg-muted/60 rounded w-2/3 mx-auto" />
              </div>
            ))}
          </div>
        ) : sortedProducts.length === 0 ? (
          <div className="text-center py-20">
            <p className="text-muted-foreground mb-4">No hay productos disponibles en esta categoría.</p>
            <button
              onClick={clearAllFilters}
              className="px-6 py-2 bg-primary text-primary-foreground rounded-full hover:shadow-lg transition-all"
            >
              Ver todos
            </button>
          </div>
        ) : (
          <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            {sortedProducts.map((product, index) => (
              <motion.div
                key={product.id}
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.4, delay: index * 0.04 }}
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

        {/* Paginación */}
        {totalPages > 1 && (
          <div className="flex items-center justify-center gap-2 mt-12">
            <button
              onClick={() => setPage(p => Math.max(1, p - 1))}
              disabled={page === 1}
              className="p-2 border border-border disabled:opacity-40 hover:border-primary transition-colors"
            >
              <ChevronLeft className="w-4 h-4" />
            </button>
            {Array.from({ length: Math.min(totalPages, 7) }, (_, i) => {
              const p = page <= 4 ? i + 1 : page - 3 + i;
              if (p < 1 || p > totalPages) return null;
              return (
                <button
                  key={p}
                  onClick={() => setPage(p)}
                  className={`w-9 h-9 text-sm border transition-colors ${
                    p === page ? 'bg-primary text-primary-foreground border-primary' : 'border-border hover:border-primary'
                  }`}
                >
                  {p}
                </button>
              );
            })}
            <button
              onClick={() => setPage(p => Math.min(totalPages, p + 1))}
              disabled={page === totalPages}
              className="p-2 border border-border disabled:opacity-40 hover:border-primary transition-colors"
            >
              <ChevronRight className="w-4 h-4" />
            </button>
          </div>
        )}
      </div>

      <style>{`
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
      `}</style>
    </div>
  );
}
