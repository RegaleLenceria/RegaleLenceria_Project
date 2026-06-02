import { motion } from 'motion/react';
import { Sparkles } from 'lucide-react';

export function PromotionalBanner() {
  return (
    <section className="py-16 md:py-20 bg-gradient-to-br from-primary/10 via-background to-primary/5 overflow-hidden">
      <div className="container mx-auto px-4">
        <div className="max-w-4xl mx-auto text-center relative">
          {/* Decorative Elements */}
          <motion.div
            animate={{
              rotate: [0, 360],
              scale: [1, 1.2, 1],
            }}
            transition={{
              duration: 20,
              repeat: Infinity,
              ease: 'linear',
            }}
            className="absolute -top-10 -left-10 w-32 h-32 bg-primary/5 rounded-full blur-3xl"
          />
          <motion.div
            animate={{
              rotate: [360, 0],
              scale: [1, 1.3, 1],
            }}
            transition={{
              duration: 25,
              repeat: Infinity,
              ease: 'linear',
            }}
            className="absolute -bottom-10 -right-10 w-40 h-40 bg-primary/5 rounded-full blur-3xl"
          />

          {/* Content */}
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6 }}
            className="relative z-10"
          >
            <motion.div
              animate={{
                y: [0, -5, 0],
              }}
              transition={{
                duration: 2,
                repeat: Infinity,
                ease: 'easeInOut',
              }}
              className="inline-flex items-center gap-2 bg-primary/10 px-6 py-2 rounded-full mb-6"
            >
              <Sparkles className="w-4 h-4 text-primary" />
              <span className="text-sm font-medium text-primary">Oferta Especial</span>
            </motion.div>

            <h2 className="text-4xl md:text-5xl lg:text-6xl mb-6">
              Celebra el Día de las Madres
            </h2>
            <p className="text-lg md:text-xl text-muted-foreground mb-8 max-w-2xl mx-auto">
              Obtén hasta un 20% de descuento en toda nuestra colección exclusiva.
              Regala elegancia y sofisticación.
            </p>

            <div className="flex flex-col sm:flex-row gap-4 justify-center">
              <motion.button
                whileHover={{ scale: 1.05 }}
                whileTap={{ scale: 0.95 }}
                onClick={() => window.scrollTo({ top: 0, behavior: 'smooth' })}
                className="bg-primary text-primary-foreground px-8 py-4 rounded-full hover:shadow-lg transition-all"
              >
                Ver Ofertas
              </motion.button>
              <motion.button
                whileHover={{ scale: 1.05 }}
                whileTap={{ scale: 0.95 }}
                onClick={() => window.open('https://wa.me/59171234567', '_blank')}
                className="border-2 border-primary text-primary px-8 py-4 rounded-full hover:bg-primary hover:text-primary-foreground transition-all"
              >
                Más Información
              </motion.button>
            </div>
          </motion.div>
        </div>
      </div>
    </section>
  );
}
