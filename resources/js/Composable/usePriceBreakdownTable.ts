import { priceUsdFormat } from '@/Composable/useMoney'
import { BreakdownPriceEnum, ICalculateBookingInfo, ICalculateBreakdownItem } from '@/Types/ICalculateBooking'
import { IFormattedTable } from '@/Types/IFormattedTable'

export const usePriceBreakdownTable = (calculateBooking: ICalculateBookingInfo): IFormattedTable[] => {
    return Object.entries(calculateBooking.breakdown || {})
        .reduce((acc: IFormattedTable[], [breakdownPrice, val]) => {
            const item: ICalculateBreakdownItem = val
            const title = titleBreakdown(breakdownPrice as BreakdownPriceEnum)

            acc.push(makeItem(title, item.days, item.pricePerDay, item.totalPrice))

            return acc
        }, [])
}

const titleBreakdown = (item: BreakdownPriceEnum) => {
    const titles = {
        [BreakdownPriceEnum.REGULAR]: 'Regular days',
        [BreakdownPriceEnum.WEEKEND]: 'Weekend days',
    }

    return titles[item] || 'Unknown days'
}
const makeItem = (title: string, days: number, pricePerDay: number, total: number): IFormattedTable => {
    return {
        title,
        days: `${days} days`,
        pricePerDay: priceUsdFormat(pricePerDay),
        total: priceUsdFormat(total)
    }
}

