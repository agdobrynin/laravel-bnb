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
                v-model="data.start"
                label="Date from"
                type="date"
                :min="data.startMin"
                input-class="form-control-sm"
                label-class="col-form-label-sm"
                :disabled="isLoading"
                :errors="startFieldError"
            )
        div.col-md.mb-3
            InputUI(
                v-model="data.end"
                label="Date to"
                type="date"
                :min="data.endMin"
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
import type { Ref } from 'vue'
import { computed, reactive, ref } from 'vue'
import { useStore } from 'vuex'

import BookingDates from '@/Components/BookableView/BookingDates.vue'
import ApiErrorDisplay from '@/Components/UI/ApiErrorDisplay.vue'
import ButtonWithLoading from '@/Components/UI/ButtonWithLoading.vue'
import InputUI from '@/Components/UI/InputUI.vue'
import { DateRange } from '@/Models/DateRange'
import { ApiError } from '@/Services/ApiError'
import { ApiValidationError } from '@/Services/ApiValidationError'
import HttpService from '@/Services/HttpService'
import type { ApiErrorInterface } from '@/Services/Interfaces/ApiErrorInterface'
import type { ApiValidationErrorInterface } from '@/Services/Interfaces/ApiValidationErrorInterface'
import { bookingStateKey } from '@/store/Booking'
import type { IBookingAvailability } from '@/Types/IBookingAvailability'

const props = defineProps<{id: string}>()
const store = useStore(bookingStateKey)


const dateRange: DateRange = store.getters.lastSearchBookingDates
const data = reactive(dateRange)

const apiError: Ref<string|null> = ref(null)
const apiValidationError: Ref<ApiValidationError|null> = ref(null)

const startFieldError = computed(() => apiValidationError.value?.getErrorsByField('start'))
const endFieldError = computed(() => apiValidationError.value?.getErrorsByField('end'))

const isLoading: Ref<boolean> = ref(false)

const bookingAvailability: Ref<IBookingAvailability|null> = ref(null)

const check = async () => {
    isLoading.value = true
    apiError.value = null
    apiValidationError.value = null
    bookingAvailability.value = null

    try {
        bookingAvailability.value = await new HttpService()
            .checkBookableAvailability(props.id, data.start, data.end)

        await store.dispatch('saveLastSearchBookingDates', data.bookingDates)
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

.slide-fade-enter-active {
    transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
    transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateX(20px);
    opacity: 0;
}
</style>
