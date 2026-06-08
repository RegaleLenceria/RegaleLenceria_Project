import { useState } from 'react';
import { Header } from './components/Header';
import { MobileMenu } from './components/MobileMenu';
import { Hero } from './components/Hero';
import { CategoriesShowcase } from './components/CategoriesShowcase';
import { FeaturedProducts } from './components/FeaturedProducts';
import { PromoSection } from './components/PromoSection';
import { StoreInfo } from './components/StoreInfo';
import { Footer } from './components/Footer';
import { WhatsAppFAB } from './components/WhatsAppFAB';
import { BackToTop } from './components/BackToTop';
import { ProductDetailPage } from './components/ProductDetailPage';
import { SearchResults } from './components/SearchResults';
import { CategoryPage } from './components/CategoryPage';
import { TermsAndConditions } from './components/TermsAndConditions';
import { CartPage } from './components/CartPage';
import { CartProvider } from './context/CartContext';
import { Product } from './data/products';
import { ExitIntentModal } from './components/ExitIntentModal';

type View = 'home' | 'product' | 'search' | 'category' | 'terms' | 'cart';

export default function App() {
  const [menuOpen, setMenuOpen] = useState(false);
  const [currentView, setCurrentView] = useState<View>('home');
  const [searchQuery, setSearchQuery] = useState('');
  const [selectedCategory, setSelectedCategory] = useState('');
  const [selectedSubcategory, setSelectedSubcategory] = useState('');
  const [selectedProduct, setSelectedProduct] = useState<any>(undefined);

  const handleSearch = (query: string) => {
    setSearchQuery(query);
    setCurrentView('search');
    window.scrollTo(0, 0);
  };

  const handleCategoryClick = (category: string, subcategory?: string) => {
    setSelectedCategory(category);
    setSelectedSubcategory(subcategory || '');
    setCurrentView('category');
    setMenuOpen(false);
    window.scrollTo(0, 0);
  };

  const goHome = () => {
    setCurrentView('home');
    setSelectedCategory('');
    setSelectedSubcategory('');
    window.scrollTo(0, 0);
  };

  const goToTerms = () => {
    setCurrentView('terms');
    window.scrollTo(0, 0);
  };

  const goToProduct = (productData?: any) => {
    if (productData) {
      setSelectedProduct(productData);
    }
    setCurrentView('product');
    window.scrollTo(0, 0);
  };

  const goToCart = () => {
    setCurrentView('cart');
    window.scrollTo(0, 0);
  };

  return (
    <CartProvider>
      <div className="relative min-h-screen bg-background">
      {/* Header */}
      <Header
        onMenuClick={() => setMenuOpen(true)}
        onSearch={handleSearch}
        onLogoClick={goHome}
        onCategoryClick={handleCategoryClick}
        onCartClick={goToCart}
      />

      {/* Mobile Menu */}
      <MobileMenu
        isOpen={menuOpen}
        onClose={() => setMenuOpen(false)}
        onCategoryClick={handleCategoryClick}
        onHomeClick={goHome}
        onTermsClick={goToTerms}
      />

      {/* Main Content */}
      {currentView === 'home' && (
        <>
          {/* Hero Section */}
          <Hero onCategoryClick={handleCategoryClick} />

          {/* Categories Showcase */}
          <CategoriesShowcase onCategoryClick={handleCategoryClick} />

          {/* Featured Products */}
          <FeaturedProducts onProductClick={goToProduct} />

          {/* Promo Section */}
          <PromoSection />

          {/* Store Information */}
          <StoreInfo />

          {/* Footer */}
          <Footer onTermsClick={goToTerms} onHomeClick={goHome} />
        </>
      )}

      {currentView === 'search' && (
        <>
          <SearchResults query={searchQuery} onClearSearch={goHome} onProductClick={goToProduct} />
          <Footer onTermsClick={goToTerms} onHomeClick={goHome} />
        </>
      )}

      {currentView === 'category' && (
        <>
          <CategoryPage
            categoryName={selectedCategory}
            subcategory={selectedSubcategory}
            onSubcategoryChange={setSelectedSubcategory}
            onProductClick={goToProduct}
            onHomeClick={goHome}
          />
          <Footer onTermsClick={goToTerms} onHomeClick={goHome} />
        </>
      )}

      {currentView === 'product' && (
        <>
          <ProductDetailPage
            product={selectedProduct}
            onProductClick={goToProduct}
            onHomeClick={goHome}
            onCategoryClick={(cat) => handleCategoryClick(cat)}
          />
          <Footer onTermsClick={goToTerms} onHomeClick={goHome} />
        </>
      )}

      {currentView === 'terms' && (
        <TermsAndConditions onBackClick={goHome} />
      )}

      {currentView === 'cart' && (
        <CartPage onBackClick={goHome} />
      )}

      {/* WhatsApp FAB - Always Visible */}
      <WhatsAppFAB />

      {/* Back to Top Button */}
      <BackToTop />

      {/* Exit Intent Modal */}
      <ExitIntentModal />
    </div>
    </CartProvider>
  );
}