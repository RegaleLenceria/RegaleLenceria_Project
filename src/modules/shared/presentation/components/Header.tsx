import { useState, useEffect } from 'react';
import { Menu, Search, ShoppingCart } from 'lucide-react';
import { motion, AnimatePresence } from 'motion/react';
import logoImg from '../../../../imports/image-10.png';
import { useCart } from '../../../cart/presentation/context/CartContext';
import { categories } from '../../../catalog/domain/entities/Category';

interface HeaderProps {
  onMenuClick: () => void;
  onSearch?: (query: string) => void;
  onLogoClick?: () => void;
  onCategoryClick?: (category: string, subcategory?: string) => void;
  onCartClick?: () => void;
}

export function Header({ onMenuClick, onSearch, onLogoClick, onCategoryClick, onCartClick }: HeaderProps) {
  const [searchExpanded, setSearchExpanded] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');
  const [hoveredCategory, setHoveredCategory] = useState<number | null>(null);
  const [isScrolled, setIsScrolled] = useState(false);
  const { getItemCount } = useCart();
  const cartItemCount = getItemCount();

  useEffect(() => {
    const handleScroll = () => {
      setIsScrolled(window.scrollY > 50);
    };

    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  const handleSearch = (e: React.FormEvent) => {
    e.preventDefault();
    if (searchQuery.trim() && onSearch) {
      onSearch(searchQuery);
    }
  };

  return (
    <header className="sticky top-0 z-40 bg-background/98 backdrop-blur-md border-b border-border/50">
      {/* Mobile Header */}
      <div className="md:hidden">
        <div className="flex items-center justify-between h-14 px-4">
          <button onClick={onMenuClick} className="p-2" aria-label="Open menu">
            <Menu className="w-5 h-5" />
          </button>

          <button onClick={onLogoClick} className="transition-opacity hover:opacity-80">
            <img src={logoImg} alt="Regale Lencería" className="h-8 w-auto" />
          </button>

          <div className="flex items-center gap-2">
            <button onClick={onCartClick} className="relative p-2" aria-label="Carrito">
              <ShoppingCart className="w-5 h-5" />
              {cartItemCount > 0 && (
                <motion.span
                  initial={{ scale: 0 }}
                  animate={{ scale: 1 }}
                  className="absolute -top-1 -right-1 bg-primary text-primary-foreground text-[10px] w-4 h-4 rounded-full flex items-center justify-center"
                >
                  {cartItemCount > 9 ? '9+' : cartItemCount}
                </motion.span>
              )}
            </button>
            <button onClick={() => setSearchExpanded(!searchExpanded)} className="p-2" aria-label="Search">
              <Search className="w-5 h-5" />
            </button>
          </div>
        </div>

        <AnimatePresence>
          {searchExpanded && (
            <motion.div
              initial={{ height: 0, opacity: 0 }}
              animate={{ height: 'auto', opacity: 1 }}
              exit={{ height: 0, opacity: 0 }}
              transition={{ duration: 0.3 }}
              className="overflow-hidden px-4 pb-4"
            >
              <form onSubmit={handleSearch}>
                <div className="flex gap-2">
                  <input
                    type="text"
                    placeholder="Buscar..."
                    value={searchQuery}
                    onChange={(e) => setSearchQuery(e.target.value)}
                    className="flex-1 px-4 py-3 rounded-full bg-input-background border border-border focus:outline-none focus:ring-2 focus:ring-primary"
                    autoFocus
                  />
                  <button
                    type="submit"
                    className="px-6 py-3 bg-primary text-primary-foreground rounded-full"
                  >
                    Buscar
                  </button>
                </div>
              </form>
            </motion.div>
          )}
        </AnimatePresence>
      </div>

      {/* Desktop Header */}
      <div className="hidden md:block">
        <div className="container mx-auto px-4">
          {/* Top Row - Search & Cart */}
          <motion.div
            initial={{ height: 'auto', opacity: 1 }}
            animate={{
              height: isScrolled ? 0 : 'auto',
              opacity: isScrolled ? 0 : 1
            }}
            transition={{ duration: 0.3 }}
            className="overflow-hidden"
          >
            <div className="flex items-center justify-end gap-2 py-1">
              {/* Search Bar */}
              <form onSubmit={handleSearch} className="relative">
                <div className="flex items-center bg-muted/30 rounded-full px-3 py-1 min-w-[200px]">
                  <Search className="w-4 h-4 text-muted-foreground mr-2" />
                  <input
                    type="text"
                    placeholder="Search"
                    value={searchQuery}
                    onChange={(e) => setSearchQuery(e.target.value)}
                    className="bg-transparent border-none outline-none text-sm placeholder:text-muted-foreground flex-1"
                  />
                </div>
              </form>

              {/* Cart Icon */}
              <button onClick={onCartClick} className="relative hover:text-primary transition-colors p-1" aria-label="Carrito">
                <ShoppingCart className="w-5 h-5" />
                {cartItemCount > 0 && (
                  <motion.span
                    initial={{ scale: 0 }}
                    animate={{ scale: 1 }}
                    className="absolute -top-1 -right-1 bg-primary text-primary-foreground text-[10px] w-4 h-4 rounded-full flex items-center justify-center"
                  >
                    {cartItemCount > 9 ? '9+' : cartItemCount}
                  </motion.span>
                )}
              </button>
            </div>
          </motion.div>

          {/* Logo Row */}
          <motion.div
            initial={{ height: 'auto', opacity: 1 }}
            animate={{
              height: isScrolled ? 0 : 'auto',
              opacity: isScrolled ? 0 : 1
            }}
            transition={{ duration: 0.3 }}
            className="overflow-hidden"
          >
            <div className="flex items-center justify-center py-2">
              <button onClick={onLogoClick} className="transition-opacity hover:opacity-80">
                <img src={logoImg} alt="Regale Lencería" className="h-20 w-auto" />
              </button>
            </div>
          </motion.div>

          {/* Navigation Row */}
          <nav className="border-t border-border/50 relative">
            <div className="flex items-center justify-between py-2">
              {/* Left spacer */}
              <div className="flex-1"></div>

              {/* Center - Categories */}
              <div className="flex items-center justify-center gap-6 lg:gap-8">
                {categories.map((category, index) => (
                  <div
                    key={index}
                    onMouseEnter={() => setHoveredCategory(index)}
                    onMouseLeave={() => setHoveredCategory(null)}
                  >
                    <button
                      onClick={() => onCategoryClick?.(category.name)}
                      className="text-xs uppercase tracking-widest hover:text-primary transition-colors py-2"
                    >
                      {category.name}
                    </button>
                  </div>
                ))}
              </div>

              {/* Right - Search & Cart (visible when scrolled) */}
              <div className="flex-1 flex items-center justify-end gap-3">
                <AnimatePresence>
                  {isScrolled && (
                    <motion.div
                      initial={{ opacity: 0, x: 20 }}
                      animate={{ opacity: 1, x: 0 }}
                      exit={{ opacity: 0, x: 20 }}
                      transition={{ duration: 0.3 }}
                      className="flex items-center gap-3"
                    >
                      <button
                        onClick={() => setSearchExpanded(!searchExpanded)}
                        className="hover:text-primary transition-colors p-2"
                        aria-label="Search"
                      >
                        <Search className="w-4 h-4" />
                      </button>
                      <button onClick={onCartClick} className="relative hover:text-primary transition-colors p-2" aria-label="Carrito">
                        <ShoppingCart className="w-4 h-4" />
                        {cartItemCount > 0 && (
                          <span className="absolute -top-1 -right-1 bg-primary text-primary-foreground text-[10px] w-4 h-4 rounded-full flex items-center justify-center">
                            {cartItemCount > 9 ? '9+' : cartItemCount}
                          </span>
                        )}
                      </button>
                    </motion.div>
                  )}
                </AnimatePresence>
              </div>
            </div>

            {/* Mega Menu Dropdown */}
            <AnimatePresence>
              {hoveredCategory !== null && categories[hoveredCategory].subcategories.length > 0 && (
                <motion.div
                  initial={{ opacity: 0, y: -10 }}
                  animate={{ opacity: 1, y: 0 }}
                  exit={{ opacity: 0, y: -10 }}
                  transition={{ duration: 0.2 }}
                  className="absolute left-0 right-0 top-full bg-background/98 backdrop-blur-md border-t border-border shadow-xl"
                  onMouseEnter={() => setHoveredCategory(hoveredCategory)}
                  onMouseLeave={() => setHoveredCategory(null)}
                >
                  <div className="container mx-auto px-4 py-8">
                    <div className="grid grid-cols-4 gap-8">
                      {(() => {
                        const category = categories[hoveredCategory];
                        const sections: { header: string; items: string[] }[] = [];
                        let currentSection: { header: string; items: string[] } | null = null;

                        category.subcategories.forEach((sub) => {
                          const isHeader = sub.startsWith('---');
                          if (isHeader) {
                            if (currentSection) {
                              sections.push(currentSection);
                            }
                            currentSection = {
                              header: sub.replace(/---/g, '').trim(),
                              items: []
                            };
                          } else {
                            if (currentSection) {
                              currentSection.items.push(sub);
                            } else {
                              if (!currentSection) {
                                currentSection = { header: '', items: [] };
                              }
                              currentSection.items.push(sub);
                            }
                          }
                        });

                        if (currentSection) {
                          sections.push(currentSection);
                        }

                        return sections.map((section, sectionIndex) => (
                          <div key={sectionIndex} className="border-r border-border/30 pr-8 last:border-r-0 last:pr-0">
                            {section.header && (
                              <h3 className="text-xs font-semibold uppercase tracking-widest mb-4 text-foreground">
                                {section.header}
                              </h3>
                            )}
                            <div className="space-y-2">
                              {section.items.map((item, itemIndex) => (
                                <button
                                  key={itemIndex}
                                  onClick={() => {
                                    onCategoryClick?.(category.name, item);
                                    setHoveredCategory(null);
                                  }}
                                  className="block text-sm text-foreground/80 hover:text-primary transition-colors text-left w-full"
                                >
                                  {item}
                                </button>
                              ))}
                            </div>
                          </div>
                        ));
                      })()}
                    </div>
                  </div>
                </motion.div>
              )}
            </AnimatePresence>

            {/* Scrolled Search Input Bar */}
            <AnimatePresence>
              {isScrolled && searchExpanded && (
                <motion.div
                  initial={{ opacity: 0, y: -10 }}
                  animate={{ opacity: 1, y: 0 }}
                  exit={{ opacity: 0, y: -10 }}
                  transition={{ duration: 0.2 }}
                  className="absolute left-0 right-0 top-full bg-background/98 backdrop-blur-md border-t border-border shadow-xl"
                >
                  <div className="container mx-auto px-4 py-4">
                    <form onSubmit={handleSearch} className="relative max-w-md mx-auto">
                      <div className="flex items-center bg-muted/30 rounded-full px-4 py-2">
                        <Search className="w-4 h-4 text-muted-foreground mr-2" />
                        <input
                          type="text"
                          placeholder="Search"
                          value={searchQuery}
                          onChange={(e) => setSearchQuery(e.target.value)}
                          className="bg-transparent border-none outline-none text-sm placeholder:text-muted-foreground flex-1"
                          autoFocus
                        />
                      </div>
                    </form>
                  </div>
                </motion.div>
              )}
            </AnimatePresence>
          </nav>
        </div>
      </div>
    </header>
  );
}
