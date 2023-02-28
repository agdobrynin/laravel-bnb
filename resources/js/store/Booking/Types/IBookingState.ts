import { IBookingDates } from '@/Types/IBookingAvailability'
import { ICalculateBookingInfo } from '@/Types/ICalculateBooking'

export interface IBookingState {
    abilityBooking: {
        dateRange: IBookingDates | null,
    }
    basket: ICalculateBookingInfo[],
}
