export interface ApiColor {
  codigo_color: string;
  color_hex: string;
  descripcion: string;
  tipo_color: 'entero' | 'estampado';
  img_estampado: string;
}

export interface ApiProduct {
  id: string;
  name: string;
  sku: string;
  price: string;
  category: string;
  subcategory: string;
  brand: string;
  gender: string;
  description: {
    short: string;
    long: string;
    features: string[];
  };
  images: string[];
  sizes: string[];
  colors: ApiColor[];
  stock: number;
  isNew: boolean;
  discount: number | null;
}
