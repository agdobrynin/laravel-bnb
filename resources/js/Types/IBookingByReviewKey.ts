export interface IBookingByReviewKey {
    data?: IBookingByReviewKeyBase
}
export interface IBookingByReviewKeyBase {
    id: string,
    /**
     * Booking from date format YYYY-DD-MM
     */
    start: string,
    /**
     * Booking to date format YYYY-DD-MM
     */
    end: string,
    bookable?: IBookingByReviewKeyBookableInfo,
    user: null | {
        id: number,
        name: string,
        email: string,
    }
}
export interface IBookingByReviewKeyBookableInfo {
    id: string,
    title: string,
}
