import { createContext, useContext, useState, ReactNode } from 'react';

export interface CartItem {
  id: string;
  name: string;
  price: string;
  sku: string;
  color: string;
  size: string;
  quantity: number;
  image?: string;
}

interface CartContextType {
  items: CartItem[];
  addItem: (item: CartItem) => void;
  removeItem: (id: string, color: string, size: string) => void;
  updateQuantity: (id: string, color: string, size: string, quantity: number) => void;
  clearCart: () => void;
  getTotal: () => number;
  getItemCount: () => number;
}

const CartContext = createContext<CartContextType | undefined>(undefined);

export function CartProvider({ children }: { children: ReactNode }) {
  const [items, setItems] = useState<CartItem[]>([]);

  const addItem = (newItem: CartItem) => {
    setItems((currentItems) => {
      // Verificar si el item ya existe con el mismo color y talla
      const existingIndex = currentItems.findIndex(
        (item) => item.id === newItem.id && item.color === newItem.color && item.size === newItem.size
      );

      if (existingIndex > -1) {
        // Si existe, incrementar la cantidad
        const updatedItems = [...currentItems];
        updatedItems[existingIndex].quantity += newItem.quantity;
        return updatedItems;
      } else {
        // Si no existe, agregar como nuevo item
        return [...currentItems, newItem];
      }
    });
  };

  const removeItem = (id: string, color: string, size: string) => {
    setItems((currentItems) =>
      currentItems.filter(
        (item) => !(item.id === id && item.color === color && item.size === size)
      )
    );
  };

  const updateQuantity = (id: string, color: string, size: string, quantity: number) => {
    if (quantity <= 0) {
      removeItem(id, color, size);
      return;
    }

    setItems((currentItems) =>
      currentItems.map((item) =>
        item.id === id && item.color === color && item.size === size
          ? { ...item, quantity }
          : item
      )
    );
  };

  const clearCart = () => {
    setItems([]);
  };

  const getTotal = () => {
    return items.reduce((total, item) => {
      const price = parseFloat(item.price.replace(',', ''));
      return total + price * item.quantity;
    }, 0);
  };

  const getItemCount = () => {
    return items.reduce((count, item) => count + item.quantity, 0);
  };

  return (
    <CartContext.Provider
      value={{
        items,
        addItem,
        removeItem,
        updateQuantity,
        clearCart,
        getTotal,
        getItemCount,
      }}
    >
      {children}
    </CartContext.Provider>
  );
}

export function useCart() {
  const context = useContext(CartContext);
  if (context === undefined) {
    throw new Error('useCart must be used within a CartProvider');
  }
  return context;
}
