import { motion } from 'motion/react';
import { ImageWithFallback } from './ImageWithFallback';
import { formatPrice, parsePriceString } from '../utils/currency';

interface ProductCardProps {
  name: string;
  price: string;
  image?: string;
  sku?: string;
  imageIndex?: number;
  onClick?: () => void;
  isNew?: boolean;
  discount?: number;
}

export function ProductCard({ name, price, sku, image, imageIndex = 0, onClick, isNew, discount }: ProductCardProps) {
  const imgSrc = image || '';

  const priceInBOB = parsePriceString(price);
  const formattedPrice = formatPrice(priceInBOB);

  const discountedPrice = discount ? priceInBOB * (1 - discount / 100) : null;
  const formattedDiscountedPrice = discountedPrice ? formatPrice(discountedPrice) : null;

  return (
    <motion.div
      initial={{ opacity: 0, y: 20 }}
      whileInView={{ opacity: 1, y: 0 }}
      viewport={{ once: true }}
      whileHover={{ y: -8 }}
      transition={{ duration: 0.3 }}
      className="group cursor-pointer"
    >
      <div className="relative mb-4 aspect-[3/4] rounded-2xl overflow-hidden bg-gradient-to-br from-muted to-muted/50">
        {/* Product Image */}
        <ImageWithFallback
          src={imgSrc}
          alt={name}
          className="w-full h-full object-cover"
        />

        {/* Badges */}
        <div className="absolute top-2 left-2 flex flex-col gap-2 z-10">
          {isNew && (
            <span className="bg-primary text-primary-foreground px-3 py-1 rounded-full text-xs font-medium shadow-lg">
              Nuevo
            </span>
          )}
          {discount && (
            <span className="bg-destructive text-destructive-foreground px-3 py-1 rounded-full text-xs font-medium shadow-lg">
              -{discount}%
            </span>
          )}
        </div>

        {/* Hover Overlay - Desktop Only */}
        <div className="hidden md:block">
          <motion.div
            initial={{ opacity: 0 }}
            whileHover={{ opacity: 1 }}
            className="absolute inset-0 bg-black/20 flex items-center justify-center"
          >
            <motion.button
              whileHover={{ scale: 1.1 }}
              whileTap={{ scale: 0.9 }}
              onClick={onClick}
              className="bg-primary text-primary-foreground px-6 py-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"
            >
              Ver Detalles
            </motion.button>
          </motion.div>
        </div>
      </div>

      <div className="text-center">
        <h3 className="mb-1 group-hover:text-primary transition-colors">{name}</h3>
        {sku && (
          <p className="text-xs text-muted-foreground mb-2">SKU: {sku}</p>
        )}
        <div className="mb-3">
          {discount ? (
            <div className="flex items-center justify-center gap-2">
              <p className="text-muted-foreground font-medium text-sm line-through">{formattedPrice}</p>
              <p className="text-primary font-medium text-sm">{formattedDiscountedPrice}</p>
            </div>
          ) : (
            <p className="text-primary font-medium text-sm">{formattedPrice}</p>
          )}
        </div>

        {/* Mobile View Details Button */}
        <motion.button
          whileTap={{ scale: 0.95 }}
          onClick={onClick}
          className="md:hidden w-full bg-primary text-primary-foreground px-4 py-2 rounded-full text-sm hover:shadow-lg transition-all"
        >
          Ver Detalles
        </motion.button>
      </div>
    </motion.div>
  );
}
