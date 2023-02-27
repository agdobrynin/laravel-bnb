export interface ICalculateBooking {
    data: {
        breakdown: {
            weekend?: ICalculateBreakdownItem,
            regular?: ICalculateBreakdownItem,
        }
        /**
         * Format as YYYY-MM-DD
         */
        dateStart: string,
        /**
         * Format as YYYY-MM-DD
         */
        dateEnd: string,
    }
}

export interface ICalculateBreakdownItem {
    pricePerDay: number,
    days: number,
}
