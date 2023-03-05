<template lang="pug">
form(@submit.prevent="check")
    h6.text-uppercase.text-secondary
        span.text-success(v-if="bookingAvailability && bookingAvailability.data.length === 0")
            | Available for booking
        span.text-danger(v-else-if="bookingAvailability")
            | Not available for booking
        span(v-else)
            | Check availability
    ApiErrorDisplay(
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
                :errors="startFieldError"
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
                :errors="endFieldError"
            )
    ButtonWithLoading.btn.btn-secondary.w-100(
        type="submit"
        :is-loading="isLoading"
        title="Check dates 🔎")
    Transition(name="slide-fade")
        BookingDates(
            v-if="bookingAvailability && bookingAvailability.data.length"
            :items="bookingAvailability")
</template>

<script setup lang="ts">
import { storeToRefs } from 'pinia'
import type { Ref } from 'vue'
import { computed, ref } from 'vue'

import BookingDates from '@/Components/BookableView/BookingDates.vue'
import ApiErrorDisplay from '@/Components/UI/ApiErrorDisplay.vue'
import ButtonWithLoading from '@/Components/UI/ButtonWithLoading.vue'
import InputUI from '@/Components/UI/InputUI.vue'
import { ApiError } from '@/Services/ApiError'
import { ApiValidationError } from '@/Services/ApiValidationError'
import HttpService from '@/Services/HttpService'
import type { ApiErrorInterface } from '@/Services/Interfaces/ApiErrorInterface'
import type { ApiValidationErrorInterface } from '@/Services/Interfaces/ApiValidationErrorInterface'
import { useBookingViewStore } from '@/stores/booking-view'
import type { IBookingAvailability } from '@/Types/IBookingAvailability'
import type { IBookingDates } from '@/Types/IBookingAvailability'

const props = defineProps<{id: string}>()
const store = useBookingViewStore()

const { dateRange } = storeToRefs(store)

const apiError: Ref<string|null> = ref(null)
const apiValidationError: Ref<ApiValidationError|null> = ref(null)

const startFieldError = computed(() => apiValidationError.value?.getErrorsByField('start'))
const endFieldError = computed(() => apiValidationError.value?.getErrorsByField('end'))

const isLoading: Ref<boolean> = ref(false)

const bookingAvailability: Ref<IBookingAvailability|null> = ref(null)

const emit = defineEmits<{
    (e: 'isAvailability', value: IBookingDates | null): void
}>()

const check = async () => {
    isLoading.value = true
    apiError.value = null
    apiValidationError.value = null
    bookingAvailability.value = null
    emit('isAvailability', null)

    try {
        // store last dates for booking
        store.saveDateRangeToStorage()

        bookingAvailability.value = await new HttpService()
            .checkBookableAvailability(props.id, dateRange.value.start, dateRange.value.end)

        const isAvailabilityDates: IBookingDates | null = bookingAvailability.value?.data.length === 0
            ? { start: dateRange.value.start, end: dateRange.value.end }
            : null

        emit('isAvailability', isAvailabilityDates)
    } catch (reason) {
        const error = reason as Error | ApiErrorInterface | ApiValidationErrorInterface

        if (error instanceof ApiValidationError) {
            apiValidationError.value = error
        } else if (error instanceof ApiError) {
            apiError.value = error.apiError?.message || error.requestError
        } else {
            apiError.value = (error as Error).message
        }
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
