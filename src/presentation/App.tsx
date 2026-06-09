import { useState } from 'react';
import { Header } from '../modules/shared/presentation/components/Header';
import { MobileMenu } from '../modules/catalog/presentation/components/MobileMenu';
import { HomePage } from '../modules/shared/presentation/pages/HomePage';
import { SearchResultsPage } from '../modules/catalog/presentation/pages/SearchResultsPage';
import { CategoryPage } from '../modules/catalog/presentation/pages/CategoryPage';
import { ProductDetailPage } from '../modules/catalog/presentation/pages/ProductDetailPage';
import { TermsAndConditionsPage } from '../modules/shared/presentation/pages/TermsAndConditionsPage';
import { CartPage } from '../modules/cart/presentation/pages/CartPage';
import { CartProvider } from '../modules/cart/presentation/context/CartContext';
import { WhatsAppFAB } from '../modules/shared/presentation/components/WhatsAppFAB';
import { BackToTop } from '../modules/shared/presentation/components/BackToTop';
import { ExitIntentModal } from '../modules/shared/presentation/components/ExitIntentModal';
import { Footer } from '../modules/shared/presentation/components/Footer';
import { Product } from '../modules/catalog/domain/entities/Product';

type View = 'home' | 'product' | 'search' | 'category' | 'terms' | 'cart';

export function App() {
  const [menuOpen, setMenuOpen] = useState(false);
  const [currentView, setCurrentView] = useState<View>('home');
  const [searchQuery, setSearchQuery] = useState('');
  const [selectedCategory, setSelectedCategory] = useState('');
  const [selectedSubcategory, setSelectedSubcategory] = useState('');
  const [selectedProduct, setSelectedProduct] = useState<Product | undefined>(undefined);

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

  const goToProduct = (productData?: Product) => {
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
            <HomePage
              onCategoryClick={handleCategoryClick}
              onProductClick={goToProduct}
            />
            <Footer onTermsClick={goToTerms} onHomeClick={goHome} />
          </>
        )}

        {currentView === 'search' && (
          <>
            <SearchResultsPage
              query={searchQuery}
              onClearSearch={goHome}
              onProductClick={goToProduct}
            />
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
          <TermsAndConditionsPage onBackClick={goHome} />
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
export default App;
