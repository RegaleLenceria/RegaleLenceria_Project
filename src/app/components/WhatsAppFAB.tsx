import { motion } from 'motion/react';
import { MessageCircle } from 'lucide-react';

export function WhatsAppFAB() {
  const handleWhatsAppClick = () => {
    // WhatsApp number for Bolivia
    window.open('https://wa.me/59171234567', '_blank');
  };

  return (
    <motion.button
      whileHover={{ scale: 1.1 }}
      whileTap={{ scale: 0.9 }}
      onClick={handleWhatsAppClick}
      className="fixed bottom-6 right-6 z-50 bg-[#25D366] text-white p-4 md:p-4 rounded-full shadow-2xl hover:shadow-3xl transition-all"
      style={{ minWidth: '56px', minHeight: '56px' }}
      aria-label="Contact via WhatsApp"
      initial={{ scale: 0, opacity: 0 }}
      animate={{ scale: 1, opacity: 1 }}
      transition={{ delay: 1, duration: 0.5, type: 'spring' }}
    >
      <MessageCircle className="w-6 h-6 md:w-7 md:h-7" />
      <motion.div
        className="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full"
        animate={{
          scale: [1, 1.2, 1],
        }}
        transition={{
          duration: 2,
          repeat: Infinity,
          ease: 'easeInOut',
        }}
      />
    </motion.button>
  );
}
