import { motion, AnimatePresence } from 'motion/react';
import { X, Check, ChevronRight, ChevronLeft } from 'lucide-react';
import { useState } from 'react';

interface MobileFiltersDrawerProps {
  isOpen: boolean;
  onClose: () => void;
  filters: {
    subcategories: string[];
    materials: string[];
    colors: string[];
    sizes: string[];
  };
  availableFilters: {
    subcategories: string[];
    materials: string[];
    colors: string[];
    sizes: string[];
  };
  sortBy?: string;
  onSortChange?: (sortBy: string) => void;
  totalProducts?: number;
  onToggleFilter: (filterType: string, value: string) => void;
  onClearFilter: (filterType: string) => void;
  onClearAll: () => void;
  onApply: () => void;
}

type FilterCategory = 'main' | 'subcategories' | 'materials' | 'colors' | 'sizes' | 'sort';

export function MobileFiltersDrawer({
  isOpen,
  onClose,
  filters,
  availableFilters,
  sortBy = 'newest',
  onSortChange,
  totalProducts = 0,
  onToggleFilter,
  onClearFilter,
  onClearAll,
  onApply,
}: MobileFiltersDrawerProps) {
  const [activeView, setActiveView] = useState<FilterCategory>('main');

  const activeFiltersCount =
    filters.subcategories.length +
    filters.materials.length +
    filters.colors.length +
    filters.sizes.length;

  const filterCategories = [
    { id: 'sort', label: 'Ordenar', count: 0 },
    { id: 'sizes', label: 'Talla', count: filters.sizes.length },
    { id: 'subcategories', label: 'Estilo', count: filters.subcategories.length },
    { id: 'colors', label: 'Color', count: filters.colors.length },
    { id: 'materials', label: 'Material', count: filters.materials.length },
  ];

  const sortOptions = [
    { value: 'newest', label: 'Más recientes' },
    { value: 'name', label: 'Nombre A-Z' },
    { value: 'price-asc', label: 'Precio: menor a mayor' },
    { value: 'price-desc', label: 'Precio: mayor a menor' },
  ];

  return (
    <>
      {/* Backdrop */}
      <AnimatePresence>
        {isOpen && (
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            transition={{ duration: 0.3 }}
            className="fixed inset-0 bg-black/50 backdrop-blur-sm z-50"
            onClick={onClose}
          />
        )}
      </AnimatePresence>

      {/* Drawer */}
      <AnimatePresence>
        {isOpen && (
          <motion.div
            initial={{ x: '100%' }}
            animate={{ x: 0 }}
            exit={{ x: '100%' }}
            transition={{ duration: 0.3, ease: [0.4, 0, 0.2, 1] }}
            className="fixed top-0 right-0 bottom-0 bg-background z-50 shadow-2xl w-full max-w-sm"
          >
            {/* Main View */}
            <AnimatePresence mode="wait">
              {activeView === 'main' && (
                <motion.div
                  key="main"
                  initial={{ opacity: 0, x: -20 }}
                  animate={{ opacity: 1, x: 0 }}
                  exit={{ opacity: 0, x: -20 }}
                  transition={{ duration: 0.2 }}
                  className="h-full flex flex-col"
                >
                  {/* Header */}
                  <div className="border-b border-border px-6 py-4">
                    <div className="flex items-center justify-between">
                      <h2 className="text-lg">Filtrar y ordenar</h2>
                      <button
                        onClick={onClose}
                        className="p-2 hover:bg-muted rounded-full transition-all"
                        aria-label="Cerrar"
                      >
                        <X className="w-5 h-5" />
                      </button>
                    </div>
                  </div>

                  {/* Filter Categories List */}
                  <div className="flex-1 overflow-y-auto">
                    {filterCategories.map((category) => (
                      <button
                        key={category.id}
                        onClick={() => setActiveView(category.id as FilterCategory)}
                        className="w-full px-6 py-4 border-b border-border flex items-center justify-between hover:bg-muted/50 transition-colors"
                      >
                        <span className="text-sm">{category.label}</span>
                        <div className="flex items-center gap-2">
                          {category.count > 0 && (
                            <span className="text-xs text-muted-foreground">
                              ({category.count})
                            </span>
                          )}
                          <ChevronRight className="w-4 h-4 text-muted-foreground" />
                        </div>
                      </button>
                    ))}
                  </div>

                  {/* Footer Button */}
                  <div className="border-t border-border px-6 py-4">
                    {activeFiltersCount > 0 && (
                      <button
                        onClick={onClearAll}
                        className="w-full mb-3 text-sm text-primary hover:underline"
                      >
                        Limpiar filtros
                      </button>
                    )}
                    <button
                      onClick={() => {
                        onApply();
                        onClose();
                      }}
                      className="w-full bg-primary text-primary-foreground py-3 uppercase tracking-wide text-sm font-medium hover:shadow-lg transition-all"
                    >
                      Ver resultados
                    </button>
                  </div>
                </motion.div>
              )}

              {/* Sort View */}
              {activeView === 'sort' && (
                <motion.div
                  key="sort"
                  initial={{ opacity: 0, x: 20 }}
                  animate={{ opacity: 1, x: 0 }}
                  exit={{ opacity: 0, x: 20 }}
                  transition={{ duration: 0.2 }}
                  className="h-full flex flex-col"
                >
                  <div className="border-b border-border px-6 py-4">
                    <div className="flex items-center gap-3">
                      <button
                        onClick={() => setActiveView('main')}
                        className="p-2 hover:bg-muted rounded-full transition-all"
                      >
                        <ChevronLeft className="w-5 h-5" />
                      </button>
                      <h2 className="text-lg">Ordenar</h2>
                    </div>
                  </div>

                  <div className="flex-1 overflow-y-auto">
                    {sortOptions.map((option) => (
                      <button
                        key={option.value}
                        onClick={() => {
                          onSortChange?.(option.value);
                          setActiveView('main');
                        }}
                        className="w-full px-6 py-4 border-b border-border flex items-center justify-between hover:bg-muted/50 transition-colors"
                      >
                        <span className="text-sm">{option.label}</span>
                        {sortBy === option.value && (
                          <Check className="w-5 h-5 text-primary" />
                        )}
                      </button>
                    ))}
                  </div>
                </motion.div>
              )}

              {/* Sizes View */}
              {activeView === 'sizes' && (
                <motion.div
                  key="sizes"
                  initial={{ opacity: 0, x: 20 }}
                  animate={{ opacity: 1, x: 0 }}
                  exit={{ opacity: 0, x: 20 }}
                  transition={{ duration: 0.2 }}
                  className="h-full flex flex-col"
                >
                  <div className="border-b border-border px-6 py-4">
                    <div className="flex items-center gap-3">
                      <button
                        onClick={() => setActiveView('main')}
                        className="p-2 hover:bg-muted rounded-full transition-all"
                      >
                        <ChevronLeft className="w-5 h-5" />
                      </button>
                      <h2 className="text-lg">Talla</h2>
                    </div>
                  </div>

                  <div className="flex-1 overflow-y-auto px-6 py-4">
                    <div className="flex flex-wrap gap-2">
                      {availableFilters.sizes.map((size) => {
                        const isSelected = filters.sizes.includes(size);
                        return (
                          <button
                            key={size}
                            onClick={() => onToggleFilter('sizes', size)}
                            className={`px-4 py-2 text-sm border transition-all min-w-[3.5rem] ${
                              isSelected
                                ? 'bg-primary text-primary-foreground border-primary'
                                : 'border-border hover:border-primary'
                            }`}
                          >
                            {size}
                          </button>
                        );
                      })}
                    </div>
                  </div>
                </motion.div>
              )}

              {/* Subcategories View */}
              {activeView === 'subcategories' && (
                <motion.div
                  key="subcategories"
                  initial={{ opacity: 0, x: 20 }}
                  animate={{ opacity: 1, x: 0 }}
                  exit={{ opacity: 0, x: 20 }}
                  transition={{ duration: 0.2 }}
                  className="h-full flex flex-col"
                >
                  <div className="border-b border-border px-6 py-4">
                    <div className="flex items-center gap-3">
                      <button
                        onClick={() => setActiveView('main')}
                        className="p-2 hover:bg-muted rounded-full transition-all"
                      >
                        <ChevronLeft className="w-5 h-5" />
                      </button>
                      <h2 className="text-lg">Estilo</h2>
                    </div>
                  </div>

                  <div className="flex-1 overflow-y-auto">
                    {availableFilters.subcategories.map((type) => {
                      const isSelected = filters.subcategories.includes(type);
                      return (
                        <button
                          key={type}
                          onClick={() => onToggleFilter('subcategories', type)}
                          className="w-full px-6 py-4 border-b border-border flex items-center justify-between hover:bg-muted/50 transition-colors"
                        >
                          <span className="text-sm">{type}</span>
                          {isSelected && <Check className="w-5 h-5 text-primary" />}
                        </button>
                      );
                    })}
                  </div>
                </motion.div>
              )}

              {/* Colors View */}
              {activeView === 'colors' && (
                <motion.div
                  key="colors"
                  initial={{ opacity: 0, x: 20 }}
                  animate={{ opacity: 1, x: 0 }}
                  exit={{ opacity: 0, x: 20 }}
                  transition={{ duration: 0.2 }}
                  className="h-full flex flex-col"
                >
                  <div className="border-b border-border px-6 py-4">
                    <div className="flex items-center gap-3">
                      <button
                        onClick={() => setActiveView('main')}
                        className="p-2 hover:bg-muted rounded-full transition-all"
                      >
                        <ChevronLeft className="w-5 h-5" />
                      </button>
                      <h2 className="text-lg">Color</h2>
                    </div>
                  </div>

                  <div className="flex-1 overflow-y-auto">
                    {availableFilters.colors.map((color) => {
                      const isSelected = filters.colors.includes(color);
                      return (
                        <button
                          key={color}
                          onClick={() => onToggleFilter('colors', color)}
                          className="w-full px-6 py-4 border-b border-border flex items-center justify-between hover:bg-muted/50 transition-colors"
                        >
                          <span className="text-sm">{color}</span>
                          {isSelected && <Check className="w-5 h-5 text-primary" />}
                        </button>
                      );
                    })}
                  </div>
                </motion.div>
              )}

              {/* Materials View */}
              {activeView === 'materials' && (
                <motion.div
                  key="materials"
                  initial={{ opacity: 0, x: 20 }}
                  animate={{ opacity: 1, x: 0 }}
                  exit={{ opacity: 0, x: 20 }}
                  transition={{ duration: 0.2 }}
                  className="h-full flex flex-col"
                >
                  <div className="border-b border-border px-6 py-4">
                    <div className="flex items-center gap-3">
                      <button
                        onClick={() => setActiveView('main')}
                        className="p-2 hover:bg-muted rounded-full transition-all"
                      >
                        <ChevronLeft className="w-5 h-5" />
                      </button>
                      <h2 className="text-lg">Material</h2>
                    </div>
                  </div>

                  <div className="flex-1 overflow-y-auto">
                    {availableFilters.materials.map((material) => {
                      const isSelected = filters.materials.includes(material);
                      return (
                        <button
                          key={material}
                          onClick={() => onToggleFilter('materials', material)}
                          className="w-full px-6 py-4 border-b border-border flex items-center justify-between hover:bg-muted/50 transition-colors"
                        >
                          <span className="text-sm">{material}</span>
                          {isSelected && <Check className="w-5 h-5 text-primary" />}
                        </button>
                      );
                    })}
                  </div>
                </motion.div>
              )}
            </AnimatePresence>
          </motion.div>
        )}
      </AnimatePresence>
    </>
  );
}
