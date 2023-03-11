export interface ICheckout {
    person: ICheckoutPeron,
    bookings: ICheckoutBookingItem[]
}

export interface ICheckoutPeron {
    firstName: string,
    lastName: string,
    address: string,
    email: string,
    phone: string | null | undefined,

}
export interface ICheckoutBookingItem {
    bookableId: string,
    start: string,
    end: string,
}

export interface IBasketItem {
    bookableId: string,
    title: string,
    total: number,
    start: string | null,
    end: string | null,
    days: number,
}

export interface IBasketTable {
    items: IBasketItem[],
    total: string,
}

export interface ICheckoutSuccess {
    data: ICheckoutSuccessItem[]
}

export interface ICheckoutSuccessItem {
    /**
     * date from formta YYYY-MM-YY
     */
    start: string,
    /**
     * date from formta YYYY-MM-YY
     */
    end: string,
    /**
     * Total booking price
     */
    price: number,
    /**
     * Review key for feedback.
     */
    reviewKey?: string,
    bookable: {
        id: string,
        title: string,
    }
}
