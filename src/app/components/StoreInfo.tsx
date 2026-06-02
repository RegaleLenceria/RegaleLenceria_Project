import { motion } from 'motion/react';
import { MapPin, Clock } from 'lucide-react';

export function StoreInfo() {
  return (
    <section className="py-12 md:py-16 bg-muted/30">
      <div className="container mx-auto px-4">
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.6 }}
          className="text-center mb-8 md:mb-12"
        >
          <h2 className="text-2xl md:text-3xl font-light tracking-widest uppercase mb-2">
            Visítanos
          </h2>
          <p className="text-muted-foreground">Encuentra nuestra tienda física</p>
        </motion.div>

        <div className="grid md:grid-cols-2 gap-8 md:gap-12 items-start">
          {/* Store Information */}
          <motion.div
            initial={{ opacity: 0, x: -20 }}
            whileInView={{ opacity: 1, x: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6, delay: 0.2 }}
            className="space-y-6"
          >
            {/* Address */}
            <div className="flex gap-4">
              <div className="flex-shrink-0">
                <div className="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                  <MapPin className="w-6 h-6 text-primary" />
                </div>
              </div>
              <div>
                <h3 className="text-lg font-medium mb-2">Nuestra Ubicación</h3>
                <p className="text-muted-foreground">
                  Segundo Anillo &amp; Fidel Oliva<br />
                  Santa Cruz de la Sierra
                </p>
                <a
                  href="https://maps.app.goo.gl/ijznZaaeU75Hu1j58"
                  target="_blank"
                  rel="noopener noreferrer"
                  className="inline-block mt-3 text-primary hover:underline text-sm"
                >
                  Ver en Google Maps →
                </a>
              </div>
            </div>

            {/* Hours */}
            <div className="flex gap-4">
              <div className="flex-shrink-0">
                <div className="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                  <Clock className="w-6 h-6 text-primary" />
                </div>
              </div>
              <div>
                <h3 className="text-lg font-medium mb-2">Horarios de Atención</h3>
                <div className="space-y-1 text-muted-foreground">
                  <p>Lunes a Viernes: 10:00 - 18:30</p>
                  <p>Sábado: 9:00 - 13:00 (horario continuo)</p>
                  <p className="text-sm italic mt-2">Domingo: Cerrado</p>
                </div>
              </div>
            </div>
          </motion.div>

          {/* Store Photo + Map Link */}
          <motion.div
            initial={{ opacity: 0, x: 20 }}
            whileInView={{ opacity: 1, x: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6, delay: 0.4 }}
            className="w-full rounded-2xl overflow-hidden shadow-lg"
          >
            <div className="relative h-[300px] md:h-[400px]">
              <img
                src="/store-interior.jpg"
                alt="Vitrina de Regale Lencería"
                className="w-full h-full object-cover"
              />
              <div className="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent" />
              <div className="absolute bottom-4 left-4 right-4 text-center">
                <a
                  href="https://maps.app.goo.gl/ijznZaaeU75Hu1j58"
                  target="_blank"
                  rel="noopener noreferrer"
                  className="inline-block bg-primary text-primary-foreground px-6 py-2.5 uppercase tracking-widest text-xs hover:bg-primary/90 transition-colors"
                >
                  Cómo Llegar →
                </a>
              </div>
            </div>
          </motion.div>
        </div>
      </div>
    </section>
  );
}
