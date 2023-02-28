<template lang="pug">
div
    ApiErrorDisplay(v-if="apiError") {{ apiError }}
    PlaceholderCard(v-if="loading")
    div(v-if="!apiError && !loading" )
        div.row
            div.col-md-8.mb-4
                div.card.mb-4
                    div.card-body
                        h2 {{ bookable.title }}
                        hr
                        article {{ bookable.description }}
                div.mb-4.mx-1
                    ReviewList(:bookabled-id="bookable.id")
            div.col-md-4.mb-4
                AvailabilityBooking(
                    :id="bookable.id"
                    @is-availability="checkPrice")
                ApiErrorDisplay(
                    v-if="calculatePriceError"
                    :icon-size="40")
                        div.alert.alert-danger.fs-6(
                            v-for="(error, index) in calculatePriceError"
                            :key="`calc-price-${index}`") {{ error }}
                Transition(name="bounce")
                    PriceBrekadown.mt-4(
                        v-if="calculate"
                        :calculate-booking="calculate")
                        template(#header) Booking price
                Transition.mt-4(name="bounce")
                    ButtonWithLoading.btn.btn-outline-success.mt-4.w-100(
                        v-if="calculate"
                        :is-loading="false"
                        title="Booking now")
</template>

<script setup lang="ts">
import type { ComputedRef, Ref } from 'vue'
import { computed, onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'

import AvailabilityBooking from '@/Components/BookableView/AvailabilityBooking.vue'
import PriceBrekadown from '@/Components/BookableView/PriceBrekadown.vue'
import ReviewList from '@/Components/BookableView/Review/ReviewList.vue'
import ApiErrorDisplay from '@/Components/UI/ApiErrorDisplay.vue'
import ButtonWithLoading from '@/Components/UI/ButtonWithLoading.vue'
import PlaceholderCard from '@/Components/UI/PlaceholderCard.vue'
import { ApiError } from '@/Services/ApiError'
import { ApiValidationError } from '@/Services/ApiValidationError'
import HttpService from '@/Services/HttpService'
import type { ApiErrorInterface } from '@/Services/Interfaces/ApiErrorInterface'
import type { ApiValidationErrorInterface } from '@/Services/Interfaces/ApiValidationErrorInterface'
import type { IBookable } from '@/Types/IBookable'
import type { IBookableItem } from '@/Types/IBookableItem'
import type { IBookingDates } from '@/Types/IBookingAvailability'
import type { ICalculateBooking, ICalculateBookingInfo } from '@/Types/ICalculateBooking'

const id: string = useRoute().params.id as string

const loading: Ref<boolean> = ref(true)
const bookableItem: Ref<IBookableItem | null> = ref(null)
const apiError: Ref<string|null> = ref(null)
const calculate: Ref<ICalculateBookingInfo|null> = ref(null)
const calculatePriceError: Ref<string[]| null> = ref(null)

const bookable: ComputedRef<IBookable | null> = computed(() => bookableItem.value?.data || null)

const checkPrice = async (isAvailable: IBookingDates | undefined): Promise<void> => {
    calculatePriceError.value = null
    calculate.value = null

    if (isAvailable && bookable.value?.id) {
        try {
            const calculateResponse: ICalculateBooking = await new HttpService()
                .calculateBooking(bookable.value.id, isAvailable.start, isAvailable.end)
            calculate.value = calculateResponse.data.calculate
        } catch (reason) {
            const error = reason as Error | ApiErrorInterface | ApiValidationErrorInterface
            if (error instanceof ApiValidationError) {
                calculatePriceError.value = error.validationErrors
            } else if (error instanceof ApiError) {
                calculatePriceError.value = [error.apiError?.message || error.requestError]
            } else {
                calculatePriceError.value = [(error as Error).message]
            }
        }
    }
}

onMounted(async () => {
    try {
        bookableItem.value = await new HttpService().getBookable(id)
    } catch (reason) {
        const error = reason as ApiErrorInterface
        apiError.value = error.apiError?.message || error.requestError
    }

    loading.value = false
})
</script>
