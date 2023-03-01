import { ActionContext, ActionTree } from 'vuex'

import { IBookingState } from '@/store/Booking/Types/IBookingState'
import { IBookingDates } from '@/Types/IBookingAvailability'
import { ICalculateBookingInfoWithBookableTitle } from '@/Types/ICalculateBooking'


const KEY_LAST_SEARCH_BOOKING_DATES = 'LastSearchBookingDates'
const KEY_BOOKING_BASKET = 'BookingBasket'

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
    }
}
export default actions
