import { acceptHMRUpdate, defineStore } from 'pinia'

import { DateRange } from '@/Models/DateRange'
import { IBookingDates } from '@/Types/IBookingAvailability'

interface IBookingView {
    dateRange: DateRange,
}

const KEY_DATE_ABILITY = 'DATE_ABILITY'
export const useBookingViewStore = defineStore('booking-view', {
    state: (): IBookingView => ({
        dateRange: new DateRange(new Date(), 4),
    }),
    actions: {
        saveDateRangeToStorage(): void {
            localStorage.setItem(KEY_DATE_ABILITY, JSON.stringify(this.dateRange.bookingDates))
        },

        restoreDateRangeFromStorage(): void {
            const srcBookingDates = localStorage.getItem(KEY_DATE_ABILITY)
            const dateRange = new DateRange(new Date(), 4)

            if (srcBookingDates) {
                const bookingDates: IBookingDates = JSON.parse(srcBookingDates)

                if ('start' in bookingDates && 'end' in bookingDates) {
                    dateRange.start = bookingDates.start
                    dateRange.end = bookingDates.end
                }
            }

            this.dateRange = dateRange
        }
    }
})

if (import.meta.hot) {
    import.meta.hot.accept(acceptHMRUpdate(useBookingViewStore, import.meta.hot))
}
