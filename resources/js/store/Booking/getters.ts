import { GetterTree } from 'vuex'

import { DateRange } from '@/Models/DateRange'
import { IBookingState } from '@/store/Booking/Types/IBookingState'
import { ICalculateBookingInfo } from '@/Types/ICalculateBooking'

const getters: GetterTree<IBookingState, any> = {
    lastSearchBookingDates: (state: IBookingState): DateRange => {
            const dateRange = new DateRange(new Date(), 4)

            if (state.abilityBooking.dateRange) {
                dateRange.start = state.abilityBooking.dateRange.start
                dateRange.end = state.abilityBooking.dateRange.end
            }

            return dateRange
    },

    basketCount: (state: IBookingState): number => state.basket.length,

    inBasket: (state: IBookingState) => (bookableId: string): ICalculateBookingInfo | undefined => {
        return state.basket.find((item: ICalculateBookingInfo) => item.bookableId === bookableId)
    },

    hasInBasket: (state: IBookingState, getters) => (bookableId: string): boolean =>  Boolean(getters.inBasket(bookableId)),

    basket: (state: IBookingState): ICalculateBookingInfo[] => state.basket,
}

export default getters
