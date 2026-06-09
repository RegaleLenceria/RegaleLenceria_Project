export interface ProductColor {
  name: string;
  hex: string;
  type: 'entero' | 'estampado';
  image?: string;
}

export interface Product {
  id: string;
  name: string;
  sku: string;
  price: string; // Keep string for direct UI compatibility (e.g., "150.00")
  category: string;
  subcategory: string;
  brand?: string;
  gender?: string;
  description: {
    short: string;
    long: string;
    features: string[];
  };
  images: string[];
  sizes: string[];
  colors: ProductColor[];
  stock: number;
  isNew: boolean;
  discount: number | null;
}
