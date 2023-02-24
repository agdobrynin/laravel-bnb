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
}
export interface IBookingByReviewKeyBookableInfo {
    id: string,
    title: string,
}
