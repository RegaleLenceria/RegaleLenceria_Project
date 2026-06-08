import { useState } from 'react';
import { motion } from 'motion/react';
import { Trash2, Plus, Minus, ShoppingBag } from 'lucide-react';
import { useCart } from '../context/CartContext';
import { ImageWithFallback } from './ImageWithFallback';
import { formatPrice } from '../utils/currency';

interface CartPageProps {
  onBackClick?: () => void;
}

export function CartPage({ onBackClick }: CartPageProps) {
  const { items, removeItem, updateQuantity, clearCart, getTotal } = useCart();
  const [billingName, setBillingName] = useState('');
  const [billingNit, setBillingNit] = useState('');

  const handleWhatsAppOrder = () => {
    if (items.length === 0) return;

    const total = getTotal();
    let message = '¡Hola! Me gustaría hacer el siguiente pedido:\n\n';

    // Agregar datos de facturación si están presentes
    if (billingName.trim() || billingNit.trim()) {
      message += '*DATOS DE FACTURACIÓN:*\n';
      if (billingName.trim()) message += `• Nombre: ${billingName.trim()}\n`;
      if (billingNit.trim()) message += `• NIT/CI: ${billingNit.trim()}\n`;
      message += '\n';
    }

    message += '*DETALLE DEL PEDIDO:*\n';
    items.forEach((item, index) => {
      const itemPrice = parseFloat(item.price.replace(',', ''));
      const itemTotal = itemPrice * item.quantity;
      message += `${index + 1}. ${item.name}\n`;
      message += `   SKU: ${item.sku}\n`;
      message += `   Color: ${item.color}\n`;
      message += `   Talla: ${item.size}\n`;
      message += `   Cantidad: ${item.quantity}\n`;
      message += `   Precio unitario: Bs ${item.price}\n`;
      message += `   Subtotal: Bs ${itemTotal.toFixed(2)}\n\n`;
    });

    message += `*TOTAL: Bs ${total.toFixed(2)}*\n`;
    message += `*TOTAL USD: $${(total / 6.96).toFixed(2)}*`;

    window.open(`https://wa.me/59171234567?text=${encodeURIComponent(message)}`, '_blank');
  };

  if (items.length === 0) {
    return (
      <div className="min-h-screen bg-background py-8 md:py-12">
        <div className="container mx-auto px-4">
          <div className="max-w-4xl mx-auto">
            <div className="flex items-center justify-between mb-8">
              <h1 className="text-3xl md:text-4xl">Carrito de Compras</h1>
              <button
                onClick={onBackClick}
                className="text-muted-foreground hover:text-primary transition-colors"
              >
                ← Volver a la tienda
              </button>
            </div>

            <motion.div
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              className="text-center py-20"
            >
              <ShoppingBag className="w-24 h-24 mx-auto mb-6 text-muted-foreground" />
              <h2 className="text-2xl mb-4">Tu carrito está vacío</h2>
              <p className="text-muted-foreground mb-8">
                Agrega productos a tu carrito para continuar con tu compra
              </p>
              <button
                onClick={onBackClick}
                className="bg-primary text-primary-foreground px-8 py-3 rounded-full hover:shadow-lg transition-all"
              >
                Explorar productos
              </button>
            </motion.div>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-background py-8 md:py-12">
      <div className="container mx-auto px-4">
        <div className="max-w-4xl mx-auto">
          {/* Header */}
          <div className="flex items-center justify-between mb-8">
            <h1 className="text-3xl md:text-4xl">
              Carrito ({items.reduce((acc, item) => acc + item.quantity, 0)})
            </h1>
            <button
              onClick={onBackClick}
              className="text-muted-foreground hover:text-primary transition-colors"
            >
              ← Continuar comprando
            </button>
          </div>

          {/* Cart Items */}
          <div className="space-y-4 mb-8">
            {items.map((item) => {
              const itemPrice = parseFloat(item.price.replace(',', ''));
              const itemTotal = itemPrice * item.quantity;

              return (
                <motion.div
                  key={`${item.id}-${item.color}-${item.size}`}
                  initial={{ opacity: 0, y: 20 }}
                  animate={{ opacity: 1, y: 0 }}
                  exit={{ opacity: 0, x: -100 }}
                  className="bg-muted/30 rounded-2xl p-4 md:p-6"
                >
                  <div className="flex gap-4">
                    {/* Image */}
                    <div className="w-24 h-24 md:w-32 md:h-32 rounded-xl overflow-hidden bg-gradient-to-br from-muted to-muted/50 flex-shrink-0">
                      {item.image && (
                        <ImageWithFallback
                          src={item.image}
                          alt={item.name}
                          className="w-full h-full object-cover"
                        />
                      )}
                    </div>

                    {/* Info */}
                    <div className="flex-1 min-w-0">
                      <div className="flex justify-between gap-4 mb-2">
                        <div className="flex-1 min-w-0">
                          <h3 className="font-medium mb-1 truncate">{item.name}</h3>
                          <p className="text-xs text-muted-foreground mb-2">SKU: {item.sku}</p>
                          <div className="flex flex-wrap gap-2 text-sm">
                            <span className="text-muted-foreground">
                              Color: <span className="text-foreground">{item.color}</span>
                            </span>
                            <span className="text-muted-foreground">•</span>
                            <span className="text-muted-foreground">
                              Talla: <span className="text-foreground">{item.size}</span>
                            </span>
                          </div>
                        </div>

                        {/* Delete Button - Desktop */}
                        <button
                          onClick={() => removeItem(item.id, item.color, item.size)}
                          className="hidden md:block p-2 hover:bg-destructive/10 rounded-lg transition-colors text-destructive h-fit"
                          aria-label="Eliminar del carrito"
                        >
                          <Trash2 className="w-5 h-5" />
                        </button>
                      </div>

                      {/* Quantity and Price */}
                      <div className="flex items-center justify-between mt-4">
                        <div className="flex items-center border border-border rounded-full overflow-hidden">
                          <button
                            onClick={() =>
                              updateQuantity(item.id, item.color, item.size, item.quantity - 1)
                            }
                            disabled={item.quantity <= 1}
                            className="p-2 hover:bg-muted transition-all disabled:opacity-30"
                            aria-label="Disminuir cantidad"
                          >
                            <Minus className="w-4 h-4" />
                          </button>
                          <span className="px-4 font-medium min-w-[3ch] text-center">
                            {item.quantity}
                          </span>
                          <button
                            onClick={() =>
                              updateQuantity(item.id, item.color, item.size, item.quantity + 1)
                            }
                            className="p-2 hover:bg-muted transition-all"
                            aria-label="Aumentar cantidad"
                          >
                            <Plus className="w-4 h-4" />
                          </button>
                        </div>

                        <div className="text-right">
                          <p className="text-xs text-muted-foreground mb-1">
                            Bs {item.price} c/u
                          </p>
                          <p className="text-lg font-medium text-primary">
                            {formatPrice(itemTotal)}
                          </p>
                        </div>
                      </div>

                      {/* Delete Button - Mobile */}
                      <button
                        onClick={() => removeItem(item.id, item.color, item.size)}
                        className="md:hidden w-full mt-3 py-2 text-destructive hover:bg-destructive/10 rounded-lg transition-colors text-sm flex items-center justify-center gap-2"
                      >
                        <Trash2 className="w-4 h-4" />
                        Eliminar
                      </button>
                    </div>
                  </div>
                </motion.div>
              );
            })}
          </div>

          {/* Datos de Facturación */}
          <div className="bg-muted/30 rounded-2xl p-6 mb-6">
            <h3 className="text-lg font-medium mb-4">Datos de Facturación</h3>
            <div className="grid md:grid-cols-2 gap-4">
              <div>
                <label htmlFor="billingName" className="block text-sm font-medium text-muted-foreground mb-1">
                  Nombre de Facturación
                </label>
                <input
                  type="text"
                  id="billingName"
                  value={billingName}
                  onChange={(e) => setBillingName(e.target.value)}
                  placeholder="Ej. Juan Pérez"
                  className="w-full px-4 py-2.5 rounded-xl border border-border bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all text-sm"
                />
              </div>
              <div>
                <label htmlFor="billingNit" className="block text-sm font-medium text-muted-foreground mb-1">
                  NIT o Carnet de Identidad
                </label>
                <input
                  type="text"
                  id="billingNit"
                  value={billingNit}
                  onChange={(e) => setBillingNit(e.target.value)}
                  placeholder="Ej. 1234567"
                  className="w-full px-4 py-2.5 rounded-xl border border-border bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all text-sm"
                />
              </div>
            </div>
          </div>

          {/* Summary */}
          <div className="bg-muted/30 rounded-2xl p-6 mb-6">
            <div className="space-y-3 mb-6">
              <div className="flex justify-between text-muted-foreground">
                <span>Subtotal</span>
                <span>{formatPrice(getTotal())}</span>
              </div>
              <div className="flex justify-between text-muted-foreground">
                <span>Envío</span>
                <span>A calcular</span>
              </div>
              <div className="border-t border-border pt-3 flex justify-between text-xl font-medium">
                <span>Total</span>
                <div className="text-right">
                  <p className="text-primary">{formatPrice(getTotal())}</p>
                  <p className="text-sm text-muted-foreground">
                    USD ${(getTotal() / 6.96).toFixed(2)}
                  </p>
                </div>
              </div>
            </div>

            {/* WhatsApp Button */}
            <motion.button
              whileHover={{ scale: 1.02 }}
              whileTap={{ scale: 0.98 }}
              onClick={handleWhatsAppOrder}
              className="w-full bg-primary text-primary-foreground py-4 rounded-full flex items-center justify-center gap-3 hover:shadow-lg transition-all mb-3"
            >
              <svg className="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
              </svg>
              Pedir por WhatsApp
            </motion.button>

            <button
              onClick={clearCart}
              className="w-full text-destructive hover:bg-destructive/10 py-2 rounded-full transition-colors text-sm"
            >
              Vaciar carrito
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}
