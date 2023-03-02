export interface ICheckout {
    person: ICheckoutPeron,
    booking: ICheckoutBookingItem[]
}

export interface ICheckoutPeron {
    firstName: string,
    lastName: string,
    address: string,
    email: string,
    phone: string | null,

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
    total: number,
}
