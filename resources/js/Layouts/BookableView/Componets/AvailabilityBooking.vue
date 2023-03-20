<template lang="pug">
form(@submit.prevent="check")
    h6.text-uppercase.text-secondary
        span.text-success(v-if="bookingAvailability && bookingAvailability.data.length === 0")
            | Available for booking
        span.text-danger(v-else-if="bookingAvailability")
            | Not available for booking
        span(v-else)
            | Check availability
    AlertDisplay(
        v-if="apiError"
        :icon-size="60" ) {{ apiError }}
    div.row.gap-2
        div.col-md.mb-3
            InputUI(
                v-model="dateRange.start"
                label="Date from"
                type="date"
                :min="dateRange.startMin"
                input-class="form-control-sm"
                label-class="col-form-label-sm"
                :disabled="isLoading"
                :errors="validation('start')"
            )
        div.col-md.mb-3
            InputUI(
                v-model="dateRange.end"
                label="Date to"
                type="date"
                :min="dateRange.endMin"
                input-class="form-control-sm"
                label-class="col-form-label-sm"
                :disabled="isLoading"
                :errors="validation('end')"
            )
    ButtonWithLoading.btn.btn-secondary.w-100(
        type="submit"
        :is-loading="isLoading") Check dates 🔎
    Transition(name="slide-fade")
        BookingDates(
            v-if="bookingAvailability && bookingAvailability.data.length"
            :items="bookingAvailability")
</template>

<script setup lang="ts">
import { storeToRefs } from 'pinia'
import type { Ref } from 'vue'
import { ref } from 'vue'

import AlertDisplay from '@/Components/UI/AlertDisplay.vue'
import ButtonWithLoading from '@/Components/UI/ButtonWithLoading.vue'
import InputUI from '@/Components/UI/InputUI.vue'
import { useApiErrors } from '@/Composable/useApiErrors'
import BookingDates from '@/Layouts/BookableView/Componets/BookingDates.vue'
import HttpApiService from '@/Services/HttpApiService'
import { useBookingViewStore } from '@/stores/booking-view'
import type { IBookingAvailability } from '@/Types/IBookingAvailability'
import type { IBookingDates } from '@/Types/IBookingAvailability'

const props = defineProps<{id: string}>()
const store = useBookingViewStore()

const { dateRange } = storeToRefs(store)
const { apiError, validation, errors } = useApiErrors()

const isLoading: Ref<boolean> = ref(false)
const bookingAvailability: Ref<IBookingAvailability|null> = ref(null)

const emit = defineEmits<{
    (e: 'isAvailability', value: IBookingDates | null): void
}>()

const check = async () => {
    isLoading.value = true
    errors(null)
    bookingAvailability.value = null
    emit('isAvailability', null)

    try {
        // store last dates for booking
        store.saveDateRangeToStorage()

        bookingAvailability.value = await new HttpApiService()
            .checkBookableAvailability(props.id, dateRange.value.start, dateRange.value.end)

        const isAvailabilityDates: IBookingDates | null = bookingAvailability.value?.data.length === 0
            ? { start: dateRange.value.start, end: dateRange.value.end }
            : null

        emit('isAvailability', isAvailabilityDates)
    } catch (reason) {
        errors(reason)
    }

    isLoading.value = false
}
</script>

<style scoped lang="scss">
.form-floating{
    label {
        left: 1.2rem
    }
}
</style>
