import { motion } from 'motion/react';
import { ImageWithFallback } from './ImageWithFallback';
import Slider from 'react-slick';
import 'slick-carousel/slick/slick.css';
import 'slick-carousel/slick/slick-theme.css';

interface HeroProps {
  onCategoryClick?: (category: string) => void;
}

const heroImages = [
  '/carousel-1.png',
  '/carousel-2.jpg',
  '/carousel-3.jpg',
];

export function Hero({ onCategoryClick }: HeroProps) {
  const settings = {
    dots: true,
    infinite: true,
    speed: 800,
    slidesToShow: 1,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 4000,
    fade: true,
    cssEase: 'ease-in-out',
    pauseOnHover: false,
  };

  return (
    <>
      {/* Promo Banner */}
      <div className="bg-primary text-primary-foreground text-center py-2 text-xs md:text-sm tracking-widest uppercase">
        Envío gratis hasta el 4to anillo en compras mínimas de $30
      </div>

      {/* Main Hero Carousel */}
      <section className="relative h-[60vh] md:h-[70vh] overflow-hidden bg-background hero-carousel">
        <Slider {...settings}>
          {heroImages.map((image, index) => (
            <div key={index} className="relative h-[60vh] md:h-[70vh]">
              {/* Background Image */}
              <div className="absolute inset-0">
                <ImageWithFallback
                  src={image}
                  alt={`Nueva Colección ${index + 1}`}
                  className="w-full h-full object-cover"
                />
                <div className="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent" />
              </div>

              {/* Content */}
              <div className="relative z-10 container mx-auto px-4 h-full flex flex-col items-center justify-center text-center">
                <motion.div
                  initial={{ opacity: 0, y: 20 }}
                  animate={{ opacity: 1, y: 0 }}
                  transition={{ duration: 0.6 }}
                  className="text-white"
                >
                  <h1 className="text-3xl md:text-5xl lg:text-6xl mb-3 md:mb-4 font-light tracking-widest uppercase">
                    Nueva Colección
                  </h1>
                  <p className="text-base md:text-xl mb-8 md:mb-10 tracking-wide uppercase font-light">
                    Primavera 2026
                  </p>
                  <div className="flex flex-col sm:flex-row gap-3 md:gap-4 justify-center">
                    <button
                      onClick={() => onCategoryClick?.('Brasier')}
                      className="bg-white text-black px-8 md:px-10 py-3 md:py-3.5 uppercase tracking-wider text-sm transition-all hover:bg-opacity-90"
                    >
                      Comprar Ahora
                    </button>
                    <button
                      onClick={() => onCategoryClick?.('Panty')}
                      className="border-2 border-white text-white px-8 md:px-10 py-3 md:py-3.5 uppercase tracking-wider text-sm transition-all hover:bg-white hover:text-black"
                    >
                      Ver Más
                    </button>
                  </div>
                </motion.div>
              </div>
            </div>
          ))}
        </Slider>

        <style>{`
          .hero-carousel .slick-dots {
            bottom: 20px;
            z-index: 20;
          }
          .hero-carousel .slick-dots li button:before {
            font-size: 10px;
            color: white;
            opacity: 0.5;
          }
          .hero-carousel .slick-dots li.slick-active button:before {
            color: white;
            opacity: 1;
          }
        `}</style>
      </section>
    </>
  );
}
