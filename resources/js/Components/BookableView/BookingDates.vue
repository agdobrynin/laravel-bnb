<template lang="pug">
ul.list-group.mt-4.border.border-danger
    li.list-group-item.list-group-item-danger Booking dates
    li.list-group-item.text-danger(v-for="(dateItem, index) in bookingDates" :key="`is-booking-${index}`")
        span.item-small
            | From {{ dateItem.start }} to {{ dateItem.end }}
</template>

<script setup lang="ts">
import type { ComputedRef } from 'vue'

import { useFormattedBookingDates } from '@/Composable/useBookingDates'
import type { IBookingAvailability, IBookingDates } from '@/Types/IBookingAvailability'

interface Props extends IBookingAvailability {}

const props = defineProps<{items: Props}>()

const bookingDates: ComputedRef<IBookingDates[]> = useFormattedBookingDates(props.items)
</script>

<style scoped>
.item-small {
    font-size: 0.75rem;
}
</style>
