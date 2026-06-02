import { Package, Truck, Shield } from 'lucide-react';

const benefits = [
  {
    icon: Truck,
    title: 'Envío Gratis',
    description: 'Hasta el 4to anillo en compras mínimas de $30',
  },
  {
    icon: Shield,
    title: 'Compra Segura',
    description: 'Protegemos tus datos',
  },
  {
    icon: Package,
    title: 'Devoluciones',
    description: 'Hasta 30 días',
  },
];

export function PromoSection() {
  return (
    <section className="py-12 md:py-16 bg-primary/5">
      <div className="container mx-auto px-4">
        <div className="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12">
          {benefits.map((benefit, index) => {
            const Icon = benefit.icon;
            return (
              <div key={index} className="text-center">
                <Icon className="w-8 h-8 md:w-10 md:h-10 mx-auto mb-4 text-primary" />
                <h3 className="uppercase tracking-wider text-sm md:text-base mb-2">
                  {benefit.title}
                </h3>
                <p className="text-muted-foreground text-xs md:text-sm">
                  {benefit.description}
                </p>
              </div>
            );
          })}
        </div>
      </div>
    </section>
  );
}
