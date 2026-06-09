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
            className="w-full rounded-2xl overflow-hidden shadow-lg border border-border"
          >
            <div className="relative h-[300px] md:h-[400px]">
              <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3799.024739468869!2d-63.19675162504285!3d-17.79053438316697!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x93f1e81a535a7a05%3A0x7315277f8c447eab!2sRegale%20Lencer%C3%ADa!5e0!3m2!1ses-419!2sbo!4v1780499075865!5m2!1ses-419!2sbo"
                width="100%"
                height="100%"
                style={{ border: 0 }}
                allowFullScreen
                loading="lazy"
                referrerPolicy="no-referrer-when-downgrade"
                title="Ubicación de Regale Lencería"
              />
              <div className="absolute bottom-4 right-4">
                <a
                  href="https://maps.app.goo.gl/ijznZaaeU75Hu1j58"
                  target="_blank"
                  rel="noopener noreferrer"
                  className="inline-block bg-primary text-primary-foreground px-6 py-2.5 uppercase tracking-widest text-xs hover:bg-primary/90 transition-colors shadow-lg rounded"
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
