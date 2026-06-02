// Tasa de cambio: 1 USD = 6.96 BOB
const EXCHANGE_RATE = 6.96;

export function formatPrice(priceInBOB: number): string {
  const priceInUSD = priceInBOB / EXCHANGE_RATE;

  return {
    bob: `Bs ${priceInBOB.toFixed(2)}`,
    usd: `$${priceInUSD.toFixed(2)}`,
    both: `Bs ${priceInBOB.toFixed(2)} / $${priceInUSD.toFixed(2)}`
  }.both;
}

export function parsePriceString(priceString: string): number {
  // Extrae el número del string "$159.900" o "Bs 159.900"
  return parseFloat(priceString.replace(/[^0-9.]/g, ''));
}
