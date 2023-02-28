import { ActionContext, ActionTree } from 'vuex'

import { IBookingState } from '@/store/Booking/Types/IBookingState'
import { IBookingDates } from '@/Types/IBookingAvailability'


const KEY_LAST_SEARCH_BOOKING_DATES = 'LastSearchBookingDates'

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
    }
}
export default actions
