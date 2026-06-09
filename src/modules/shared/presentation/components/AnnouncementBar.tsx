import { motion, AnimatePresence } from 'motion/react';
import { X, Sparkles } from 'lucide-react';
import { useState } from 'react';

export function AnnouncementBar() {
  const [isVisible, setIsVisible] = useState(true);

  if (!isVisible) return null;

  return (
    <AnimatePresence>
      <motion.div
        initial={{ height: 0, opacity: 0 }}
        animate={{ height: 'auto', opacity: 1 }}
        exit={{ height: 0, opacity: 0 }}
        className="bg-primary text-primary-foreground overflow-hidden"
      >
        <div className="container mx-auto px-4 py-3">
          <div className="flex items-center justify-center gap-2 text-sm md:text-base relative">
            <Sparkles className="w-4 h-4 flex-shrink-0" />
            <p className="text-center">
              <span className="font-medium">Envío gratis</span> en compras superiores a Bs 1.400 / $200
            </p>
            <button
              onClick={() => setIsVisible(false)}
              className="absolute right-0 p-1 hover:bg-primary-foreground/10 rounded-full transition-colors"
              aria-label="Close announcement"
            >
              <X className="w-4 h-4" />
            </button>
          </div>
        </div>
      </motion.div>
    </AnimatePresence>
  );
}
