import { IPagination } from '@/Types/IPagination'

export interface IBookingWithoutReviewByUserCollection extends IPagination {
    data: IBookingWithoutReviewByUser[]
}

export interface IBookingWithoutReviewByUser {
    bookable: {
        id: string,
        title: string,
    },
    /**
     * Date booking start
     */
    start: string,
    /**
     * Date booking end
     */
    end: string,
    price: number,
    reviewKey: string,
}
