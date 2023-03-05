import { acceptHMRUpdate, defineStore } from 'pinia'

import { ICheckoutPeron } from '@/Types/ICheckout'

const KEY_CHECKOUT_PERSON = 'CHECKOUT_PERSON'

interface ICheckoutPersonState {
    person: ICheckoutPeron
}

export const useCheckoutPersonStore = defineStore('checkout-person', {
    state: (): ICheckoutPersonState => ({
        person: { firstName: '', lastName: '', address: '', email: '', phone: '' }
    }),
    actions: {
        saveToStorage(): void {
            localStorage.setItem(KEY_CHECKOUT_PERSON, JSON.stringify(this.person))
        },

        restoreFromStorage(): void {
            const src = localStorage.getItem(KEY_CHECKOUT_PERSON)

            if (src) {
                // TODO my be validate data from storage
                this.person = JSON.parse(src)
            }
        }
    }
})

if (import.meta.hot) {
    import.meta.hot.accept(acceptHMRUpdate(useCheckoutPersonStore, import.meta.hot))
}
