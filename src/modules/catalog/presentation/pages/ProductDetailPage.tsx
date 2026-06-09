import { useState, useEffect } from 'react';
import { motion, AnimatePresence } from 'motion/react';
import { ChevronLeft, ChevronRight, Plus, Minus, ShoppingCart, Check } from 'lucide-react';
import { ProductCard } from '../components/ProductCard';
import { ImageWithFallback } from '../../../shared/presentation/components/ImageWithFallback';
import { Breadcrumb } from '../../../shared/presentation/components/Breadcrumb';
import { useCart } from '../../../cart/presentation/context/CartContext';
import { useProductDetail } from '../hooks/useProductDetail';
import { Product } from '../../domain/entities/Product';

interface ProductDetailPageProps {
  product?: any;
  onProductClick?: (product?: Product) => void;
  onHomeClick?: () => void;
  onCategoryClick?: (category: string) => void;
}

export function ProductDetailPage({ product: productProp, onProductClick, onHomeClick, onCategoryClick }: ProductDetailPageProps) {
  const { addItem } = useCart();
  const { product: dbProduct, loading } = useProductDetail(productProp?.id);

  const [currentImage, setCurrentImage] = useState(0);
  const [selectedColorIndex, setSelectedColorIndex] = useState(0);
  const [selectedSizeIndex, setSelectedSizeIndex] = useState(0);
  const [quantity, setQuantity] = useState(1);
  const [showFullDescription, setShowFullDescription] = useState(false);
  const [addedToCart, setAddedToCart] = useState(false);

  const [zoomStyle, setZoomStyle] = useState<React.CSSProperties>({
    transformOrigin: 'center center',
    transform: 'scale(1)',
  });
  const [isZoomed, setIsZoomed] = useState(false);

  const handleMouseMove = (e: React.MouseEvent<HTMLDivElement>) => {
    if (window.innerWidth < 768) return; // desktop only zoom
    const { left, top, width, height } = e.currentTarget.getBoundingClientRect();
    const x = ((e.clientX - left) / width) * 100;
    const y = ((e.clientY - top) / height) * 100;

    setZoomStyle({
      transformOrigin: `${x}% ${y}%`,
      transform: 'scale(2.2)', // 2.2x zoom
    });
  };

  const handleMouseEnter = () => {
    if (window.innerWidth < 768) return;
    setIsZoomed(true);
  };

  const handleMouseLeave = () => {
    setIsZoomed(false);
    setZoomStyle({
      transformOrigin: 'center center',
      transform: 'scale(1)',
    });
  };

  const productData = dbProduct || productProp;
  const productName = productData?.name || 'Conjunto Victoria';
  const productCategory = productData?.category || 'Lencería';
  const productSubcategory = productData?.subcategory || 'Conjunto';
  const productPrice = productData?.price || '1111.00';
  const productSKU = productData?.sku || 'LS-2024-123';
  const productImages = productData?.images || [];
  const productColors = productData?.colors || [];
  const productSizes = productData?.sizes || [];
  const productMaterials = productData?.material || [];
  const stockAvailable = productData?.stock || 5;
  const shortDescription = productData?.description?.short || 'Elegante conjunto de lencería importada, confeccionado con materiales de la más alta calidad.';
  const longDescription = productData?.description?.long || '';
  const features = productData?.description?.features || [];

  const colorsList = (productColors || []).map((c: any) => {
    if (typeof c === 'string') {
      return { name: c, hex: '' };
    }
    return {
      name: c.name || c.descripcion || c.codigo_color,
      hex: c.hex || c.color_hex || '#CCCCCC',
      tipo: c.type || c.tipo_color,
      img: c.image || c.img_estampado
    };
  });

  const handleAddToCart = () => {
    addItem({
      id: productData?.id || productSKU,
      name: productName,
      price: productPrice,
      sku: productSKU,
      color: colorsList[selectedColorIndex]?.name || '',
      size: productSizes[selectedSizeIndex] || '',
      quantity: quantity,
      image: productImages[0] || '',
    });

    setAddedToCart(true);
    setTimeout(() => {
      setAddedToCart(false);
    }, 2000);

    setQuantity(1);
  };

  if (loading) {
    return (
      <div className="min-h-screen bg-background py-8 md:py-12 flex items-center justify-center">
        <div className="text-center">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary mx-auto mb-4"></div>
          <p className="text-muted-foreground">Cargando detalles del producto...</p>
        </div>
      </div>
    );
  }

  const nextImage = () => {
    if (productImages.length > 0) {
      setCurrentImage((prev) => (prev + 1) % productImages.length);
    }
  };

  const prevImage = () => {
    if (productImages.length > 0) {
      setCurrentImage((prev) => (prev - 1 + productImages.length) % productImages.length);
    }
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
              {/* Main Image */}
              <div
                className="relative mb-4 aspect-[3/4] rounded-3xl overflow-hidden bg-gradient-to-br from-muted to-muted/50 cursor-zoom-in"
                onMouseMove={handleMouseMove}
                onMouseEnter={handleMouseEnter}
                onMouseLeave={handleMouseLeave}
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
                {productImages.length > 0 && (
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
                        className="w-full h-full object-cover transition-transform duration-75 ease-out"
                        style={isZoomed ? zoomStyle : undefined}
                      />
                    </motion.div>
                  </AnimatePresence>
                )}

                {productImages.length > 1 && (
                  <>
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

                    <div className="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
                      {productImages.map((_: any, index: number) => (
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
                  </>
                )}
              </div>

              {/* Thumbnails */}
              {productImages.length > 1 && (
                <div className="hidden md:grid grid-cols-4 gap-4">
                  {productImages.map((img: any, index: number) => (
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
              )}
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

                  {productMaterials.length > 0 && (
                    <div className="mb-4">
                      <p className="text-sm font-medium mb-2">Materiales:</p>
                      <div className="flex flex-wrap gap-2">
                        {productMaterials.map((material: any, idx: number) => (
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

                  <div className="text-muted-foreground leading-relaxed">
                    <p className="mb-4">{shortDescription}</p>

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
                              {features.map((feature: any, idx: number) => (
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
                {colorsList.length > 0 && (
                  <div className="mb-6">
                    <h3 className="mb-3">
                      Color: <span className="text-muted-foreground font-normal">{colorsList[selectedColorIndex]?.name}</span>
                    </h3>
                    <div className="flex gap-3 flex-wrap">
                      {colorsList.map((color: any, index: number) => {
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

                        const hexColor = color.hex || colorMap[color.name] || '#CCCCCC';

                        return (
                          <motion.button
                            key={`${color.name}-${index}`}
                            whileHover={{ scale: 1.1 }}
                            whileTap={{ scale: 0.9 }}
                            onClick={() => setSelectedColorIndex(index)}
                            className={`relative w-12 h-12 rounded-full transition-all ${
                              index === selectedColorIndex ? 'ring-2 ring-primary ring-offset-2' : ''
                            }`}
                            style={{ backgroundColor: hexColor }}
                            aria-label={color.name}
                            title={color.name}
                          >
                            {(hexColor === '#FFFFFF' || hexColor === '#F5F5DC') && (
                              <div className="absolute inset-0 border border-border rounded-full" />
                            )}
                          </motion.button>
                        );
                      })}
                    </div>
                  </div>
                )}

                {/* Size Selection */}
                {productSizes.length > 0 && (
                  <div className="mb-6">
                    <h3 className="mb-3">
                      Talla: <span className="text-muted-foreground font-normal">{productSizes[selectedSizeIndex]}</span>
                    </h3>
                    <div className="flex gap-3 flex-wrap">
                      {productSizes.map((size: any, index: number) => (
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
                )}

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

                {/* Add to Cart */}
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
        </div>
      </div>
    </div>
  );
}
