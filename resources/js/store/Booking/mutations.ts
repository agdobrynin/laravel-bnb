import { MutationTree } from 'vuex'

import { IBookingState } from '@/store/Booking/Types/IBookingState'
import { IBookingDates } from '@/Types/IBookingAvailability'

const mutations: MutationTree<IBookingState> = {
    lastSearchBookingDates(state: IBookingState, payload: IBookingDates | null): void {
        state.abilityBooking.dateRange = payload
    }
}

export default mutations
