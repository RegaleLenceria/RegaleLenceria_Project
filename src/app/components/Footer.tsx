import { motion } from 'motion/react';
import { Facebook, Instagram, Youtube } from 'lucide-react';
import logoImg from '../../imports/image-10.png';

interface FooterProps {
  onTermsClick?: () => void;
  onHomeClick?: () => void;
}

export function Footer({ onTermsClick, onHomeClick }: FooterProps) {
  return (
    <footer className="bg-secondary text-secondary-foreground mt-20 w-full overflow-hidden">
      <div className="container mx-auto px-4 py-12 md:py-16">
        {/* Logo */}
        <div className="text-center mb-8">
          <img
            src={logoImg}
            alt="Regale Lencería"
            className="h-12 md:h-16 w-auto mx-auto mb-4 invert brightness-0 contrast-200"
            style={{ filter: 'invert(1) brightness(2)' }}
          />
        </div>

        {/* Philosophy */}
        <div className="max-w-3xl mx-auto text-center mb-12">
          <p className="text-sm md:text-base leading-relaxed opacity-90">
            En un mundo de constante cambio y desarrollo nuestro reto es brindar la mejor
            experiencia posible en la compra de productos, que van desde casual y despreocupada,
            hasta sofisticada. Manteniendo un diseño con estilo de lujo y altos estándares de belleza.
          </p>
        </div>

        {/* Quick Links */}
        <div className="flex flex-wrap justify-center gap-6 md:gap-8 mb-12">
          <button onClick={onHomeClick} className="hover:text-primary transition-colors">Inicio</button>
          <button
            onClick={() => window.open('https://wa.me/59171234567', '_blank')}
            className="hover:text-primary transition-colors"
          >
            Contacto
          </button>
          <button onClick={onTermsClick} className="hover:text-primary transition-colors">Términos y condiciones</button>
        </div>

        {/* Newsletter */}
        <div className="max-w-md mx-auto mb-12">
          <h3 className="text-center mb-4">¡Suscríbete para más beneficios!</h3>
          <div className="flex gap-2">
            <input
              type="email"
              placeholder="Tu correo electrónico"
              className="flex-1 px-4 py-3 rounded-full bg-background text-foreground border border-border focus:outline-none focus:ring-2 focus:ring-primary"
            />
            <motion.button
              whileHover={{ scale: 1.05 }}
              whileTap={{ scale: 0.95 }}
              className="bg-primary text-primary-foreground px-6 py-3 rounded-full transition-all hover:shadow-lg whitespace-nowrap"
            >
              Enviar
            </motion.button>
          </div>
        </div>

        {/* Social Media */}
        <div className="flex justify-center gap-6 mb-8">
          <motion.a
            whileHover={{ scale: 1.1 }}
            whileTap={{ scale: 0.9 }}
            href="#"
            className="p-3 bg-background/10 rounded-full hover:bg-primary transition-colors"
            aria-label="Facebook"
          >
            <Facebook className="w-5 h-5" />
          </motion.a>
          <motion.a
            whileHover={{ scale: 1.1 }}
            whileTap={{ scale: 0.9 }}
            href="#"
            className="p-3 bg-background/10 rounded-full hover:bg-primary transition-colors"
            aria-label="Instagram"
          >
            <Instagram className="w-5 h-5" />
          </motion.a>
          <motion.a
            whileHover={{ scale: 1.1 }}
            whileTap={{ scale: 0.9 }}
            href="#"
            className="p-3 bg-background/10 rounded-full hover:bg-primary transition-colors"
            aria-label="YouTube"
          >
            <Youtube className="w-5 h-5" />
          </motion.a>
          <motion.a
            whileHover={{ scale: 1.1 }}
            whileTap={{ scale: 0.9 }}
            href="#"
            className="p-3 bg-background/10 rounded-full hover:bg-primary transition-colors"
            aria-label="TikTok"
          >
            <svg className="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
              <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/>
            </svg>
          </motion.a>
        </div>

        {/* Copyright */}
        <div className="text-center text-sm opacity-70">
          <p>&copy; 2026 Regale Lencería. Todos los derechos reservados.</p>
        </div>
      </div>
    </footer>
  );
}
