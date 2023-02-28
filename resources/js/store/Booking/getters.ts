import { GetterTree } from 'vuex'

import { DateRange } from '@/Models/DateRange'
import { IBookingState } from '@/store/Booking/Types/IBookingState'

const getters: GetterTree<IBookingState, any> = {
    lastSearchBookingDates: (state: IBookingState): DateRange => {
            const dateRange = new DateRange(new Date(), 4)

            if (state.abilityBooking.dateRange) {
                dateRange.start = state.abilityBooking.dateRange.start
                dateRange.end = state.abilityBooking.dateRange.end
            }

            return dateRange
    }
}

export default getters
