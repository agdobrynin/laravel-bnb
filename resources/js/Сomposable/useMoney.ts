export const priceUsdFormat = (price: number) => new Intl.NumberFormat(
    'us-US',
    {
        style: 'currency',
        currency: 'USD',
        maximumFractionDigits: 0,
    }).format(price)
