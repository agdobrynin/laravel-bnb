import { IBookingState } from '@/store/Booking/Types/IBookingState'
import { IBookingDates } from '@/Types/IBookingAvailability'

export default {
    lastSearchBookingDates(state: IBookingState, payload: IBookingDates | null): void {
        state.abilityBooking.dateRange = payload
    }
}
