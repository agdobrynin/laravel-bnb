import { IBookingDates } from '@/Types/IBookingAvailability'

export interface IBookingState {
    abilityBooking: {
        dateRange: IBookingDates | null,
    }
}
