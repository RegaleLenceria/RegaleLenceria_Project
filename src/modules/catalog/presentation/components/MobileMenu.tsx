import { motion, AnimatePresence } from 'motion/react';
import { X, ChevronDown } from 'lucide-react';
import * as Accordion from '@radix-ui/react-accordion';
import { categories } from '../../domain/entities/Category';

interface MobileMenuProps {
  isOpen: boolean;
  onClose: () => void;
  onCategoryClick?: (category: string, subcategory?: string) => void;
  onHomeClick?: () => void;
  onTermsClick?: () => void;
}

export function MobileMenu({ isOpen, onClose, onCategoryClick, onHomeClick, onTermsClick }: MobileMenuProps) {
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
            className="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 md:hidden"
            onClick={onClose}
          />
        )}
      </AnimatePresence>

      {/* Menu Panel */}
      <AnimatePresence>
        {isOpen && (
          <motion.div
            initial={{ x: '-100%' }}
            animate={{ x: 0 }}
            exit={{ x: '-100%' }}
            transition={{ duration: 0.4, ease: [0.4, 0, 0.2, 1] }}
            className="fixed left-0 top-0 h-full w-[85%] max-w-sm bg-background/98 backdrop-blur-xl z-50 shadow-2xl md:hidden overflow-hidden"
          >
            <div className="flex flex-col h-full">
              {/* Header */}
              <div className="p-6 border-b border-border bg-muted/10">
                <div className="flex justify-between items-center">
                  <div>
                    <h2 className="text-xl uppercase tracking-widest font-light mb-1">Categorías</h2>
                    <p className="text-xs text-muted-foreground uppercase tracking-wider">Explora nuestra colección</p>
                  </div>
                  <button
                    onClick={onClose}
                    className="p-2 hover:bg-muted transition-all"
                    aria-label="Close menu"
                  >
                    <X className="w-6 h-6" />
                  </button>
                </div>
              </div>

              {/* Categories Accordion */}
              <div className="flex-1 overflow-y-auto px-6 py-4">
                <Accordion.Root type="multiple" className="space-y-2">
                {categories.map((category, index) => (
                  <Accordion.Item
                    key={index}
                    value={`item-${index}`}
                    className="overflow-hidden bg-muted/10 mb-2 last:mb-0"
                  >
                    <Accordion.Header>
                      <Accordion.Trigger
                        className="flex items-center justify-between w-full px-4 py-3.5 hover:text-primary transition-colors group uppercase tracking-wider text-sm"
                        onClick={(e) => {
                          if (category.subcategories.length === 0 && onCategoryClick) {
                            e.preventDefault();
                            onCategoryClick(category.name, undefined);
                          }
                        }}
                      >
                        <span className="text-left flex-1">
                          {category.name}
                        </span>
                        {category.subcategories.length > 0 && (
                          <ChevronDown className="w-4 h-4 transition-transform duration-200 group-data-[state=open]:rotate-180" />
                        )}
                      </Accordion.Trigger>
                    </Accordion.Header>
                    {category.subcategories.length > 0 && (
                      <Accordion.Content className="overflow-hidden data-[state=open]:animate-slideDown data-[state=closed]:animate-slideUp">
                        <div className="px-4 pb-4 pt-2 bg-background/30 space-y-1">
                          {category.subcategories.map((sub, subIndex) => {
                            // Check if it's a section header
                            const isHeader = sub.startsWith('---');

                            if (isHeader) {
                              return (
                                <div
                                  key={subIndex}
                                  className="text-xs text-primary uppercase tracking-widest mt-3 first:mt-1 mb-2 pb-1 border-b border-primary/20"
                                >
                                  {sub.replace(/---/g, '').trim()}
                                </div>
                              );
                            }

                            return (
                              <button
                                key={subIndex}
                                onClick={() => onCategoryClick && onCategoryClick(category.name, sub)}
                                className="w-full text-left block text-sm text-muted-foreground hover:text-primary transition-colors py-2 px-3 hover:bg-muted/30"
                              >
                                {sub}
                              </button>
                            );
                          })}
                        </div>
                      </Accordion.Content>
                    )}
                  </Accordion.Item>
                ))}
              </Accordion.Root>
              </div>

              {/* Quick Links Footer */}
              <div className="p-6 border-t border-border bg-muted/10">
                <div className="grid grid-cols-3 gap-3">
                  <button
                    onClick={() => {
                      onHomeClick && onHomeClick();
                      onClose();
                    }}
                    className="text-xs text-center py-2.5 px-4 bg-background/50 hover:bg-primary hover:text-primary-foreground transition-colors uppercase tracking-wider"
                  >
                    Inicio
                  </button>
                  <button
                    onClick={() => {
                      window.open('https://wa.me/59171234567', '_blank');
                    }}
                    className="text-xs text-center py-2.5 px-4 bg-background/50 hover:bg-primary hover:text-primary-foreground transition-colors uppercase tracking-wider"
                  >
                    Contacto
                  </button>
                  <button
                    onClick={() => {
                      onTermsClick && onTermsClick();
                      onClose();
                    }}
                    className="text-xs text-center py-2.5 px-4 bg-background/50 hover:bg-primary hover:text-primary-foreground transition-colors uppercase tracking-wider"
                  >
                    Términos
                  </button>
                </div>
              </div>
            </div>
          </motion.div>
        )}
      </AnimatePresence>

      {/* Desktop Mega Menu (shown on hover) */}
      <div className="hidden md:block">
        {/* This would be implemented as a hover dropdown in a real app */}
      </div>
    </>
  );
}
