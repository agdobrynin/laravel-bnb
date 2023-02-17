import type { ComputedRef } from 'vue'
import { computed } from 'vue'

import type { IBookingAvailability, IBookingDates } from '@/Types/IBookingAvailability'

export const useFormattedBookingDates = (bookingAbilityDates: IBookingAvailability): ComputedRef<IBookingDates[]> => {
    return computed(() => {
        return bookingAbilityDates?.data.map((item: IBookingDates) => {
            const { start, end } = item
            const dateStart = new Date(start)
            const dateEnd = new Date(end)

            return {
                start: dateStart.toDateString(),
                end: dateEnd.toDateString(),
            } as IBookingDates
        }) || []
    })
}
