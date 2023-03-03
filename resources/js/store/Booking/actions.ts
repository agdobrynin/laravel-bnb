import { ActionContext, ActionTree } from 'vuex'

import { IBookingState } from '@/store/Booking/Types/IBookingState'
import { IBookingDates } from '@/Types/IBookingAvailability'
import { ICalculateBookingInfoWithBookableTitle } from '@/Types/ICalculateBooking'
import { ICheckoutPeron } from '@/Types/ICheckout'


const KEY_LAST_SEARCH_BOOKING_DATES = 'LastSearchBookingDates'
const KEY_BOOKING_BASKET = 'BookingBasket'
const KEY_CHECKOUT_PERSON = 'CheckoutPerson'

const actions: ActionTree<IBookingState, any> = {
    async saveLastSearchBookingDates({ commit }: ActionContext<IBookingState, IBookingState>, payload: IBookingDates): Promise<void> {
        localStorage.setItem(KEY_LAST_SEARCH_BOOKING_DATES, JSON.stringify(payload))
        commit('lastSearchBookingDates', payload)
    },

    async restoreLastSearchBookingDates({ commit }: ActionContext<IBookingState, IBookingState>): Promise<void> {
        const data: string | null = localStorage.getItem(KEY_LAST_SEARCH_BOOKING_DATES)

        if (data) {
            commit('lastSearchBookingDates', JSON.parse(data))
        }
    },

    async addToBasket({ state, commit }: ActionContext<IBookingState, IBookingState>, payload: ICalculateBookingInfoWithBookableTitle): Promise<void> {
        commit('addToBasket', payload)
        localStorage.setItem(KEY_BOOKING_BASKET, JSON.stringify(state.basket))
    },

    async removeFromBasket({ state, commit }: ActionContext<IBookingState, IBookingState>, bookableId: string): Promise<void> {
        commit('removeFromBasket', bookableId)
        localStorage.setItem(KEY_BOOKING_BASKET, JSON.stringify(state.basket))
    },

    async emptyBasket({ commit }: ActionContext<IBookingState, IBookingState>): Promise<void> {
        commit('emptyBasket')
        localStorage.removeItem(KEY_BOOKING_BASKET)
    },

    async restoreBasket({ commit }: ActionContext<IBookingState, IBookingState>): Promise<void> {
        const basketSrc: string | null = localStorage.getItem(KEY_BOOKING_BASKET)

        if (null !== basketSrc) {
            const basket: ICalculateBookingInfoWithBookableTitle[] = JSON.parse(basketSrc)

            if (Array.isArray(basket)) {
                basket.forEach((item: ICalculateBookingInfoWithBookableTitle) => {
                    commit('addToBasket', item)
                })
            }
        }
    },

    async saveCheckoutPerson({ state, commit }: ActionContext<IBookingState, IBookingState>, person: ICheckoutPeron|null): Promise<void> {
        commit('setCheckoutPerson', person)
        localStorage.setItem(KEY_CHECKOUT_PERSON, JSON.stringify(state.checkoutPerson))
    },

    async restoreCheckoutPerson({ commit }: ActionContext<IBookingState, IBookingState>): Promise<void> {
        const srcPerson: string|null = localStorage.getItem(KEY_CHECKOUT_PERSON)

        commit('setCheckoutPerson', srcPerson ? JSON.parse(srcPerson) : null)
    }
}
export default actions
