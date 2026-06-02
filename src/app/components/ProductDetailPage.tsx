import { useState } from 'react';
import { motion, AnimatePresence } from 'motion/react';
import { ChevronLeft, ChevronRight, Plus, Minus, ShoppingCart, Check } from 'lucide-react';
import { ProductCard } from './ProductCard';
import { ImageWithFallback } from './ImageWithFallback';
import { Breadcrumb } from './Breadcrumb';
import { products, Product } from '../data/products';
import { useCart } from '../context/CartContext';


interface ProductDetailPageProps {
  product?: {
    name: string;
    category: string;
    subcategory: string;
    price?: string;
    sku?: string;
  };
  onProductClick?: (product?: any) => void;
  onHomeClick?: () => void;
  onCategoryClick?: (category: string) => void;
}

export function ProductDetailPage({ product: productProp, onProductClick, onHomeClick, onCategoryClick }: ProductDetailPageProps) {
  // Buscar producto completo en la base de datos
  const fullProduct = productProp?.sku ? products.find(p => p.sku === productProp.sku) : null;
  const { addItem } = useCart();

  const [currentImage, setCurrentImage] = useState(0);
  const [selectedColorIndex, setSelectedColorIndex] = useState(0);
  const [selectedSizeIndex, setSelectedSizeIndex] = useState(0);
  const [quantity, setQuantity] = useState(1);
  const [showFullDescription, setShowFullDescription] = useState(false);
  const [addedToCart, setAddedToCart] = useState(false);

  const productName = fullProduct?.name || productProp?.name || 'Conjunto Victoria';
  const productCategory = fullProduct?.category || productProp?.category || 'Lencería';
  const productSubcategory = fullProduct?.subcategory || productProp?.subcategory || 'Conjunto';
  const productPrice = fullProduct?.price || productProp?.price || '1111.00';
  const productSKU = fullProduct?.sku || productProp?.sku || 'LS-2024-123';
  const productImages = fullProduct?.images || productProp?.images || [
    'https://images.unsplash.com/photo-1762195020829-835d05d3ee80?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1080',
    'https://images.unsplash.com/photo-1763906471843-aa53dad9fba8?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1080',
    'https://images.unsplash.com/photo-1762843353166-e0542bba1a66?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1080',
    'https://images.unsplash.com/photo-1762843352569-6350701c80d1?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1080',
  ];
  const productColors = fullProduct?.colors || ['Negro', 'Blanco', 'Rosa', 'Rojo'];
  const productSizes = fullProduct?.sizes || ['XS', 'S', 'M', 'L', 'XL'];
  const productMaterials = fullProduct?.material || ['Encaje', 'Microfibra'];
  const stockAvailable = fullProduct?.stock || 5;
  const shortDescription = fullProduct?.description.short || 'Elegante conjunto de lencería importada, confeccionado con materiales de la más alta calidad. Diseño sofisticado que combina comodidad y sensualidad.';
  const longDescription = fullProduct?.description.long || '';
  const features = fullProduct?.description.features || [];

  const nextImage = () => {
    setCurrentImage((prev) => (prev + 1) % productImages.length);
  };

  const prevImage = () => {
    setCurrentImage((prev) => (prev - 1 + productImages.length) % productImages.length);
  };

  const handleAddToCart = () => {
    addItem({
      id: fullProduct?.id || productSKU,
      name: productName,
      price: productPrice,
      sku: productSKU,
      color: productColors[selectedColorIndex],
      size: productSizes[selectedSizeIndex],
      quantity: quantity,
      image: productImages[0],
    });

    // Mostrar feedback visual
    setAddedToCart(true);
    setTimeout(() => {
      setAddedToCart(false);
    }, 2000);

    // Resetear cantidad
    setQuantity(1);
  };

  return (
    <div className="min-h-screen bg-background py-8 md:py-12">
      <div className="container mx-auto px-4">
        <div className="max-w-6xl mx-auto">
          {/* Breadcrumb */}
          <Breadcrumb
            items={[
              { label: 'Inicio', onClick: onHomeClick },
              { label: 'Tienda', onClick: onHomeClick },
              { label: productCategory, onClick: () => onCategoryClick?.(productCategory) },
              { label: productName },
            ]}
          />

          {/* Product Grid */}
          <div className="grid md:grid-cols-2 gap-8 md:gap-12 mb-16">
            {/* Image Gallery */}
            <div>
              {/* Main Image - Mobile Swipe */}
              <div
                className="relative mb-4 aspect-[3/4] rounded-3xl overflow-hidden bg-gradient-to-br from-muted to-muted/50"
                onTouchStart={(e) => {
                  const touch = e.touches[0];
                  const startX = touch.clientX;

                  const handleTouchMove = (moveEvent: TouchEvent) => {
                    const moveTouch = moveEvent.touches[0];
                    const diff = startX - moveTouch.clientX;

                    if (Math.abs(diff) > 50) {
                      if (diff > 0) {
                        nextImage();
                      } else {
                        prevImage();
                      }
                      document.removeEventListener('touchmove', handleTouchMove);
                    }
                  };

                  document.addEventListener('touchmove', handleTouchMove);
                  document.addEventListener('touchend', () => {
                    document.removeEventListener('touchmove', handleTouchMove);
                  }, { once: true });
                }}
              >
                <AnimatePresence mode="wait">
                  <motion.div
                    key={currentImage}
                    initial={{ opacity: 0, x: 100 }}
                    animate={{ opacity: 1, x: 0 }}
                    exit={{ opacity: 0, x: -100 }}
                    transition={{ duration: 0.3 }}
                    className="absolute inset-0"
                  >
                    <ImageWithFallback
                      src={productImages[currentImage]}
                      alt={`Product view ${currentImage + 1}`}
                      className="w-full h-full object-cover"
                    />
                  </motion.div>
                </AnimatePresence>

                {/* Navigation Arrows */}
                <button
                  onClick={prevImage}
                  className="absolute left-4 top-1/2 -translate-y-1/2 p-3 bg-background/80 backdrop-blur-sm rounded-full hover:bg-background transition-all active:scale-95"
                  style={{ minWidth: '44px', minHeight: '44px' }}
                  aria-label="Previous image"
                >
                  <ChevronLeft className="w-6 h-6" />
                </button>
                <button
                  onClick={nextImage}
                  className="absolute right-4 top-1/2 -translate-y-1/2 p-3 bg-background/80 backdrop-blur-sm rounded-full hover:bg-background transition-all active:scale-95"
                  style={{ minWidth: '44px', minHeight: '44px' }}
                  aria-label="Next image"
                >
                  <ChevronRight className="w-6 h-6" />
                </button>

                {/* Image Indicators */}
                <div className="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
                  {productImages.map((_, index) => (
                    <button
                      key={index}
                      onClick={() => setCurrentImage(index)}
                      className={`w-2 h-2 rounded-full transition-all ${
                        index === currentImage ? 'bg-primary w-6' : 'bg-white/50'
                      }`}
                      aria-label={`Go to image ${index + 1}`}
                    />
                  ))}
                </div>
              </div>

              {/* Thumbnail Grid - Desktop */}
              <div className="hidden md:grid grid-cols-4 gap-4">
                {productImages.map((img, index) => (
                  <button
                    key={index}
                    onClick={() => setCurrentImage(index)}
                    className={`relative aspect-square rounded-2xl overflow-hidden bg-gradient-to-br from-muted to-muted/50 transition-all hover:ring-2 hover:ring-primary ${
                      index === currentImage ? 'ring-2 ring-primary' : ''
                    }`}
                  >
                    <ImageWithFallback
                      src={img}
                      alt={`Product thumbnail ${index + 1}`}
                      className="w-full h-full object-cover"
                    />
                  </button>
                ))}
              </div>
            </div>

            {/* Product Info */}
            <div className="flex flex-col">
              <motion.div
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.6 }}
              >
                <h1 className="text-3xl md:text-4xl mb-2">{productName}</h1>
                <p className="text-sm text-muted-foreground mb-6">SKU: {productSKU}</p>

                <div className="mb-8">
                  <div className="mb-4">
                    <p className="text-3xl text-primary">Bs {productPrice}</p>
                    <p className="text-xl text-muted-foreground">
                      USD ${(parseFloat(productPrice.replace(/,/g, '')) / 6.96).toFixed(2)}
                    </p>
                  </div>

                  {/* Materiales */}
                  {productMaterials.length > 0 && (
                    <div className="mb-4">
                      <p className="text-sm font-medium mb-2">Materiales:</p>
                      <div className="flex flex-wrap gap-2">
                        {productMaterials.map((material, idx) => (
                          <span
                            key={idx}
                            className="px-3 py-1 bg-muted rounded-full text-xs"
                          >
                            {material}
                          </span>
                        ))}
                      </div>
                    </div>
                  )}

                  {/* Descripción corta */}
                  <div className="text-muted-foreground leading-relaxed">
                    <p className="mb-4">{shortDescription}</p>

                    {/* Descripción larga y características */}
                    {showFullDescription && (longDescription || features.length > 0) && (
                      <motion.div
                        initial={{ opacity: 0, height: 0 }}
                        animate={{ opacity: 1, height: 'auto' }}
                        exit={{ opacity: 0, height: 0 }}
                        className="mb-4 space-y-3"
                      >
                        {longDescription && <p>{longDescription}</p>}

                        {features.length > 0 && (
                          <>
                            <p>
                              <strong>Características:</strong>
                            </p>
                            <ul className="list-disc list-inside space-y-1 ml-2">
                              {features.map((feature, idx) => (
                                <li key={idx}>{feature}</li>
                              ))}
                            </ul>
                          </>
                        )}
                      </motion.div>
                    )}

                    {(longDescription || features.length > 0) && (
                      <button
                        onClick={() => setShowFullDescription(!showFullDescription)}
                        className="text-primary hover:underline font-medium"
                      >
                        {showFullDescription ? 'Ver menos' : 'Ver descripción completa'}
                      </button>
                    )}
                  </div>
                </div>

                {/* Color Selection */}
                <div className="mb-6">
                  <h3 className="mb-3">
                    Color: <span className="text-muted-foreground font-normal">{productColors[selectedColorIndex]}</span>
                  </h3>
                  <div className="flex gap-3 flex-wrap">
                    {productColors.map((color, index) => {
                      const colorMap: Record<string, string> = {
                        'Negro': '#000000',
                        'Blanco': '#FFFFFF',
                        'Rosa': '#FFC0CB',
                        'Rojo': '#800020',
                        'Nude': '#E8B89A',
                        'Beige': '#F5F5DC',
                        'Gris': '#808080',
                        'Azul': '#0000FF',
                        'Azul Marino': '#000080',
                        'Azul Oscuro': '#00008B',
                        'Verde': '#008000',
                        'Vino': '#722F37',
                      };

                      const hexColor = colorMap[color] || '#CCCCCC';

                      return (
                        <motion.button
                          key={color}
                          whileHover={{ scale: 1.1 }}
                          whileTap={{ scale: 0.9 }}
                          onClick={() => setSelectedColorIndex(index)}
                          className={`relative w-12 h-12 rounded-full transition-all ${
                            index === selectedColorIndex ? 'ring-2 ring-primary ring-offset-2' : ''
                          }`}
                          style={{ backgroundColor: hexColor }}
                          aria-label={color}
                          title={color}
                        >
                          {(hexColor === '#FFFFFF' || hexColor === '#F5F5DC') && (
                            <div className="absolute inset-0 border border-border rounded-full" />
                          )}
                        </motion.button>
                      );
                    })}
                  </div>
                </div>

                {/* Size Selection */}
                <div className="mb-6">
                  <h3 className="mb-3">
                    Talla: <span className="text-muted-foreground font-normal">{productSizes[selectedSizeIndex]}</span>
                  </h3>
                  <div className="flex gap-3 flex-wrap">
                    {productSizes.map((size, index) => (
                      <motion.button
                        key={size}
                        whileHover={{ scale: 1.05 }}
                        whileTap={{ scale: 0.95 }}
                        onClick={() => setSelectedSizeIndex(index)}
                        className={`px-6 py-3 rounded-full border-2 transition-all ${
                          index === selectedSizeIndex
                            ? 'bg-primary text-primary-foreground border-primary'
                            : 'border-border hover:border-primary'
                        }`}
                      >
                        {size}
                      </motion.button>
                    ))}
                  </div>
                </div>

                {/* Stock & Quantity */}
                <div className="mb-8">
                  <p className="text-sm text-muted-foreground mb-3">
                    Stock disponible: {stockAvailable}
                  </p>
                  <div className="flex items-center gap-4">
                    <div className="flex items-center border border-border rounded-full overflow-hidden">
                      <button
                        onClick={() => setQuantity(Math.max(1, quantity - 1))}
                        disabled={quantity <= 1}
                        className="p-3 hover:bg-muted transition-all disabled:opacity-30 disabled:cursor-not-allowed"
                        aria-label="Decrease quantity"
                      >
                        <Minus className="w-5 h-5" />
                      </button>
                      <span className="px-6 font-medium">{quantity}</span>
                      <button
                        onClick={() => setQuantity(Math.min(stockAvailable, quantity + 1))}
                        disabled={quantity >= stockAvailable}
                        className="p-3 hover:bg-muted transition-all disabled:opacity-30 disabled:cursor-not-allowed"
                        aria-label="Increase quantity"
                      >
                        <Plus className="w-5 h-5" />
                      </button>
                    </div>
                  </div>
                </div>

                {/* Add to Cart Button */}
                <motion.button
                  whileHover={{ scale: 1.02 }}
                  whileTap={{ scale: 0.98 }}
                  onClick={handleAddToCart}
                  disabled={addedToCart}
                  className={`w-full py-4 rounded-full flex items-center justify-center gap-3 hover:shadow-lg transition-all ${
                    addedToCart
                      ? 'bg-green-600 text-white'
                      : 'bg-primary text-primary-foreground'
                  }`}
                >
                  {addedToCart ? (
                    <>
                      <Check className="w-6 h-6" />
                      ¡Añadido al carrito!
                    </>
                  ) : (
                    <>
                      <ShoppingCart className="w-6 h-6" />
                      Añadir al carrito
                    </>
                  )}
                </motion.button>
              </motion.div>
            </div>
          </div>

          {/* Recommendations */}
          <div>
            <motion.div
              initial={{ opacity: 0, y: 30 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.6 }}
            >
              <h2 className="text-2xl md:text-3xl mb-8">Recomendados para ti</h2>
            </motion.div>

            <div className="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
              {products
                .filter(p => p.category === productCategory && p.sku !== productSKU)
                .slice(0, 4)
                .map((product, index) => (
                  <ProductCard
                    key={product.id}
                    name={product.name}
                    price={product.price}
                    sku={product.sku}
                    imageIndex={index + 4}
                    isNew={product.isNew}
                    discount={product.discount}
                    onClick={() => onProductClick?.(product)}
                  />
                ))}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
