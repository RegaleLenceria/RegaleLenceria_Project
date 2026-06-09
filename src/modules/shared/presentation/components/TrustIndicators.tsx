import { motion } from 'motion/react';
import { Shield, Truck, Award } from 'lucide-react';

const indicators = [
  {
    icon: Shield,
    title: 'Compra Segura',
    description: '100% seguro y confiable',
  },
  {
    icon: Truck,
    title: 'Envío Nacional',
    description: 'A toda Bolivia',
  },
  {
    icon: Award,
    title: 'Calidad Premium',
    description: 'Productos importados',
  },
];

export function TrustIndicators() {
  return (
    <section className="py-12 md:py-16 border-y border-border bg-muted/20">
      <div className="container mx-auto px-4">
        <div className="flex flex-wrap justify-center items-center gap-12 md:gap-20 max-w-5xl mx-auto">
          {indicators.map((indicator, index) => (
            <motion.div
              key={index}
              initial={{ opacity: 0, y: 20 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.5, delay: index * 0.1 }}
              className="text-center flex-shrink-0"
            >
              <motion.div
                whileHover={{ scale: 1.1, rotate: 5 }}
                transition={{ duration: 0.3 }}
                className="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-primary/10 mb-4"
              >
                <indicator.icon className="w-8 h-8 text-primary" />
              </motion.div>
              <h4 className="mb-1">{indicator.title}</h4>
              <p className="text-sm text-muted-foreground">{indicator.description}</p>
            </motion.div>
          ))}
        </div>
      </div>
    </section>
  );
}
