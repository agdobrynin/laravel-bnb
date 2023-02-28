import { MutationTree } from 'vuex'

import { IBookingState } from '@/store/Booking/Types/IBookingState'
import { IBookingDates } from '@/Types/IBookingAvailability'
import { ICalculateBookingInfo } from '@/Types/ICalculateBooking'

const mutations: MutationTree<IBookingState> = {
    lastSearchBookingDates(state: IBookingState, payload: IBookingDates | null): void {
        state.abilityBooking.dateRange = payload
    },

    addToBasket(state: IBookingState, item: ICalculateBookingInfo): void {
        state.basket.push(item)
    },

    removeFromBasket(state: IBookingState, bookableId: string): void {
        state.basket = state.basket.filter((item: ICalculateBookingInfo) => item.bookableId !== bookableId)
    }
}

export default mutations
