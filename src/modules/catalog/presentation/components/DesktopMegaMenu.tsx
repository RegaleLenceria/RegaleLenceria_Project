import { useState } from 'react';
import { motion, AnimatePresence } from 'motion/react';
import { ChevronDown, ChevronRight } from 'lucide-react';
import { categories } from '../../domain/entities/Category';

interface DesktopMegaMenuProps {
  onCategoryClick?: (category: string, subcategory?: string) => void;
}

export function DesktopMegaMenu({ onCategoryClick }: DesktopMegaMenuProps) {
  const [isOpen, setIsOpen] = useState(false);
  const [activeCategory, setActiveCategory] = useState<number | null>(null);

  const handleCategoryClick = (categoryName: string, subcategory?: string) => {
    if (onCategoryClick) {
      onCategoryClick(categoryName, subcategory);
      setIsOpen(false);
      setActiveCategory(null);
    }
  };

  return (
    <div
      className="relative"
      onMouseEnter={() => setIsOpen(true)}
      onMouseLeave={() => {
        setIsOpen(false);
        setActiveCategory(null);
      }}
    >
      {/* Trigger Button */}
      <button className="flex items-center gap-1 hover:text-primary transition-colors uppercase tracking-wide">
        <span>Categorías</span>
        <ChevronDown className={`w-4 h-4 transition-transform duration-200 ${isOpen ? 'rotate-180' : ''}`} />
      </button>

      {/* Mega Menu Dropdown */}
      <AnimatePresence>
        {isOpen && (
          <motion.div
            initial={{ opacity: 0, y: -10 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0, y: -10 }}
            transition={{ duration: 0.2 }}
            className="absolute top-full mt-2 bg-background/98 backdrop-blur-md border border-border shadow-xl overflow-hidden z-50"
            style={{ width: '900px', left: '-200px' }}
          >
            <div className="flex">
              {/* Categories List - Left Side */}
              <div className="w-1/3 bg-muted/10 p-6 border-r border-border">
                <h3 className="text-xs uppercase tracking-widest text-muted-foreground mb-4 font-medium">Categorías</h3>
                <nav className="space-y-1">
                  {categories.map((category, index) => (
                    <button
                      key={index}
                      onMouseEnter={() => setActiveCategory(index)}
                      onClick={() => handleCategoryClick(category.name)}
                      className={`w-full text-left px-3 py-2.5 transition-all text-sm uppercase tracking-wide ${
                        activeCategory === index
                          ? 'bg-primary text-primary-foreground'
                          : 'hover:bg-muted/30'
                      }`}
                    >
                      {category.name}
                    </button>
                  ))}
                </nav>
              </div>

              {/* Subcategories - Right Side */}
              <div className="w-2/3 p-8 min-h-[400px] max-h-[70vh] overflow-y-auto custom-scrollbar">
                <AnimatePresence mode="wait">
                  {activeCategory !== null ? (
                    <motion.div
                      key={activeCategory}
                      initial={{ opacity: 0 }}
                      animate={{ opacity: 1 }}
                      exit={{ opacity: 0 }}
                      transition={{ duration: 0.2 }}
                    >
                      <h2 className="text-xl uppercase tracking-widest mb-6 font-light">{categories[activeCategory].name}</h2>

                      {categories[activeCategory].subcategories.length > 0 ? (
                        <div className={`grid gap-3 ${categories[activeCategory].subcategories.length > 8 ? 'grid-cols-2' : 'grid-cols-1'}`}>
                          {categories[activeCategory].subcategories.map((sub, subIndex) => {
                            const isHeader = sub.startsWith('---');

                            if (isHeader) {
                              return (
                                <div
                                  key={subIndex}
                                  className="col-span-full mt-4 first:mt-0"
                                >
                                  <h4 className="text-xs uppercase tracking-widest text-muted-foreground mb-2">
                                    {sub.replace(/---/g, '').trim()}
                                  </h4>
                                </div>
                              );
                            }

                            return (
                              <button
                                key={subIndex}
                                onClick={() => handleCategoryClick(categories[activeCategory].name, sub)}
                                className="w-full text-left text-sm hover:text-primary transition-colors py-1.5 px-2 hover:bg-muted/20"
                              >
                                {sub}
                              </button>
                            );
                          })}
                        </div>
                      ) : (
                        <p className="text-muted-foreground text-sm">No hay subcategorías disponibles</p>
                      )}
                    </motion.div>
                  ) : (
                    <motion.div
                      initial={{ opacity: 0 }}
                      animate={{ opacity: 1 }}
                      className="flex items-center justify-center h-full"
                    >
                      <div className="text-center">
                        <h3 className="text-base uppercase tracking-widest mb-2 font-light">Selecciona una categoría</h3>
                        <p className="text-sm text-muted-foreground">
                          Pasa el cursor sobre una categoría para ver sus opciones
                        </p>
                      </div>
                    </motion.div>
                  )}
                </AnimatePresence>
              </div>
            </div>
          </motion.div>
        )}
      </AnimatePresence>
    </div>
  );
}
