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

    hasInBasket: (state: IBookingState) => (bookableId: string): boolean => {
        return Boolean(state.basket.find((item: ICalculateBookingInfo) => item.bookableId === bookableId))
    }
}

export default getters
