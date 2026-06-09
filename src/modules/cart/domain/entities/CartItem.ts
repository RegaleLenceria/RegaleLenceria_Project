export interface CartItem {
  id: string;
  name: string;
  price: string; // "150.00" style string
  sku: string;
  color: string;
  size: string;
  quantity: number;
  image?: string;
}
