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
