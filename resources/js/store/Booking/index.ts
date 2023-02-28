import { InjectionKey } from 'vue'
import { createStore, Store } from 'vuex'

import actions from '@/store/Booking/actions'
import getters from '@/store/Booking/getters'
import mutations from '@/store/Booking/mutations'
import { IBookingState } from '@/store/Booking/Types/IBookingState'

export const bookingStateKey: InjectionKey<Store<IBookingState>> = Symbol()

const storeBooking = createStore<IBookingState>({
    state: {
        abilityBooking: {
            dateRange: null,
        },
        basket: [],
    },
    actions,
    getters,
    mutations,
})

export default storeBooking
