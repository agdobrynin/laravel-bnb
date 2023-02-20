<template lang="pug">
form(@submit.prevent="check")
    h6.text-uppercase.text-secondary
        span.text-success(v-if="bookingAvailability && bookingAvailability.data.length === 0")
            | Available for booking
        span.text-danger(v-else-if="bookingAvailability")
            | Not available for booking
        span(v-else)
            | Check availability
    div.alert.alert-danger(v-if="apiError") {{ apiError }}
    div.row.gap-2
        div.col-md.form-floating.mb-3
            input#dateFrom.form-control.form-control-sm(
                v-model="data.start"
                type="date"
                placeholder="Date from"
                :min="data.startMin"
                :class="{'is-invalid': startFieldError}"
                :disabled="isLoading"
                @change="resetBookingAvailability")
            label.text-secondary.col-form-label-sm(for="dateFrom") Date from
            div.invalid-feedback(
                v-for="(error, index) in startFieldError"
                :key="`start_${index}`") {{ error }}
        div.col-md.form-floating.mb-3
            input#dateTo.form-control-sm.form-control(
                    v-model="data.end"
                    type="date"
                    placeholder="Date to"
                    :min="data.endMin"
                    :class="{'is-invalid': endFieldError}"
                    :disabled="isLoading"
                    @change="resetBookingAvailability")
            label.text-secondary.col-form-label-sm(for="dateTo") Date to
            div.invalid-feedback(
                v-for="(error, index) in endFieldError"
                :key="`end_${index}`") {{error}}
    ButtonWithLoading.btn.btn-secondary.w-100(
        type="submit"
        :is-loading="isLoading"
        title="Check dates 🔎")
    BookingDates(
        v-if="bookingAvailability && bookingAvailability.data.length"
        :items="bookingAvailability")
</template>

<script setup lang="ts">
import type { Ref } from 'vue'
import { computed, reactive, ref } from 'vue'

import BookingDates from '@/Components/BookableView/BookingDates.vue'
import ButtonWithLoading from '@/Components/UI/ButtonWithLoading.vue'
import { DateRange } from '@/Models/DateRange'
import { ApiError } from '@/Services/ApiError'
import { ApiValidationError } from '@/Services/ApiValidationError'
import HttpService from '@/Services/HttpService'
import type { IBookingAvailability } from '@/Types/IBookingAvailability'

const props = defineProps<{id: string}>()

const dateRange: DateRange = new DateRange(new Date(), 4)
const data = reactive(dateRange)

const apiError: Ref<string|null> = ref(null)
const apiValidationError: Ref<ApiValidationError|null> = ref(null)

const startFieldError = computed(() => apiValidationError.value?.getErrorsByField('start'))
const endFieldError = computed(() => apiValidationError.value?.getErrorsByField('end'))

const isLoading: Ref<boolean> = ref(false)

const bookingAvailability: Ref<IBookingAvailability|null> = ref(null)

const resetBookingAvailability = () => bookingAvailability.value = null

const submit = () => alert(1)
const check = () => {
    isLoading.value = true
    apiError.value = null
    apiValidationError.value = null
    bookingAvailability.value = null

    new HttpService()
        .checkBookableAvailability(props.id, data.start, data.end)
        .then(response => {
            bookingAvailability.value = response as IBookingAvailability
        })
        .catch((result: ApiValidationError|ApiError) => {
            if(result instanceof ApiError) {
                apiError.value = result.backendMessage
            } else {
                apiValidationError.value = result
            }
        })
        .finally(() => isLoading.value = false)
}
</script>

<style scoped lang="scss">
.form-floating{
    label {
        left: 1.2rem
    }
}
</style>
