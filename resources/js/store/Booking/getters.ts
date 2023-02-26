import { DateRange } from '@/Models/DateRange'
import { IBookingState } from '@/store/Booking/Types/IBookingState'

export default {
    lastSearchBookingDates: (state: IBookingState): DateRange => {
            const dateRange = new DateRange(new Date(), 4)

            if (state.abilityBooking.dateRange) {
                dateRange.start = state.abilityBooking.dateRange.start
                dateRange.end = state.abilityBooking.dateRange.end
            }

            return dateRange
    }
}
