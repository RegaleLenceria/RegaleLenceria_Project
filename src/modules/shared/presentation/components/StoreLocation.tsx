import { motion } from 'motion/react';
import { MapPin, Phone, Clock } from 'lucide-react';
import { ImageWithFallback } from './ImageWithFallback';

export function StoreLocation() {
  return (
    <section className="py-16 md:py-24 bg-muted/30">
      <div className="container mx-auto px-4">
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.6 }}
          className="text-center mb-12"
        >
          <h2 className="text-3xl md:text-4xl mb-4">Visítanos</h2>
          <p className="text-muted-foreground">Te esperamos en nuestra tienda física</p>
        </motion.div>

        <div className="grid md:grid-cols-2 gap-8 md:gap-12 max-w-5xl mx-auto">
          {/* Store Image */}
          <motion.div
            initial={{ opacity: 0, x: -30 }}
            whileInView={{ opacity: 1, x: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6, delay: 0.2 }}
            className="relative h-64 md:h-80 rounded-3xl overflow-hidden"
          >
            <ImageWithFallback
              src="https://images.unsplash.com/photo-1621261027519-a71ac66d5a68?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1080"
              alt="Élégance boutique interior"
              className="w-full h-full object-cover"
            />
            <div className="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent" />
          </motion.div>

          {/* Store Info */}
          <motion.div
            initial={{ opacity: 0, x: 30 }}
            whileInView={{ opacity: 1, x: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6, delay: 0.3 }}
            className="space-y-6"
          >
            <div className="flex items-start gap-4">
              <div className="p-3 bg-primary/10 rounded-2xl">
                <MapPin className="w-6 h-6 text-primary" />
              </div>
              <div>
                <h3 className="mb-2">Dirección</h3>
                <p className="text-muted-foreground">
                  Av 26 de Febrero esq Fidel Oliva<br />
                  Santa Cruz de la Sierra, Bolivia
                </p>
              </div>
            </div>

            <div className="flex items-start gap-4">
              <div className="p-3 bg-primary/10 rounded-2xl">
                <Phone className="w-6 h-6 text-primary" />
              </div>
              <div>
                <h3 className="mb-2">Teléfono</h3>
                <p className="text-muted-foreground">
                  +591 3 123 4567
                </p>
              </div>
            </div>

            <div className="flex items-start gap-4">
              <div className="p-3 bg-primary/10 rounded-2xl">
                <Clock className="w-6 h-6 text-primary" />
              </div>
              <div>
                <h3 className="mb-2">Horario</h3>
                <p className="text-muted-foreground">
                  Lunes a Sábado: 9:00 AM - 7:00 PM<br />
                  Domingo: 10:00 AM - 3:00 PM
                </p>
              </div>
            </div>

          </motion.div>
        </div>

        {/* Google Maps */}
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.6, delay: 0.4 }}
          className="mt-12"
        >
          <h3 className="text-center mb-6 text-xl">Encuéntranos en el mapa</h3>
          <div className="aspect-video rounded-3xl overflow-hidden shadow-lg">
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3799.0!2d-63.18!3d-17.78!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTfCsDQ2JzQ4LjAiUyA2M8KwMTAnNDguMCJX!5e0!3m2!1ses!2sbo!4v1234567890"
              width="100%"
              height="100%"
              style={{ border: 0 }}
              allowFullScreen
              loading="lazy"
              referrerPolicy="no-referrer-when-downgrade"
              title="Ubicación de Regale Lencería"
            />
          </div>
        </motion.div>
      </div>
    </section>
  );
}
