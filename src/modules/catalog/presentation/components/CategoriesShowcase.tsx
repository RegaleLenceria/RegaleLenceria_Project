import { motion } from 'motion/react';
import { ImageWithFallback } from '../../../shared/presentation/components/ImageWithFallback';

interface CategoriesShowcaseProps {
  onCategoryClick?: (category: string, subcategory?: string) => void;
}

const showcaseCategories = [
  {
    name: 'Ropa Interior',
    image: '/cat-ropainterior.jpg',
    bgColor: '#D4A5A5',
    category: 'Brasier',
    subcategory: 'Push Up'
  },
  {
    name: 'Fajas',
    image: '/cat-fajas.jpg',
    bgColor: '#8B7B6F',
    category: 'Fajas',
    subcategory: 'Postquirúrgica'
  },
  {
    name: 'Deportivos',
    image: '/cat-deportivos.jpg',
    bgColor: '#5C7A8C',
    category: 'Deportivos',
    subcategory: 'Top'
  },
  {
    name: 'Trajes de Baño',
    image: '/cat-trajesdebano.jpg',
    bgColor: '#A8C5B5',
    category: 'Trajes de Baño',
    subcategory: 'Bikini'
  },
];

export function CategoriesShowcase({ onCategoryClick }: CategoriesShowcaseProps) {
  return (
    <section className="py-8 md:py-12 bg-background">
      <div className="container mx-auto px-4">
        {/* Categories Grid - Victoria's Secret Style with Solid Color Backgrounds */}
        <div className="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
          {showcaseCategories.map((category, index) => (
            <motion.div
              key={index}
              initial={{ opacity: 0, y: 20 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.5, delay: index * 0.08 }}
              className="group cursor-pointer relative aspect-[3/4] overflow-hidden"
              style={{ backgroundColor: category.bgColor }}
              onClick={() => onCategoryClick?.(category.category, category.subcategory)}
            >
              {/* Product Image */}
              <div className="relative w-full h-full">
                <ImageWithFallback
                  src={category.image}
                  alt={category.name}
                  className="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                />

                {/* Text Overlay - Bottom Left */}
                <div className="absolute bottom-0 left-0 p-4 md:p-6">
                  <h3 className="text-white text-base md:text-lg font-medium drop-shadow-lg">
                    {category.name}
                  </h3>
                </div>
              </div>
            </motion.div>
          ))}
        </div>
      </div>
    </section>
  );
}
