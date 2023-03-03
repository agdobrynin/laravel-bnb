import { MutationTree } from 'vuex'

import { IBookingState } from '@/store/Booking/Types/IBookingState'
import { IBookingDates } from '@/Types/IBookingAvailability'
import { ICalculateBookingInfo, ICalculateBookingInfoWithBookableTitle } from '@/Types/ICalculateBooking'
import { ICheckoutPeron } from '@/Types/ICheckout'

const mutations: MutationTree<IBookingState> = {
    lastSearchBookingDates(state: IBookingState, payload: IBookingDates | null): void {
        state.abilityBooking.dateRange = payload
    },

    addToBasket(state: IBookingState, item: ICalculateBookingInfoWithBookableTitle): void {
        state.basket.push(item)
    },

    removeFromBasket(state: IBookingState, bookableId: string): void {
        state.basket = state.basket.filter((item: ICalculateBookingInfo) => item.bookableId !== bookableId)
    },

    emptyBasket(state: IBookingState): void {
        state.basket = []
    },

    setCheckoutPerson(state: IBookingState, person: ICheckoutPeron|null): void {
        state.checkoutPerson = person
    }
}

export default mutations
