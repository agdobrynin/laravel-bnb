import { acceptHMRUpdate, defineStore } from 'pinia'

import { ICalculateBookingInfo, ICalculateBookingInfoWithBookableTitle } from '@/Types/ICalculateBooking'

const KEY_BASKET = 'BASKET'

interface IBasketState {
    basket: ICalculateBookingInfoWithBookableTitle[]
}

export const useBasketStore = defineStore('basket', {
    state: (): IBasketState => ({
        basket: []
    }),
    actions: {
        inBasket(bookableId: string): ICalculateBookingInfoWithBookableTitle | undefined {
            return this.basket
                .find((item: ICalculateBookingInfoWithBookableTitle) => item.bookableId === bookableId)
        },

        addToBasket(item: ICalculateBookingInfoWithBookableTitle): void {
            this.basket.push(item)
            this.saveBasketToStorage()
        },

        removeFromBasket(bookableId: string): void {
            this.basket = this.basket.filter((item: ICalculateBookingInfo) => item.bookableId !== bookableId)
            this.saveBasketToStorage()
        },

        emptyBasket(): void {
            this.basket = []
            this.saveBasketToStorage()
        },

        saveBasketToStorage(): void {
            localStorage.setItem(KEY_BASKET, JSON.stringify(this.basket))
        },

        restoreBasketFromStorage(): void {
            // TODO validate date from storage
            this.basket = JSON.parse(localStorage.getItem(KEY_BASKET) || '[]')
        },
    }
})

if (import.meta.hot) {
    import.meta.hot.accept(acceptHMRUpdate(useBasketStore, import.meta.hot))
}
