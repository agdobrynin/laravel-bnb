import { IBookingDates } from '@/Types/IBookingAvailability'
import { ICalculateBookingInfoWithBookableTitle } from '@/Types/ICalculateBooking'

export interface IBookingState {
    abilityBooking: {
        dateRange: IBookingDates | null,
    }
    basket: ICalculateBookingInfoWithBookableTitle[],
}
