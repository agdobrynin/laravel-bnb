export interface ICalculateBooking {
    data: {
        calculate: ICalculateBookingInfo,
    }
}

export interface ICalculateBookingInfo {
    bookableId: string,
    breakdown?: IBreakdownPrice,
    /**
     * Total price
     */
    totalPrice?: number,
    /**
     * Format as YYYY-MM-DD
     */
    dateStart: string,
    /**
     * Format as YYYY-MM-DD
     */
    dateEnd: string,
}

export interface ICalculateBookingInfoWithBookableTitle extends ICalculateBookingInfo {
    bookableTitle: string,
}

export interface IBreakdownPrice {
    [BreakdownPriceEnum.WEEKEND]?: ICalculateBreakdownItem,
    [BreakdownPriceEnum.REGULAR]?: ICalculateBreakdownItem,
}

export enum BreakdownPriceEnum {
    WEEKEND = 'weekend',
    REGULAR = 'regular',
}

export interface ICalculateBreakdownItem {
    pricePerDay: number,
    days: number,
    totalPrice: number,
}
