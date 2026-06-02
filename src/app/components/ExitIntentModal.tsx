import { motion, AnimatePresence } from 'motion/react';
import { X } from 'lucide-react';
import { useEffect, useState } from 'react';

export function ExitIntentModal() {
  const [isOpen, setIsOpen] = useState(false);
  const [hasShown, setHasShown] = useState(false);

  useEffect(() => {
    const handleMouseLeave = (e: MouseEvent) => {
      // Only trigger when mouse leaves from top of viewport and hasn't shown before
      // Skip on mobile devices
      if (e.clientY <= 0 && !hasShown && window.innerWidth >= 768) {
        setIsOpen(true);
        setHasShown(true);
      }
    };

    const handleTouchEnd = () => {
      // On mobile, show after 30 seconds if not shown
      if (!hasShown && window.innerWidth < 768) {
        setTimeout(() => {
          if (!hasShown) {
            setIsOpen(true);
            setHasShown(true);
          }
        }, 30000);
      }
    };

    document.addEventListener('mouseleave', handleMouseLeave);
    window.addEventListener('touchend', handleTouchEnd, { once: true });

    return () => {
      document.removeEventListener('mouseleave', handleMouseLeave);
      window.removeEventListener('touchend', handleTouchEnd);
    };
  }, [hasShown]);

  return (
    <AnimatePresence>
      {isOpen && (
        <>
          {/* Backdrop */}
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            transition={{ duration: 0.3 }}
            className="fixed inset-0 bg-black/60 z-50 backdrop-blur-sm"
            onClick={() => setIsOpen(false)}
          />

          {/* Modal */}
          <motion.div
            initial={{ scale: 0.8, opacity: 0 }}
            animate={{ scale: 1, opacity: 1 }}
            exit={{ scale: 0.8, opacity: 0 }}
            transition={{ type: 'spring', damping: 20, stiffness: 300 }}
            className="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50 w-[90%] max-w-md"
          >
            <div className="bg-background rounded-3xl shadow-2xl p-8 relative">
              {/* Close Button */}
              <button
                onClick={() => setIsOpen(false)}
                className="absolute top-4 right-4 p-2 hover:bg-muted rounded-full transition-all"
                aria-label="Close"
              >
                <X className="w-5 h-5" />
              </button>

              {/* Content */}
              <div className="text-center">
                <h2 className="text-3xl mb-4">¡No te vayas sin ver más!</h2>
                <p className="text-muted-foreground mb-8">
                  Descubre nuestra exclusiva colección de lencería importada
                </p>
                <motion.button
                  whileHover={{ scale: 1.05 }}
                  whileTap={{ scale: 0.95 }}
                  onClick={() => setIsOpen(false)}
                  className="bg-primary text-primary-foreground px-8 py-3 rounded-full w-full transition-all hover:shadow-lg"
                >
                  Consultar el Catálogo
                </motion.button>
              </div>
            </div>
          </motion.div>
        </>
      )}
    </AnimatePresence>
  );
}
