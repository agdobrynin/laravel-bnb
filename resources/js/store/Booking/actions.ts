import { ActionContext } from 'vuex'

import { IBookingState } from '@/store/Booking/Types/IBookingState'
import { IBookingDates } from '@/Types/IBookingAvailability'


const KEY_LAST_SEARCH_BOOKING_DATES = 'LastSearchBookingDates'

export default {
    async saveLastSearchBookingDates(context: ActionContext<IBookingState, IBookingState>, payload: IBookingDates): Promise<void> {
        localStorage.setItem(KEY_LAST_SEARCH_BOOKING_DATES, JSON.stringify(payload))
        context.commit('lastSearchBookingDates', payload)
    },

    async restoreLastSearchBookingDates(context: ActionContext<IBookingState, IBookingState>): Promise<void> {
        const data: string | null = localStorage.getItem(KEY_LAST_SEARCH_BOOKING_DATES)

        if (data) {
            context.commit('lastSearchBookingDates', JSON.parse(data))
        }
    }
}
