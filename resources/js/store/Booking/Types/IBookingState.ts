import { IBookingDates } from '@/Types/IBookingAvailability'
import { ICalculateBookingInfoWithBookableTitle } from '@/Types/ICalculateBooking'
import { ICheckoutPeron } from '@/Types/ICheckout'

export interface IBookingState {
    abilityBooking: {
        dateRange: IBookingDates | null,
    }
    basket: ICalculateBookingInfoWithBookableTitle[],
    checkoutPerson: ICheckoutPeron|null,
}
