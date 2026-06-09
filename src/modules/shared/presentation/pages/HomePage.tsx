import { Hero } from '../components/Hero';
import { CategoriesShowcase } from '../../../catalog/presentation/components/CategoriesShowcase';
import { FeaturedProducts } from '../../../catalog/presentation/components/FeaturedProducts';
import { PromoSection } from '../components/PromoSection';
import { StoreInfo } from '../components/StoreInfo';
import { Product } from '../../../catalog/domain/entities/Product';

interface HomePageProps {
  onCategoryClick: (category: string, subcategory?: string) => void;
  onProductClick: (product: Product) => void;
}

export function HomePage({ onCategoryClick, onProductClick }: HomePageProps) {
  return (
    <>
      {/* Hero Section */}
      <Hero onCategoryClick={(cat) => onCategoryClick(cat)} />

      {/* Categories Showcase */}
      <CategoriesShowcase onCategoryClick={onCategoryClick} />

      {/* Featured Products */}
      <FeaturedProducts onProductClick={onProductClick} />

      {/* Promo Section */}
      <PromoSection />

      {/* Store Information */}
      <StoreInfo />
    </>
  );
}
export default HomePage;
